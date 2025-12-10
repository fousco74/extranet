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
    $user = Auth::user();
        $authorizedRoles = ['Chef de projet', 'direction', 'Directeur', 'RH'];

        $canSeeAll = $user->hasAnyRole($authorizedRoles);

    if ($canSeeAll) {
        $tasks = Task::with(['project', 'users'])->get();
        $projects = Project::all();
        $users = User::all();
    }
    // Si l'utilisateur a un autre rôle, par exemple un membre, on filtre les tâches liées à lui
    else {
        $tasks = Task::with(['project', 'users'])
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        $projects = Project::whereHas('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $users = User::where('id', $user->id)->get();
    }

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
    $validated = $request->validate([
        'title' => 'required|string|max:255|unique:tasks,title',
        'description' => 'nullable|string|max:1000',
        'priority' => 'required|string|in:high,low,medium',
        'status' => 'required|string|in:todo,inprogress,done',
        'step_project' => 'required|string',
        'delais' => 'required|date',
        'project_id' => 'required|integer|exists:projects,id',
        'assigned_users' => 'required|array',
        'assigned_users.*' => 'integer|exists:users,id', // Validation de chaque user ID
    ]);

    $project = Project::find($validated['project_id']);

    if ($validated['delais'] > $project->end_date) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'La date de la tâche ne doit pas dépasser la date de fin du projet.');
    }

    $task = Task::create([
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'priority' => $validated['priority'],
        'status' => $validated['status'],
        'step_project' => $validated['step_project'],
        'delais' => $validated['delais'],
        'project_id' => $validated['project_id'],
    ]);

    $task->users()->attach($validated['assigned_users']);


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
    public function update(Request $request, Task $task)
{
    // Validation
    $validated = $request->validate([
        'title' => [
            'string',
            'max:255',
            Rule::unique('tasks', 'title')->ignore($task->id), // Ignore current task
        ],
        'description' => 'string|nullable|max:1000',
        'priority' => 'string|required|in:high,low,medium',
        'status' => 'string|required|in:todo,inprogress,done',
        'step_project' => 'string|required',
        'delais' => 'date|required',
        'project_id' => 'integer|required|exists:projects,id',
        'assigned_users' => 'array|required',
        'assigned_users.*' => 'integer|exists:users,id', // Validate each user ID
    ]);

    // Check if deadline exceeds project end date
    $project = Project::find($validated['project_id']);
    if ($validated['delais'] > $project->end_date) {
        return redirect()->back()->with('error', 'La date de la tâche ne doit pas dépasser la date de fin du projet.');
    }

    // Update the task
    $task->update($validated);

    // Sync users (replace old relationships with new ones)
    $task->users()->sync($validated['assigned_users']);

    return redirect()->back()->with('success', 'Tâche mise à jour avec succès !');
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
