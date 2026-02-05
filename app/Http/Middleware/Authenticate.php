<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuthenticate;

class Authenticate extends MiddlewareAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
//    public function handle(Request $request, Closure $next): Response
//    {
//        return $next($request);
//    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            throw new AuthenticationException('Unauthenticated.', $guards);
        }

        foreach ($guards as $guard) {
            switch ($guard) {
                case 'admin':
                    throw new AuthenticationException(
                        'Unauthenticated.', $guards, route('dashboard.login')
                    );
                default:
                    throw new AuthenticationException(
                        'Unauthenticated.', $guards, route('login')
                    );
            }
        }
    }
}
