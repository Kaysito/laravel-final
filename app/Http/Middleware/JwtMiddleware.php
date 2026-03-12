<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Buscamos el token en la cookie (intentamos de dos formas por compatibilidad)
        $token = $request->cookie('jwt_token') ?? $_COOKIE['jwt_token'] ?? null;

        // Candado 1: Si no hay token, fuera.
        if (!$token) {
            return redirect('/')->with('error', '❌ Acceso denegado. Inicia sesión primero.');
        }

        try {
            // 2. Le pasamos el token al motor JWT
            JWTAuth::setToken($token);
            
            // 3. Autenticamos al usuario
            $user = JWTAuth::authenticate();
            
            if (!$user) {
                return redirect('/')->with('error', '❌ Usuario no encontrado.');
            }
            
            // Logueamos al usuario en la sesión actual de Laravel
            Auth::login($user);
            
        } catch (Exception $e) {
            // Si el token expiró o es basura, limpiamos y rebotamos
            return redirect('/')->with('error', '❌ Sesión inválida o expirada.');
        }

        return $next($request);
    }
}