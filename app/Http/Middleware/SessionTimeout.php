<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Session timeout duration in seconds (15 minutes)
     */
    protected const TIMEOUT_DURATION = 900;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check timeout for authenticated users
        if (Auth::check()) {
            $lastActivity = session('last_activity');

            // Check if last_activity exists and if session has timed out
            if ($lastActivity && (time() - $lastActivity) > self::TIMEOUT_DURATION) {
                // Log the auto logout activity
                $activityLogService = app(ActivityLogService::class);
                $activityLogService->log(
                    'auto_logout',
                    'Pengguna logout otomatis karena tidak aktif selama 15 menit',
                    Auth::id(),
                    'User'
                );

                // Logout the user
                Auth::logout();

                // Invalidate the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect to login with message
                return redirect()->route('login')
                    ->with('message', 'Sesi Anda telah berakhir karena tidak aktif.');
            }

            // Update last activity timestamp for valid requests
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}
