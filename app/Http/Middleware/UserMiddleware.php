<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole($role)) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
