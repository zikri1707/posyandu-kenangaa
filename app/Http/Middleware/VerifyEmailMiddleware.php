<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyEmailMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah email pengguna sudah diverifikasi
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
