<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Mail\SuggestionMail;
use App\Mail\UserCreate;
use App\Models\User;
use App\Models\OneDriveLink;
use App\Notifications\InformationNotification;
use Illuminate\Notifications\DatabaseNotification;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:créer un utilisateur', only: ['create']),
            new Middleware('permission:envoyer une notification', only: ['sendNotification']),
            new Middleware('permission:liste des utilisateurs', only: ['index']),
            new Middleware('permission:liens onedrives utilisateur', only: ['editOneDriveLinks']),
            new Middleware('permission:supprimer utilisateur', only: ['destroy']),
            new Middleware('permission:modifier utilisateur', only: ['edit', 'update']),
        ];
    }

    /* ----------------------------- USERS CRUD ----------------------------- */

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('ordre_team', 'like', "%{$search}%")
                  ->orWhere('team', 'like', "%{$search}%")
                  ->orWhere('poste', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(6);

        return inertia('users/index', [
            'users' => $users,
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return inertia('users/create', [
            'oneDriveLinks' => OneDriveLink::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'poste' => 'required|string|max:255',
            'team' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'profile_link' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkedin_link' => 'nullable|url|max:255',
            'password' => 'required|string|min:8|confirmed',
            'ordre_team' => 'nullable|numeric|unique:users,ordre_team',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'nationality' => 'required|string|max:255',
            'marital_status' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($request->hasFile('profile_link')) {
            $validated['profile_link'] = $request->file('profile_link')->store('profile', 'public');
        }

        try {
            Mail::to($validated['email'])->send(new UserCreate($validated));
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'email' => 'L\'envoi d\'email a échoué. Veuillez vérifier l\'adresse.',
            ]);
        }

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return to_route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return inertia('users/edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'poste' => 'required|string|max:255',
            'team' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_link' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkedin_link' => 'nullable|url|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'ordre_team' => 'nullable|numeric|unique:users,ordre_team,' . $user->id,
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'nationality' => 'required|string|max:255',
            'marital_status' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($request->hasFile('profile_link')) {
            if ($user->profile_link && Storage::disk('public')->exists($user->profile_link)) {
                Storage::disk('public')->delete($user->profile_link);
            }
            $validated['profile_link'] = $request->file('profile_link')->store('profile', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (empty($validated['profile_link'])) {
            unset($validated['profile_link']);
        }

        $user->update($validated);

        return to_route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->profile_link && Storage::disk('public')->exists($user->profile_link)) {
            Storage::disk('public')->delete($user->profile_link);
        }

        $user->oneDriveLinks()->detach();
        $user->delete();

        return to_route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /* ----------------------------- AUTH ----------------------------- */

    public function login()
    {
        return inertia('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = Str::of($credentials['email'])->trim()->lower()->value();
        $remember = $request->boolean('remember');
        $throttleKey = $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => __('Trop de tentatives. Réessayez dans :seconds secondes.', [
                    'seconds' => RateLimiter::availableIn($throttleKey)
                ]),
            ]);
        }

        if (!Auth::attempt(['email' => $email, 'password' => $credentials['password']], $remember)) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => __('Identifiants invalides.'),
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return Inertia::location(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Inertia::location(route('login'));
    }

    /* ------------------------- ONEDRIVE LINKS ------------------------- */

    public function editOneDriveLinks(User $user)
    {
        return inertia('users/userOneDrive', [
            'user' => $user,
            'allLinks' => OneDriveLink::all(),
            'userLinks' => $user->oneDriveLinks->pluck('id')->toArray(),
        ]);
    }

    public function updateOneDriveLinks(Request $request, User $user)
    {
        $validated = $request->validate([
            'links' => 'nullable|array',
            'links.*' => 'exists:one_drive_links,id',
        ]);

        $user->oneDriveLinks()->sync($validated['links'] ?? []);

        return to_route('users.index')->with('success', 'Liens OneDrive mis à jour avec succès.');
    }

    /* ---------------------------- ROLES ---------------------------- */

    public function showRoles($user)
    {
        $user = User::with('roles')->findOrFail($user);
        return inertia('users/roles', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = $request->input('roles', []);

        $user->syncRoles($roles);

        return to_route('user.roles', $id)->with('success', 'Rôles mis à jour avec succès.');
    }

    /* ---------------------------- NOTIFS & MAIL ---------------------------- */

    public function send(Request $request)
    {
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to('nkakou@amoaman.com')->send(new SuggestionMail($validated));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Erreur lors de l\'envoi du message.']);
        }

        return back()->with('success', 'Suggestion envoyée avec succès.');
    }

    public function notification()
    {
        return inertia('users/notification');
    }

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'equipe' => 'nullable|string',
        ]);

        $user = Auth::user();
        $equipe = $validated['equipe'] ?? null;

        $query = User::where('id', '!=', $user->id)
            ->whereNotIn('ordre_team', [1, 2, 3]);

        if ($equipe) {
            $query->where('team', $equipe);
        }

        $users = $query->get();
        Notification::sendNow($users, new InformationNotification($user, $validated));

        return back()->with('success', 'Notification envoyée avec succès.');
    }

    public function notificationList()
    {
        return inertia('Notifications/NotificationList', [
            'notifications' => DatabaseNotification::paginate(10),
        ]);
    }

    public function notificationDestroy($id)
    {
        $notif = DatabaseNotification::findOrFail($id);
        $notif->delete();

        return to_route('notifications.index')->with('success', 'Notification supprimée avec succès.');
    }
}
