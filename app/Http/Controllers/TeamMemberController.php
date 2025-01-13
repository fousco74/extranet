<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
   

   

    public function membersList(Request $request)
    {  
        // Récupération du paramètre de requête 'equipe'
        $equipe = $request->input('equipe', 'interne');
        
        // Définir les requêtes de base pour les équipes interne et externe
        if ($equipe === 'interne') {
            $membersFird = User::where('team', 'interne')
                ->whereBetween('ordre_team', [1, 3])
                ->orderBy('ordre_team', 'asc')
                ->get();
    
            $memberFour = User::where('team', 'interne')
                ->where('ordre_team', 4)
                ->orderBy('ordre_team', 'asc')
                ->first();
    
            $membersRest = User::where('team', 'interne')
                ->where('ordre_team', '>=', 5)
                ->orderBy('ordre_team', 'asc')
                ->get();
    
            return inertia('frontend/trombinoscope/trombinoscope', [
                'membersFird' => $membersFird,
                'memberFour' => $memberFour,
                'membersRest' => $membersRest,
                'equipe' => $equipe
            ]);
        } elseif ($equipe === 'externe') {
            $membersFird = User::where('team', 'interne')
                ->whereBetween('ordre_team', [1, 3])
                ->orderBy('ordre_team', 'asc')
                ->get();
    
            $memberFour = User::where('team', 'interne')
                ->where('ordre_team', 4)
                ->orderBy('ordre_team', 'asc')
                ->get()[0];
    
            $membersRest = User::where('team', 'externe')
                ->orderBy('ordre_team', 'asc')
                ->get();


    
            return inertia('frontend/trombinoscope/trombinoscope', [
                'membersFird' => $membersFird,
                'memberFour' => $memberFour,
                'membersRest' => $membersRest,
                'equipe' => $equipe
            ]);
        } else {
            // Logique pour afficher tous les membres si aucune équipe n'est sélectionnée
            $membersFird = User::whereBetween('ordre_team', [1, 3])
                ->orderBy('ordre_team', 'asc')
                ->get();
    
            $memberFour = User::where('ordre_team', 4)
                ->orderBy('ordre_team', 'asc')
                ->first();
    
            $membersRest = User::where('ordre_team', '>=', 5)
                ->orderBy('ordre_team', 'asc')
                ->get();
    
            return inertia('frontend/trombinoscope/trombinoscope', [
                'membersFird' => $membersFird,
                'memberFour' => $memberFour,
                'membersRest' => $membersRest,
                'equipe' => $equipe
            ]);
        }
    }
    
}
