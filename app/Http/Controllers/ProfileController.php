<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        ]);

    
        // Gestion du fichier de profil
        if ($request->hasFile('profile_link')) {
            if ($user->profile_link) {
                Storage::disk('public')->delete($user->profile_link);
            }
            $validated['profile_link'] = $request->file('profile_link')->store('profile', 'public');
        }
    
        // Hash du mot de passe
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (empty($validated['profile_link'])) {
            unset($validated['profile_link']);
        } 
    
        // Mise à jour de l'utilisateur
        $user->update($validated);
    
        return redirect()->route('home')->with('message', 'Utilisateur mis à jour avec succès.');
    }
}
