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
    // 1. Alias personalizados (aquí van TODOS tus alias, uno tras otro)
    $middleware->alias([
        'checkrole' => \App\Http\Middleware\CheckRole::class,
    ]);
    // ... agrega más alias si necesitas otros

    // 2. Middlewares web (los que sí se ejecutan)
    $middleware->web(append: [
        \App\Http\Middleware\HandleInertiaRequests::class,
        \App\Http\Middleware\ShareMenu::class,
        \App\Http\Middleware\ContadorVisitas::class,
        \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        // Puedes agregar más middlewares globales aquí si necesitas
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
