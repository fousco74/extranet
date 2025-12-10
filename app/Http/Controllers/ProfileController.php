<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return inertia('frontend/users/profile', [
            'user' => $user,
        ]);
    }

   public function update(Request $request, User $user)
{
    // 1) Normalisation (évite la date invalide "0000-00-00")
    if ($request->input('birth_date') === '0000-00-00') {
        $request->merge(['birth_date' => null]);
    }

    // 2) Validation
    $validated = $request->validate([
        'first_name'     => ['required','string','max:255'],
        'last_name'      => ['required','string','max:255'],
        'poste'          => ['required','string','max:255'],
        'team'           => ['required','string','max:255'],
        'email'          => ['required','email', Rule::unique('users','email')->ignore($user->id)],
        'phone_number'   => ['nullable','string','max:20'],
        'profile_link'   => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
        'linkedin_link'  => ['nullable','url','max:255'],
        'password'       => ['nullable','string','min:8','confirmed'],
        // si tu ne l’envoies pas toujours, garde "nullable"
        'birth_place'    => ['required','string','max:255'],
        'birth_date'     => ['nullable','date'], // <= ici nullable
        'nationality'    => ['required','string','max:255'],
        'marital_status' => ['required','string','max:255'],
        'address'        => ['required','string','max:255'],
        // 'ordre_team'   => ['nullable','numeric', Rule::unique('users','ordre_team')->ignore($user->id)],
    ]);

    // 3) Fichier (avatar)
    if ($request->hasFile('profile_link')) {
        if ($user->profile_link) {
            Storage::disk('public')->delete($user->profile_link);
        }
        $validated['profile_link'] = $request->file('profile_link')->store('profile', 'public');
    } else {
        // si non fourni, on n’écrase pas
        unset($validated['profile_link']);
    }

    // 4) Mot de passe
    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    // 5) Update
    $user->update($validated);

    // 6) Redirection fiable pour Inertia
    return redirect()->route('profile.edit', $user, 303)
        ->with('message', 'Utilisateur mis à jour avec succès.');
    // ou hard reload :
    // return Inertia::location(route('profile.edit', $user));
}
}
