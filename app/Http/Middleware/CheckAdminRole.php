<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized access');
        }

        // Super Admin has access to everything
        if ($admin->hasRole('Super Admin')) {
            return $next($request);
        }

        // Check specific role if provided
        if ($role && !$admin->hasRole($role)) {
            abort(403, 'Insufficient permissions');
        }

        return $next($request);
    }
}