<?php

namespace App\Http\Middleware;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Permission;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        Inertia::share('appName', config('app.name'));
        return array_merge(parent::share($request), [
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success')
            ],
            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'first_name', 'last_name', 'team', 'poste', 'email', 'phone_number', 'profile_link')
                : null,
            'auth.user.notifications' => fn () => $request->user() ? $request->user()->notifications->take(5) : [],
            'auth.user.unreadNotifications' => fn () => $request->user() ? $request->user()->unreadNotifications : [], 
            'auth.user.permissions' => fn () => $request->user() ? $request->user()->getAllPermissions()->pluck('name') : [],
            'auth.user.permissionsVoir' => fn () => $request->user()
            ? $request->user()
                ->getAllPermissions()
                ->filter(fn($permission) => stripos($permission->name, 'voir') !== false)
                ->pluck('name')
            : [],
            'routeName' => request()->route()->getName(), 
            'routePath' => request()->route()->uri(),

            'app' => [
                'name' => config('app.name'),
                'locale' => app()->getLocale(),
            ],
        ]);
    }
}
