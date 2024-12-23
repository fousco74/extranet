<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Folder;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\OneDriveLink;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class PageController extends Controller
{
   public function index(Request $request)
    {
        // Récupérer la ville depuis la requête ou "Abidjan" par défaut
        $city = $request->input('city', "abidjan");
        $weatherApiKey = env('WEATHER_API_KEY');
        $weatherBaseUrl = env('WEATHER_BASE_URL');

        // Créer une instance du client Guzzle
        $client = new Client();

        // Construire l'URL de l'API
        $apiUrl = "{$weatherBaseUrl}?q={$city}&appid={$weatherApiKey}&lang=fr&units=metric";

        try {
            // Effectuer la requête GET avec Guzzle
            $response = $client->get($apiUrl);

            // Décoder la réponse JSON
            $weatherData = json_decode($response->getBody()->getContents(), true);

            // Vérifier si la réponse contient des données météo
            $weatherTime = isset($weatherData["weather"][0]["main"]) ? strtolower($weatherData["weather"][0]["main"]) : 'unknown';

        } catch (\Exception $e) {
            // En cas d'erreur, retournez un message d'erreur approprié
            $weatherData = null;
            $weatherTime = 'unknown';
        }

        // Retourner la vue avec les données météo
        return inertia('home', [
            'weatherData' => $weatherData,
            'weatherTime' => $weatherTime,
            'city' => $city
        ]);
    }

   public function knowledges(Request $request){

      $folders = Folder::when($request->search, function ($query, $search) {
         $query->where('name', 'like', "%{$search}%");
     })->with('files')
       ->paginate(10); // 10 résultats par page
     


    return inertia('frontend/knowledge/knowledge', ['folders' => $folders]);
   }

   public function folderFiles(Folder $folder, Request $request){

      $folderFiles = $folder->files()->when($request->search, function ($query, $search) {
         $query->where('name', 'like', "%{$search}%");
     })->paginate(10);
     


      return inertia('frontend/knowledge/folderFiles', ['files' => $folderFiles, 'folderId' => $folder->id]);
   }

   public function organigramme(){

      return inertia('frontend/organigramme/organigramme');
   }

   public function oneDriveLinks(Request $request){

      $oneDriveLinks = Auth::user()->oneDriveLinks()->when($request->search, function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
      })
      ->paginate(10);
  
     
      return inertia('frontend/oneDriveLinks/oneDriveLinks', ['oneDriveLinks' => $oneDriveLinks]);
   }
   
   public function suggestion(){
      
      return inertia('frontend/suggestion/suggestion');
   }

   public function reglement(){
      
      return inertia('frontend/reglement/reglement');
   }

   public function applications(Request $request){

      $apps = Application::when($request->search, function ($query) use ($request) {
        $query->where('name', 'like', "%{$request->search}%");
    })
    ->paginate(10);

      return inertia('frontend/App/App', ["apps" => $apps]);
   }
   
}
