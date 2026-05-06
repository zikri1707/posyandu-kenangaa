<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * Check if the user account is active and not blocked.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is inactive
        if (! $user->isActive()) {
            Auth::logout();

            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Check if user account is blocked
        if ($user->isBlocked()) {
            Auth::logout();
            $remainingMinutes = $user->getRemainingBlockMinutes();

            return redirect()->route('login')
                ->with('error', "Akun sementara dikunci. Coba lagi dalam {$remainingMinutes} menit.");
        }

        // If block_expires has passed, clear it
        $user->unlockIfExpired();

        return $next($request);
    }
}
