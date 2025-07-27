<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
