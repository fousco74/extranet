<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\OneDriveLink;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;


class DashboardAnalyticsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|direction', only: ['index']),
        ];
    }
    public function index()
    {
        // Récupérer les totaux pour chaque entité
        $fileCount = File::count();
        $folderCount = Folder::count();
        $oneDriveLinkCount = OneDriveLink::count();
        $userCount = User::count();


        // Retourner la vue avec les données
        return inertia('dashboard/analytics', compact('fileCount', 'folderCount', 'oneDriveLinkCount', 'userCount'));
    }

    public function show()
{
    $user = Auth::user();

    // Rôles autorisés à tout voir
    $authorizedRoles = ['Chef de projet', 'direction', 'Directeur', 'RH'];

    $canSeeAll = $user->hasAnyRole($authorizedRoles);



    // Base query des projets
    $projectsQuery = Project::withCount(['members', 'tasks']);

    // Si l'utilisateur ne peut pas tout voir, on filtre par ses projets
    if (!$canSeeAll) {
        $projectsQuery->whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    // Récupération des projets avec les tâches
    $projects = $projectsQuery->with(['tasks' => function ($query) {
        $query->select('project_id', 'status');
    }])->get();

    // Statistiques sur les projets
    $totalProjects = $projects->count();
    $completedProjects = $projects->where('status', 'completed')->count();
    $inprogressProjects = $projects->where('status', 'inprogress')->count();

    // Statistiques sur les tâches
    if ($canSeeAll) {
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'done')->count();
        $inprogressTasks = Task::where('status', 'inprogress')->count();
    } else {
        $totalTasks = Task::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        $completedTasks = Task::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'done')->count();

        $inprogressTasks = Task::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'inprogress')->count();
    }

    // Statistiques utilisateurs (uniquement pour ceux qui peuvent tout voir)
    $totalUsers = $canSeeAll ? User::count() : null;
    $usersHasProjects = $canSeeAll ? User::has('projects')->count() : null;

    // Moyennes et taux
    $avgTasksPerProject = $totalProjects > 0 ? $totalTasks / $totalProjects : 0;
    $avgMembersPerProject = $totalProjects > 0 ? $projects->avg('members_count') : 0;
    $taskCompletionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

    // Graphiques
    $charts = [
        'projectStatus' => [
            'labels' => ['Terminés', 'En cours'],
            'data' => [$completedProjects, $inprogressProjects],
            'colors' => ['#10B981', '#3B82F6']
        ],
        'taskStatus' => [
            'labels' => ['Terminées', 'En cours'],
            'data' => [$completedTasks, $inprogressTasks],
            'colors' => ['#10B981', '#3B82F6']
        ],
        'taskProgressPerProject' => [
            'labels' => $projects->pluck('title'),
            'data' => $projects->map(function ($project) {
                $done = $project->tasks->where('status', 'done')->count();
                return $project->tasks_count > 0 ? round(($done / $project->tasks_count) * 100, 1) : 0;
            }),
            'colors' => ['#3B82F6']
        ],
        'usersPerProject' => [
            'labels' => $projects->pluck('title'),
            'data' => $projects->pluck('members_count'),
            'colors' => ['#10B981', '#3B82F6', '#3B82F6', '#10B981']
        ]
    ];

    return inertia('stats/index', [
        'stats' => [
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'totalUsers' => $totalUsers,
            'usersHasProjects' => $usersHasProjects,
            'avgTasksPerProject' => round($avgTasksPerProject, 1),
            'avgMembersPerProject' => round($avgMembersPerProject, 1),
            'taskCompletionRate' => round($taskCompletionRate, 1),
        ],
        'charts' => $charts,
        'canSeeAll' => $canSeeAll,
    ]);
}




public function showUserStats($userId)
{
    // Récupération de l'utilisateur par ID
    $user = User::findOrFail($userId);

     // Rôles autorisés à tout voir
   

    // Récupération des projets de l'utilisateur avec les tâches et les membres
    $projects = Project::withCount(['members', 'tasks'])
        ->with(['tasks' => function ($query) {
            $query->select('project_id', 'status');
        }])
        ->whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();

    // Calculs des projets
    $totalProjects = $projects->count();
    $completedProjects = $projects->where('status', 'completed')->count();
    $inprogressProjects = $projects->where('status', 'inprogress')->count();

    // Calculs des tâches en fonction des projets de l'utilisateur
    $totalTasks = Task::whereIn('project_id', $projects->pluck('id'))
        ->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->count();

    $completedTasks = Task::whereIn('project_id', $projects->pluck('id'))
        ->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'done')
        ->count();

    $inprogressTasks = Task::whereIn('project_id', $projects->pluck('id'))
        ->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'inprogress')
        ->count();

    // Statistiques des utilisateurs
    $totalUsers = User::count();
    $usersHasProjects = User::has('projects')->count();

    // Calculs sécurisés contre la division par zéro
    $avgTasksPerProject = $totalProjects > 0 ? $totalTasks / $totalProjects : 0;
    $avgMembersPerProject = $totalProjects > 0 ? $projects->avg('members_count') : 0;
    $taskCompletionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

    // Préparation des données pour les graphiques
    $charts = [
        'projectStatus' => [
            'labels' => ['Terminés', 'En cours'],
            'data' => [$completedProjects, $inprogressProjects],
            'colors' => ['#10B981', '#3B82F6']
        ],
        'taskStatus' => [
            'labels' => ['Terminées', 'En cours'],
            'data' => [$completedTasks, $inprogressTasks],
            'colors' => ['#10B981', '#3B82F6']
        ],
        'taskProgressPerProject' => [
            'labels' => $projects->pluck('title'),
            'data' => $projects->map(function ($project) {
                $done = $project->tasks->where('status', 'done')->count();
                return $project->tasks_count > 0 ? round(($done / $project->tasks_count) * 100, 1) : 0;
            }),
            'colors' => ['#3B82F6']
        ],
        'usersPerProject' => [
            'labels' => $projects->pluck('title'),
            'data' => $projects->pluck('members_count'),
            'colors' => ['#10B981', '#3B82F6', '#3B82F6', '#10B981']
        ]
    ];

    return inertia('stats/userStats', [
        'user' => $user,
        'stats' => [
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'totalUsers' => $totalUsers,
            'usersHasProjects' => $usersHasProjects,
            'avgTasksPerProject' => round($avgTasksPerProject, 1),
            'avgMembersPerProject' => round($avgMembersPerProject, 1),
            'taskCompletionRate' => round($taskCompletionRate, 1),
        ],
        'charts' => $charts
    ]);
}


}
