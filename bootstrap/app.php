<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'prefs' => \App\Http\Middleware\HandleUserPreferences::class,
            'api.token' => \App\Http\Middleware\ApiTokenMiddleware::class,
            'honeypot' => \App\Http\Middleware\HoneypotTrap::class,
            'internal' => \App\Http\Middleware\RestrictClientAccess::class,
        ]);

        $middleware->append(\App\Http\Middleware\HoneypotTrap::class);

        // user_prefs is intentionally left inside the encrypted-cookie set. The prefs
        // middleware unserializes it, so its integrity has to depend on APP_KEY; that is
        // what ties the diagnostic file read to the deserialization step in the lab.
        // Adding an encryptCookies() exception here would hand the sink an unauthenticated
        // attacker-controlled string and quietly collapse those two steps into one.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();