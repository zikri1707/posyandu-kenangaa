<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika user tidak aktif
        if ($user && !$user->is_active) {
            Log::warning("Inactive user ID {$user->id} attempted access.");
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account is deactivated. Please contact administrator.');
        }

        // Jika email belum diverifikasi
        if ($user && !$user->verified_email && !$request->is('email/*', 'logout')) {
            Log::info("User ID {$user->id} attempted access without verified email.");
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address.');
        }

        // Jika user diblokir karena terlalu banyak attempt login
        if ($user && $user->block_expires && $user->block_expires > now()) {
            $minutes = now()->diffInMinutes($user->block_expires);
            Log::warning("Blocked user ID {$user->id} attempted access. Block expires in $minutes minutes.");
            Auth::logout();
            return redirect()->route('login')
                ->with('error', "Your account is temporarily blocked. Try again in $minutes minutes.");
        }

        return $next($request);
    }
}
