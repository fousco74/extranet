<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->get();
    
        $permissionsByCategory = [
            'Utilisateur' => $permissions->filter(fn($permission) => str_contains(strtolower($permission->name), 'utilisateurs') || str_contains(strtolower($permission->name), 'utilisateur')),
            'Dossiers' => $permissions->filter(fn($permission) => str_contains(strtolower($permission->name), 'dossiers') || str_contains(strtolower($permission->name), 'dossier')),
            'Fichiers' => $permissions->filter(fn($permission) => str_contains(strtolower($permission->name), 'fichiers') || str_contains(strtolower($permission->name), 'fichier')),
            'Lien OneDrive' => $permissions->filter(fn($permission) => str_contains(strtolower($permission->name), 'onedrives') || str_contains(strtolower($permission->name), 'onedrive')),
            'Applications' => $permissions->filter(fn($permission) => str_contains(strtolower($permission->name), 'applications') || str_contains(strtolower($permission->name), 'application')),
            'Reservations' => $permissions->filter(fn($permission) => str_contains(strtolower($permission->name), 'reservations') || str_contains(strtolower($permission->name), 'reservation')),
            'Autres' => $permissions->filter(fn($permission) => !str_contains(strtolower($permission->name), 'utilisateur')
                && !str_contains(strtolower($permission->name), 'dossiers')
                && !str_contains(strtolower($permission->name), 'dossier')
                && !str_contains(strtolower($permission->name), 'fichiers')
                && !str_contains(strtolower($permission->name), 'fichier')
                && !str_contains(strtolower($permission->name), 'onedrive')
                && !str_contains(strtolower($permission->name), 'lien onedrive')
                && !str_contains(strtolower($permission->name), 'applications')
                && !str_contains(strtolower($permission->name), 'application')
                && !str_contains(strtolower($permission->name), 'reservations')
                && !str_contains(strtolower($permission->name), 'reservation'))
        ];
        
    
        return inertia('permissions/index', compact('permissionsByCategory'));
    }
    


    public function create()
    {
        return inertia('permissions/create');
    }

    public function store(Request $request)
    {
       // Valider la demande
       $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Créer ou récupérer la permission
    Permission::firstOrCreate(['name' => $request->name, 'guard_name' => 'web']);

    return redirect()->route('permissions.index')->with('success', 'Permission créée avec succès.');

    }

    public function edit(Permission $permission)
    {
        return inertia('permissions/edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $permission->update($request->all());

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
