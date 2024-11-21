<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',    // Path to web routes file
        commands: __DIR__.'/../routes/console.php',    // Path to console commands routes
        health: '/up',    // Health check route
    )

    // Middleware aliases
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,    // Alias for admin middleware
            'patient' => \App\Http\Middleware\Patient::class,    // Alias for patient middleware
            'doctor' => \App\Http\Middleware\Doctor::class,    // Alias for doctor middleware
        ]);

    })

    // Appending middleware to the web middleware stack
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,    // Handle Inertia.js requests
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,    // Preload assets
        ]);

    })

    // Exception handling configuration (can be customized as needed)
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling logic (optional)
    })

    ->create();
