<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OneDriveLinkController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamMemberController;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'role:admin'])->group(function () {


//dashboard
Route::get('/admin/dashboard', [DashboardAnalyticsController::class, 'index'])->name('dashboard.analytics');


//notification
Route::get('/users/notification',[UserController::class, 'notification'])->name('vue.notification');
Route::post('/users/notification',[UserController::class, 'sendNotification'])->name('send.notification');



// Ressources pour chaque modèle
Route::resource('one-drive-links', OneDriveLinkController::class);
Route::resource('folders', FolderController::class);
Route::resource('files', FileController::class);

//users
Route::get('/users/{user}/onedrive', [UserController::class, 'editOneDriveLinks'])->name('users.onedrive.edit');
Route::put('/users/{user}/onedrive', [UserController::class, 'updateOneDriveLinks'])->name('users.onedrive.update');
Route::get('users/{user}/roles', [UserController::class, 'showRoles'])->name('user.roles');
Route::put('users/{id}/roles', [UserController::class, 'updateRoles'])->name('user.updateRoles');
Route::resource('users', UserController::class);



//roles
Route::resource('roles', RoleController::class);
Route::get('roles/{id}/permissions', [RoleController::class, 'showPermissions'])->name('role.permissions');
Route::put('roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('role.updatePermissions');

//permissions
route::resource('permissions', PermissionController::class);

});



Route::middleware(['auth'])->group(function () {






//Frontend 
Route::post('/logout',[UserController::class,'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/reglementInterieur',[PageController::class, 'reglement'])->name('reglement');
Route::get('/Apps', [PageController::class, 'applications'])->name('our.app');
Route::get('/suggestion',[PageController::class, 'suggestion'])->name('suggestion');
Route::get('/oneDriveLinks',[PageController::class, 'oneDriveLinks'])->name('oneDriveLinks');
Route::get('/organigramme',[PageController::class, 'organigramme'])->name('organigramme');
Route::get('/knowledge',[PageController::class, 'knowledges'])->name('knowledges');
Route::get('/folder/{folder}/files',[PageController::class, 'folderFiles'])->name('folder.files');
Route::get('/team',[TeamMemberController::class, 'membersList'])->name('membersList');
Route::post('/suggestions/send', [UserController::class, 'send'])->name('suggestions.send');
route::resource('reservations', ReservationController::class);
Route::resource('applications', ApplicationController::class);
Route::get('/reservation/date', [ReservationController::class, 'dateReservation'])->name('reservation.date');
Route::get('/notifications', function () {
    
    return inertia('Notifications/index');
})->name('notification.index');

Route::get('/notifications/read/{id}', function (string $id) {
    $notification = Auth::user()->notifications->find($id);
    $notifications = Auth::user()->notifications;
    $notification->markAsRead();
     
    return inertia('Notifications/index', ['selectedNotificationId' => $notification->id, 'notifications' => $notifications]);
})->name('notifications.read');

Route::get('/', function (Request $request) {

    $city = $request->input('city', "abidjan");
    $weatherApiKey = env('WEATHER_API_KEY'); // La clé API
    $weatherBaseUrl = env('WEATHER_BASE_URL'); // L'URL de base de l'API
        
    // Effectuer la requête API pour récupérer les données météo
    $response = Http::get($weatherBaseUrl, [
        "q" => $city,
        "appid" => $weatherApiKey, // Utiliser la clé API ici
        "lang" => "fr",
        "units" => "metric"
    ]);

    $weatherData = $response->json();

    $weatherTime = strtolower($weatherData["weather"][0]["main"]);
    
    return Inertia::render('home', ["weatherData","weatherTime" => $weatherTime , $weatherData, "city" => $city]);
})->name('home');



});


// Authentification
Route::get('/login',[UserController::class,'login'])->name('login');
Route::post('/login',[UserController::class,'authenticate'])->name('authenticate');
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});
