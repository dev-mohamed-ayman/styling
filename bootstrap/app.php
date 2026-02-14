<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // User Routes
            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api/user')
                ->group(base_path('routes/user.php'));

            // Dashboard routes
            \Illuminate\Support\Facades\Route::middleware('web')
                ->prefix('dashboard')
                ->name('dashboard.')
                ->namespace('App\\Http\\Controllers\\Dashboard')
                ->group(base_path('routes/dashboard.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            \App\Http\Middleware\ForceJson::class,
        ]);
        $middleware->alias([
            'guest' => RedirectIfAuthenticated::class,
            'auth' => Authenticate::class,
            'role' => \App\Http\Middleware\CheckAdminRole::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $apiHandler = new \App\Exceptions\ApiExceptionHandler();

        $exceptions->renderable(function (\Throwable $e) use ($apiHandler) {
            $request = request();
            if ($apiHandler->shouldHandle($request)) {
                return $apiHandler->handle($e, $request);
            }
        });

        // Handle authorization exceptions for web routes
        $exceptions->renderable(function (AuthorizationException $e, $request) {
            if (!$request->expectsJson()) {
                return redirect()->back()->with('error', __('dashboard.You do not have permission to access this resource'));
            }
        });
    })->create();
