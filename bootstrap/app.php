<?php

use App\Http\Middleware\EnsureUserHasImobiliaria;
use App\Http\Middleware\UserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => UserIsAdmin::class,
            'has-imobiliaria' => EnsureUserHasImobiliaria::class
        ]);
        $middleware->redirectUsersTo('/home');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
