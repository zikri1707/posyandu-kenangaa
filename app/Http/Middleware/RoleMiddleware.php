<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * The activity log service instance.
     */
    protected ActivityLogService $activityLogService;

    /**
     * Create a new middleware instance.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            $this->logUnauthorizedAccess($request, $roles);

            abort(403, 'Akses ditolak. Halaman ini memerlukan hak akses: '.implode(', ', $roles));
        }

        return $next($request);
    }

    /**
     * Log unauthorized access attempt.
     */
    private function logUnauthorizedAccess(Request $request, array $roles): void
    {
        if (! $request->user()) {
            return;
        }

        $this->activityLogService->log(
            'unauthorized_access',
            'Percobaan akses tidak sah ke URL: '.$request->fullUrl().' (Role yang dibutuhkan: '.implode(', ', $roles).')',
            null,
            null,
            null,
            [
                'required_roles' => $roles,
                'user_role' => $request->user()->role,
                'url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]
        );
    }
}
