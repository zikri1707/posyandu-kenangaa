<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            // Log unauthorized access attempt
            if ($request->user()) {
                $activityLogService = app(ActivityLogService::class);
                $activityLogService->log(
                    'unauthorized_access',
                    'Percobaan akses tidak sah ke halaman yang memerlukan role: '.implode(', ', $roles)." (URL: {$request->fullUrl()})",
                    null,
                    null,
                    null,
                    ['required_roles' => $roles, 'user_role' => $request->user()->role, 'url' => $request->fullUrl()]
                );
            }

            abort(403, 'Unauthorized action. Halaman ini butuh role: '.implode(', ', $roles));
        }

        return $next($request);
    }
}
