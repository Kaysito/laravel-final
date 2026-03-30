<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class CheckSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Intentamos obtener al usuario a través del token
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado.'], 401);
            }

            // Extraemos el payload
            $payload = JWTAuth::parseToken()->getPayload();
            $tokenSid = $payload->get('sid');

            // COMPARACIÓN CRÍTICA:
            // Usamos != en lugar de !== por si uno es null y el otro string vacío
            if ($user->active_session_id != $tokenSid) {
                
                // Si tienes el token en cookie, podrías intentar limpiarla aquí
                Auth::logout();
                
                return response()->json([
                    'error' => 'Tu sesión ha sido abierta en otro dispositivo.',
                    'code' => 'SESSION_DUPLICATED'
                ], 401);
            }

        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Tu sesión ha expirado.'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido o manipulado.'], 401);
        } catch (\Exception $e) {
            // Si llega aquí, es porque no hay token en la petición
            return response()->json(['error' => 'No se encontró el token de acceso.'], 401);
        }

        return $next($request);
    }
}