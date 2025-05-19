<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Récupération de l'utilisateur connecté
    $user = Auth::user();
        $authorizedRoles = ['Chef de projet', 'direction', 'Directeur', 'RH'];

        $canSeeAll = $user->hasAnyRole($authorizedRoles);

    // Si l'utilisateur est un Chef de projet, on récupère toutes les tâches et les projets
    if ($canSeeAll) {
        // Récupération de toutes les tâches, projets et utilisateurs
        $tasks = Task::with(['project', 'users'])->get();
        $projects = Project::all();
        $users = User::all();
    }
    // Si l'utilisateur a un autre rôle, par exemple un membre, on filtre les tâches liées à lui
    else {
        // Récupération des tâches auxquelles l'utilisateur est assigné
        $tasks = Task::with(['project', 'users'])
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        // Récupération des projets auxquels l'utilisateur appartient
        $projects = Project::whereHas('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Récupération de l'utilisateur connecté
        $users = User::where('id', $user->id)->get();
    }

    // Retourner la vue avec les données filtrées
    return inertia('Tasks/index', [
        'tasks' => $tasks,
        'projects' => $projects,
        'users' => $users
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create(int $projectId)
    {
        $users = User::all();
        $project = Project::find($projectId)->load('members');

        return inertia('Tasks/create', [
            'project'=> $project,
            'users'=> $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validated  = $request->validate([
            'title' => 'string|max:255',
            'description'=> 'string|nullable|max:1000',
            'priority' => 'string|required|in:high,low,medium',
            'status' => 'string|required|in:todo,inprogress,done',
            'step_project' => 'string|required',
            'delais' => 'date|required',
            'project_id' => 'integer|required|exists:projects,id',
            'assigned_users' => 'array|required|exists:users,id'
        ]);

        $project = Project::find($validated['project_id']);


        if( $validated['delais'] > $project->end_date) {
            return redirect()->back()->with('error', 'La date de la tâche ne doit pas dépasser la date de fin du projet.');
        }


        $task = Task::create($validated);

        // Attach users to the task
        $task->users()->attach($validated['assigned_users']);


        $task->save();
        // Redirect to the task index page with a success message
        return redirect()->back()->with('success', 'Tâche créée avec succès !');

    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return inertia('Tasks/edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:tasks,id',
                'status' => 'required|in:todo,inprogress,done,on_hold'
            ]);

            $task = Task::find($validated['id']);
            $task->status = $validated['status'];
            $task->done_at = $validated['status'] === 'done' ? now() : null;
            $task->save();

            $project = Project::with('tasks')->find($task->project_id);

            $totalTasks = $project->tasks->count();
            $doneCount = $project->tasks->where('status', 'done')->count();
            $onHoldCount = $project->tasks->where('status', 'on_hold')->count();

            if ($doneCount === $totalTasks) {
                $project->status = 'completed';
                $project->completed_at = now();
            } elseif ($onHoldCount === $totalTasks) {
                $project->status = 'on_hold';
            } else {
                $project->status = 'inprogress';
            }

            $project->save();

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du statut de la tâche.');
        }
    }

}
