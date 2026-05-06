<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PosyanduScopeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Set the current posyandu_id in the application container
     * for use by global scopes and query filtering.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set the current posyandu_id in the application container
        // This will be used by global scopes to automatically filter queries
        if (Auth::check()) {
            app()->instance('current_posyandu_id', Auth::user()?->posyandu_id);
        }

        return $next($request);
    }
}
