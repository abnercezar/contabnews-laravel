<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        // NÃO adicionar AuthTokenMiddleware globalmente ao grupo 'api'.
        // Proteja apenas rotas específicas via Route::middleware('auth.token')->group(...)
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
