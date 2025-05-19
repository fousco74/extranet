<?php

namespace App\Http\Controllers;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicationController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            // Middleware pour les actions spécifiques
            new Middleware('permission:ajouter une application', only: ['create']),
            new Middleware('permission:liste des applications', only: ['index']),
            new Middleware('permission:modifier application', only: ['edit']),
            new Middleware('permission:supprimer application', only: ['destroy']),
        ];
    }

    // Afficher toutes les applications
    public function index(Request $request)
    {
        $applications = Application::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%");
        })
        ->paginate(5);
        return inertia('applications/index', compact('applications'));
    }

    // Formulaire de création d'une nouvelle application
    public function create()
    {
        return inertia('applications/create');
    }

    // Enregistrer une nouvelle application
    public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:applications',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }



        Application::create($validated);


        return redirect()->route('applications.index')->with('success', 'Application ajoutée avec succès.');
    }

    // Afficher un formulaire d'édition d'une application
    public function edit(Application $application)
    {
        
        return inertia('applications/edit', compact('application'));
    }

    // Mettre à jour une application existante
    public function update(Request $request, Application $application)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si présent
            if ($application->logo) {
                Storage::delete($application->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $application->update($validated);

        return redirect()->route('applications.index')->with('success', 'Application mise à jour avec succès.');
    }

    // Supprimer une application
    public function destroy(Application $application)
    {
        // Supprimer l'logo de l'application si nécessaire
        if ($application->logo) {
            Storage::delete($application->logo);
        }

        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application supprimée avec succès.');
    }
}
