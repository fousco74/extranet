<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\OneDriveLink;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
}
