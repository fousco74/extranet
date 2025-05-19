<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\OneDriveLink;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FolderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // Middleware pour les actions spécifiques
            new Middleware('permission:créer un dossier', only: ['create']),
            new Middleware('permission:liste des dossiers', only: ['index']),
            new Middleware('permission:modifier dossier', only: ['edit']),
            new Middleware('permission:supprimer dossier', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $folders = Folder::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->with('files')
          ->paginate(10);
    
        return inertia('Folders/index', ['folders' => $folders]);
    }

    public function create()
    {
        $links = OneDriveLink::all();
        return inertia('Folders/create', ['links' => $links]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:folders',
        ]);

        Folder::create($request->all());

        return redirect()->route('folders.index')->with('success', 'Dossier créer avec succès.');
    }

    public function show(Folder $folder)
    {
        return inertia('folders/show', ['folder' => $folder->load('files')]);
    }

    public function edit(Folder $folder)
    {
        $links = OneDriveLink::all();
        return inertia('Folders/edit', ['folder' => $folder, 'links' => $links]);
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update($request->all());

        return redirect()->route('folders.index')->with('success', 'Dossier mis à jour avec succès.');
    }

    public function destroy(Folder $folder)
    {
        $folder->delete();
        return redirect()->route('folders.index')->with('success', 'Dossier supprimé avec succès.');
    }
}
