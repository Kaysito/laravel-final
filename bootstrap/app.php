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
        
        // 🛡️ REGLA DE ORO: No encriptar la cookie del Token
        // Esto permite que nuestro JwtMiddleware lea el token creado por JS
        $middleware->encryptCookies(except: [
            'jwt_token',
        ]);

        // Registramos los apodos (alias) para usarlos en las rutas
        $middleware->alias([
            'jwt.verify' => \App\Http\Middleware\JwtMiddleware::class,
            'permiso'    => \App\Http\Middleware\VerificarPermiso::class, // 👈 NUESTRO NUEVO CANDADO RBAC
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();