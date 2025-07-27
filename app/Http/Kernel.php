<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // Global middleware for security, logging, etc.
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\Authenticate::class, // User authentication
            \App\Http\Middleware\CheckUserStatus::class, // User account status check
            \App\Http\Middleware\VerifyEmailMiddleware::class, // Verifying email address for users
            \Illuminate\Session\Middleware\StartSession::class, // Start session for user
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Share error messages with session
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Bind route model data
        ],

        'api' => [
            'throttle:api', // API rate limiting
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Route model binding for APIs
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Middleware for authentication and roles
        'auth' => \App\Http\Middleware\Authenticate::class, // Authentication middleware
        'user' => \App\Http\Middleware\UserMiddleware::class, // Middleware to ensure user access
        'medical' => \App\Http\Middleware\MedicalMiddleware::class, // Middleware for medical staff
        'superadmin' => \App\Http\Middleware\SuperadminMiddleware::class, // Middleware for superadmins
        'coordinator' => \App\Http\Middleware\CoordinatorMiddleware::class, // Middleware for coordinators
        'staff' => \App\Http\Middleware\StaffMiddleware::class, // Middleware for staff role
        'patient' => \App\Http\Middleware\PatientMiddleware::class, // Middleware for patient role
        'partner' => \App\Http\Middleware\PartnerMiddleware::class, // Middleware for partner role

        // Middleware for account verification and user status
        'check.user.status' => \App\Http\Middleware\CheckUserStatus::class, // Middleware for checking account status
        'verified' => \App\Http\Middleware\VerifyEmailMiddleware::class, // Middleware to ensure email verification

        // Middleware for role-based access control (RBAC)
        'role' => \App\Http\Middleware\RoleMiddleware::class, // Middleware to enforce role-based access

        // Middleware for request validation
        'user.request' => \App\Http\Requests\UserRequest::class, // User data validation
        'patient.request' => \App\Http\Requests\PatientRequest::class, // Patient data validation
        'schedule.request' => \App\Http\Requests\ScheduleRequest::class, // Schedule data validation
        'gallery.request' => \App\Http\Requests\GalleryRequest::class, // Gallery data validation
        'article.request' => \App\Http\Requests\ArticleRequest::class, // Article data validation
        'medical_record.request' => \App\Http\Requests\MedicalRecordRequest::class, // Medical record validation
        'posyandu.request' => \App\Http\Requests\PosyanduRequest::class, // Posyandu data validation
        'pedukuhan.request' => \App\Http\Requests\PedukuhanRequest::class, // Pedukuhan data validation
    ];
}
