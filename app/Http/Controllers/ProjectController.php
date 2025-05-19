<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Mail\ProjectAssignedMail;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $authorizedRoles = ['Chef de projet', 'direction', 'Directeur', 'RH'];

        $canSeeAll = $user->hasAnyRole($authorizedRoles);

        // Vérification du rôle et récupération des projets en fonction de celui-ci
        if ($canSeeAll) {
            $projects = Project::with('tasks', 'members')->paginate(10); // Récupère les projets avec les tâches et les membres
        } else {
            // Récupère seulement les projets où l'utilisateur est membre
            $projects = Project::whereHas('members', function($query) {
                $query->where('user_id', Auth::id());
            })->with('tasks','members')->paginate(10); // Pagination ajoutée pour garder la même logique
        }

        return inertia('Projects/index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
        {

            $users = User::all(); // Récupérer tous les utilisateurs
            return inertia('Projects/create', [
                'users' => $users
            ]);
        }

        /**
        * Store a newly created resource in storage.
        */

        public function store(Request $request)
        {
            // Validation des données
            $validated = $request->validate([
                'title' => 'required|string|unique:projects,title|max:255',
                'description' => 'nullable|string|max:1000',
                'status' => 'required|in:planned,inprogress,completed,on_hold',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'nature' => 'required|in:interne,externe',
                'costumer_name' => 'nullable|string|max:255',
                'priority' => 'required|in:low,medium,high',
                'type' => 'required|in:development,marketing,design,research,other',
                'members' => 'required|array',
                'members.*.user_id' => 'required|exists:users,id',
                'members.*.role' => 'required|string|max:255',
            ]);

            // Création du projet
            $project = Project::create($validated);

            // Attacher les membres et envoyer les emails
            foreach ($request->members as $member) {
                $project->members()->attach($member['user_id'], ['role' => $member['role']]);

                $user = User::find($member['user_id']);
                if ($user && $user->email) {
                    $data = [
                        'objet' => 'Vous avez été assigné au projet : ' . $project->title,
                        'message' => "Bonjour {$user->first_name},\n\nVous avez été assigné au projet **{$project->title}** en tant que **{$member['role']}**.\n\nMerci de vous connecter à la plateforme pour consulter les détails.",
                        'url' => url("/projects/{$project->id}")
                    ];
                    Mail::to($user->email)->send(new ProjectAssignedMail($data));
                }
            }

            return redirect()->back()->with('success', 'Projet créé et notifications envoyées avec succès !');
        }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            // Chef de projet peut voir tous les projets
            $project = Project::where('id', $id)->with('members', 'tasks')->first();
            $tasks = Task::where('project_id', $id)->with('users')->get();


        return inertia('Projects/show', [
            'project' => $project,
            'tasks' => $tasks,
            'users' => User::all(),
            'compactView' => request()->has('compact'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $project = Project::findOrFail($id)->load('members');
        $users = User::all();
        return inertia('Projects/edit', [
            'project' => $project,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|unique:projects,title,' . $id,
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:planned,inprogress,completed,on_hold',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'nature' => 'required|in:interne,externe',
            'costumer_name' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'type' => 'required|in:development,marketing,design,research,other',
            'members' => 'required|array',
            'members.*.user_id' => 'required|exists:users,id',
            'members.*.role' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($id);
        $project->members()->detach();
        $project->update(collect($validated)->except('members')->toArray());

        // Attacher les nouveaux membres
        foreach ($validated['members'] as $member) {
            $project->members()->attach($member['user_id'], ['role' => $member['role']]);
        }

        return redirect()->back()->with('success', 'Projet mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès !');
    }

    // Méthodes pour assigner et retirer des membres
    public function assignUsers(Request $request, $projectId)
    {
        $validated = $request->validate([
            'members' => 'required|array|min:1',
            'members.*.id' => 'required|exists:users,id',
            'members.*.role' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($projectId);
        foreach ($validated['members'] as $member) {
            $project->members()->syncWithoutDetaching([
                $member['id'] => ['role' => $member['role']]
            ]);
        }

        return redirect()->back()->with('success', 'Membres assignés avec succès !');
    }

    public function removeUser(Request $request, $projectId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($projectId);
        $project->members()->detach($validated['user_id']);

        return redirect()->back()->with('success', 'Utilisateur retiré avec succès !');
    }
}
