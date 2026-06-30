<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // API routes (dedicated for API endpoints)
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        // Tambahkan SecurityHeaders sebagai global middleware untuk semua request web
        $middleware->web(append: [
            SecurityHeaders::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
            'posyandu.scope' => \App\Http\Middleware\PosyanduScopeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
