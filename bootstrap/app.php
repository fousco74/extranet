<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // Configuration des routes
    ->withRouting(
        web: __DIR__ . '/../routes/web.php', // Routes web
        commands: __DIR__ . '/../routes/console.php', // Commandes artisan
        health: '/up', // Endpoint pour vérifier l'état de santé de l'application
    )
    // Configuration des middlewares
    ->withMiddleware(function (Middleware $middleware) {
        // Ajout du middleware spécifique pour Inertia
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        
        // Définition des alias pour les middlewares Spatie Permission
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class, // Vérification des rôles
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class, // Vérification des permissions
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class, // Vérification des rôles ou permissions
        ]);
    })
    // Gestion des exceptions
    ->withExceptions(function (Exceptions $exceptions) {
        // Ajouter des personnalisations ici si nécessaire
    })
    ->create();
