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
    ->withMiddleware(function (Middleware $middleware): void {

        // ── Enregistrement des middlewares avec alias ──────────────
        $middleware->alias([
            'role'              => \App\Http\Middleware\CheckRole::class,
            'level'             => \App\Http\Middleware\CheckLevel::class,
            'track.login'       => \App\Http\Middleware\TrackLogin::class,
            'track.device.view' => \App\Http\Middleware\TrackDeviceView::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();