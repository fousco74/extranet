<?php

namespace App\Http\Controllers;

use App\Mail\SuggestionMail;
use App\Mail\UserCreate;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\OneDriveLink;
use App\Notifications\InformationNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;




class UserController extends Controller implements HasMiddleware

{
    public static function middleware(): array
    {
        return [

            // Middleware pour les actions spécifiques
            new Middleware('permission:créer un utilisateur', only: ['create']),
            new Middleware('permission:envoyer une notification', only: ['sendNotification']),
            new Middleware('permission:liste des utilisateurs', only: ['index']),
            new Middleware('permission:liens onedrives utilisateur', only: ['editOneDriveLinks']),
            new Middleware('permission:supprimer utilisateur', only: ['destroy']),
            new Middleware('permission:modifier utilisateur', only: ['edit']),
        ];
    }

    public function index(Request $request)
{
    $query = User::query();

    // Filtrage global
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%{$searchTerm}%")
              ->orWhere('last_name', 'like', "%{$searchTerm}%")
              ->orWhere('ordre_team', 'like', "%{$searchTerm}%")
              ->orWhere('team', 'like', "%{$searchTerm}%")
              ->orWhere('poste', 'like', "%{$searchTerm}%")
              ->orWhere('email', 'like', "%{$searchTerm}%")
              ->orWhere('phone_number', 'like', "%{$searchTerm}%");
        });
    }

    // Pagination
    $users = $query->paginate(6);

    // Retourner les utilisateurs vers la vue
    return inertia('users/index', [
        'users' => $users,
        'filters' => $request->only('search'), // Persister le filtre pour le formulaire
    ]);
}


    // Affichage du formulaire de création d'utilisateur
    public function create()
    {
        $oneDriveLinks = OneDriveLink::all();
        return inertia('users/create', ['oneDriveLinks' => $oneDriveLinks]);
    }

    // Sauvegarde d'un nouvel utilisateur
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
            'ordre_team' =>'nullable|numeric|unique:users,ordre_team',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required',
            'nationality' => 'required|string|max:255',
            'marital_status' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Gestion de l'image
        if ($request->hasFile('profile_link')) {
            $validated['profile_link'] = Storage::disk('public')->put("profile", $request->profile_link);
        }

        // Test d'envoi d'email avant la création de l'utilisateur
        try {
            // Essai d'envoi d'email à l'adresse fournie
            Mail::to($validated['email'])->send(new UserCreate($validated));
        } catch (\Exception $e) {
            // En cas d'erreur, retour avec un message sans créer l'utilisateur
            return redirect()->back()->withInput()->withErrors(['email' => 'L\'email est invalide ou l\'envoi a échoué. Veuillez vérifier l\'adresse email.']);
        }

        // Si tout est bon, on crée l'utilisateur
        $user = User::create($validated);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    // Affichage des détails d'un utilisateur
    public function show(User $user)
    {
        $user->load('oneDriveLinks');
        return inertia('Users/show', ['user' => $user]);
    }

    // Affichage du formulaire d'édition d'utilisateur
    public function edit(User $user)
    {
        return inertia('users/edit', [
            'user' => $user,
        ]);
    }

    // Mise à jour des informations d'un utilisateur
    public function update(Request $request, User $user)
{
    // Validation
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
        'birth_date' => 'required',
         'nationality' => 'required|string|max:255',
        'marital_status' => 'required|string|max:255',
        'address' => 'required|string|max:255',
    ]);

    // Gestion du fichier de profil
    if ($request->hasFile('profile_link')) {
        if ($user->profile_link) {
            Storage::disk('public')->delete($user->profile_link);
        }
        $validated['profile_link'] = Storage::disk('public')->put("profile", $request->profile_link);
    }

    // Hash du mot de passe si fourni
    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    // Supprimer le champ profile_link s'il n'y a pas de nouveau fichier
    if (empty($validated['profile_link'])) {
        unset($validated['profile_link']);
    }



    // Mise à jour de l'utilisateur
    $user->update($validated);

    return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
}




    // Suppression d'un utilisateur
    public function destroy(User $user)
    {

        if($user->profile_link){
            Storage::delete($user->profile_link);
        }
        $user->oneDriveLinks()->detach(); // Supprimer les relations avec les OneDriveLinks
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Affichage du formulaire de connexion
    public function login()
    {
        return inertia('login');
    }

    // Soumission du formulaire de connexion
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Déconnexion de l'utilisateur
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function editOneDriveLinks(User $user)
{
    $allLinks = OneDriveLink::all(); // Tous les liens disponibles
    $userLinks = $user->oneDriveLinks->pluck('id')->toArray(); // Liens associés à cet utilisateur

    return inertia('users/userOneDrive', [
        'user' => $user,
        'allLinks' => $allLinks,
        'userLinks' => $userLinks,
    ]);
}

public function updateOneDriveLinks(Request $request, User $user)
{
    // Validation des liens OneDrive envoyés
    $validated = $request->validate([
        'links' => 'nullable|array',
        'links.*' => 'exists:one_drive_links,id', // Vérifie que chaque ID de lien existe dans la table one_drive_links
    ]);


    // Synchronisation des liens OneDrive avec l'utilisateur
    $user->oneDriveLinks()->sync($validated['links'] ?? []); // On met à jour la relation

    // Redirection avec un message de succès
    return redirect()->route('users.index')->with('success', 'Liens OneDrive mis à jour avec succès.');
}


public function showRoles($user)
{
    $user = User::with('roles')->findOrFail($user);
    $roles = Role::all();

    return inertia('users/roles', compact('user', 'roles'));
}

public function updateRoles(Request $request, $id)
{

     // Récupérer l'utilisateur
     $user = User::findOrFail($id);

     // Récupérer les rôles depuis la requête
     $roles = $request->input('roles', []);

     // Retirer tous les rôles actuels de l'utilisateur
     foreach ($user->roles as $role) {
         $user->removeRole($role);
     }

     // Attribuer les nouveaux rôles à l'utilisateur
     foreach ($roles as $roleId) {
         $role = Role::findOrFail($roleId);
         $user->assignRole($role);
     }



     // Rediriger avec un message de succès
     return redirect()->route('user.roles', $id)->with('success', 'Rôles mis à jour avec succès.');
}


public function send(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'objet' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    try {
        // Tentative d'envoi de l'email
        Mail::to('nkakou@amoaman.com')->send(new SuggestionMail($validated));
    } catch (\Exception $e) {
        // Gestion de l'erreur et retour avec message d'erreur
        return back()->withInput()->withErrors([
            'email' => 'Une erreur est survenue lors de l\'envoi de votre suggestion. Veuillez réessayer plus tard.',
        ]);
    }

    // Retour avec un message de succès
    return back()->with('success', 'Votre suggestion a été envoyée avec succès.');
}


    public function notification(){

        return inertia('users/notification');
    }

    public function sendNotification(Request $request){

        $equipe = $request->input('equipe');
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string'

        ]);

        $user = Auth::user();

        if($equipe =='interne'){
            $users = User::where('id', '!=', Auth::user()->id)->where('team',$equipe)->whereNotIn('ordre_team', [1, 2, 3])->get();
        }else if($equipe =='externe'){
            $users = User::where('id', '!=', Auth::user()->id)->where('team',$equipe)->whereNotIn('ordre_team', [1, 2, 3])->get();
        }else{
            $users = User::where('id', '!=', Auth::user()->id)->whereNotIn('ordre_team', [1, 2, 3])->get();
        }



        Notification::sendNow($users, new InformationNotification($user, $validated));
        return redirect()->back()->with('success', 'la notification a été envoyé avec succès');
    }

    public function notificationList()
    {
        $notifications = DatabaseNotification::Paginate(10);

        return inertia('Notifications/NotificationList', [
            'notifications' => $notifications
        ]);
    }

    public function notificationDestroy($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        $notification->delete();

       return redirect()->route('notifications.index')->with('message','Notification supprimée avec succès');
    }

}








