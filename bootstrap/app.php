<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php', // Web routes for regular web pages
        api: __DIR__.'/../routes/api.php', // API routes (dedicated for API endpoints)
        commands: __DIR__.'/../routes/console.php', // Routes for console commands (artisan commands)
        health: '/up', // Path for the health check endpoint (useful for monitoring)
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Here you can add middleware for the routes if necessary
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle exceptions globally
    })
    ->create();
