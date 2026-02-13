<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super Admin can do everything
        Gate::before(function ($user, $ability) {
            // Always grant admin@admin.com all permissions (first admin)
            if ($user->email === 'admin@admin.com') {
                return true;
            }

            // Also check via role
            if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
                return true;
            }
        });
    }
}
