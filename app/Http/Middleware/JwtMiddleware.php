<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Exception;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    /**
     * Maneja una petición entrante.
     * * Este Middleware actúa como el primer "Filtro de Seguridad".
     * Extrae el token de la cookie, lo valida y loguea al usuario.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. EXTRAER EL TOKEN DE LA COOKIE
        // Buscamos 'jwt_token' porque así lo nombramos en el script del Login
        $token = $request->cookie('jwt_token');

        // REFUERZO: Si no hay token en la cookie, redirigimos al Login con un aviso
        if (!$token) {
            return redirect('/')->with('error', '❌ Acceso denegado. Debes iniciar sesión primero.');
        }

        try {
            // 2. EL TRUCO PARA RENDER (HTTPS)
            // Forzamos el token en la cabecera 'Authorization' para que el motor
            // de JWTAuth lo encuentre siempre, incluso si el navegador lo envió como cookie.
            $request->headers->set('Authorization', 'Bearer ' . $token);

            // 3. AUTENTICACIÓN
            // Intentamos obtener al usuario dueño de ese token
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return redirect('/')->with('error', '❌ Usuario no encontrado en el sistema.');
            }

            // 4. PERSISTENCIA EN BLADE
            // Logueamos al usuario en el "Guard" de Laravel para que funciones como 
            // Auth::user() o @auth funcionen correctamente en tus vistas.
            Auth::login($user);

        } catch (TokenExpiredException $e) {
            // Caso: El token ya cumplió su tiempo de vida (ej. 60 min)
            return redirect('/')->with('error', '⚠️ Tu sesión ha expirado. Por favor, ingresa de nuevo.');

        } catch (TokenInvalidException $e) {
            // Caso: El token fue manipulado o la JWT_SECRET en Render cambió
            return redirect('/')->with('error', '🚫 Sesión inválida o manipulada. Acceso bloqueado.');

        } catch (Exception $e) {
            // Cualquier otro error técnico (Base de datos caída, error de servidor, etc.)
            return redirect('/')->with('error', '❌ Error crítico de seguridad: ' . $e->getMessage());
        }

        // Si todo está OK, el usuario pasa a la ruta solicitada (ej. /home)
        return $next($request);
    }
}