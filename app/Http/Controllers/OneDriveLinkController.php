<?php

namespace App\Http\Controllers;

use App\Models\OneDriveLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OneDriveLinkController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            // Middleware pour les actions spécifiques
            new Middleware('permission:liste des liens onedrives', only: ['index']),
            new Middleware('permission:modifier onedrive', only: ['edit']),
            new Middleware('permission:supprimer onedrive', only: ['destroy']),
        ];
    }

    public function index()
    {
        $links = OneDriveLink::with('users')->paginate(6);
        return inertia('OneDriveLinks/index', ['links' => $links]);
    }

    public function create()
    {
        $users = User::all();
        return inertia('OneDriveLinks/create', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'link' => 'required|url',
            'name' => 'required|string'
        ]);

        
        $oneDriveLink = OneDriveLink::create($validated);

        return redirect()->route('one-drive-links.index')->with('success', 'Lien onedrive créer avec succès.');
    }

    public function show(OneDriveLink $oneDriveLink)
    {
        $oneDriveLink->load('users');
        return inertia('OneDriveLinks/Show', ['link' => $oneDriveLink]);
    }

    public function edit(OneDriveLink $oneDriveLink)
    {
        $users = User::all();
        $oneDriveLink->load('users');
        return inertia('OneDriveLinks/edit', ['link' => $oneDriveLink, 'users' => $users]);
    }

    public function update(Request $request, OneDriveLink $oneDriveLink)
    {
       $validated = $request->validate([
            'link' => 'required|url|max:255',
            
        ]);

        $oneDriveLink->update($validated);

        return redirect()->route('one-drive-links.index')->with('success', 'Lien onedrive mis à jour avec succès.');
    }

    public function destroy(OneDriveLink $oneDriveLink)
    {
        $oneDriveLink->users()->detach(); // Supprimer les relations avec les utilisateurs
        $oneDriveLink->delete();

        return redirect()->route('one-drive-links.index')->with('success', 'Lien onedrive supprimé avec succès.');
    }
}
