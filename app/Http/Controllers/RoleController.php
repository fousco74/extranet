<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->paginate(10);

        return inertia('roles/index', compact('roles'));
    }

    public function create()
    {
        return inertia('roles/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return inertia('roles/edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }


    public function showPermissions($id)
{
    $role = Role::findOrFail($id);
    $rolePermissions = $role->permissions;
    $permissions = Permission::all();


$permissionsByCategory = [
    'Utilisateurs' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'utilisateur')),
    'Dossiers' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'dossier')),
    'Fichiers' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'fichier')),
    'Liens OneDrives' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'liens onedrive')),
    'Applications' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'application')),
    'Reservations' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'reservation')),
    'Notifications' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'notification')),
    'Parametre' => $permissions->filter(fn($permission) => Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'parametre')),
    'Autres' => $permissions->filter(fn($permission) => !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'utilisateur')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'dossier')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'fichier')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'liens onedrive')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'application')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'reservation')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'notification')
        && !Str::contains(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $permission->name)), 'parametre'))
];




    return inertia('roles/permissions', compact('role', 'permissionsByCategory', 'rolePermissions'));
}

    

    public function updatePermissions(Request $request, $id)
    {
     // Récupérer le rôle
        $role = Role::findOrFail($id);

        // Récupérer les permissions depuis la requête
        $permissions = $request->input('permissions', []);

        // Retirer toutes les permissions actuelles du rôle
        $role->revokePermissionTo($role->permissions);

        // Attribuer les nouvelles permissions au rôle
        foreach ($permissions as $permissionId) {
            $permission = Permission::findOrFail($permissionId);
            $role->givePermissionTo($permission);
        }

        // Rediriger avec un message de succès
        return redirect()->route('role.permissions',$id)->with('successMessage', 'Permissions mises à jour avec succès.');
    }

}
