<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CheckSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        // Verificamos si hay un usuario autenticado
        if (Auth::check()) {
            $user = Auth::user();
            
            try {
                // Extraemos el 'sid' que metimos dentro del token JWT
                $payload = JWTAuth::parseToken()->getPayload();
                $tokenSid = $payload->get('sid');

                // ¿El código del token es distinto al de la base de datos?
                if ($user->active_session_id !== $tokenSid) {
                    
                    // Invalidamos este token para que no se pueda volver a usar
                    JWTAuth::invalidate(JWTAuth::getToken());
                    Auth::logout();
                    
                    return response()->json([
                        'error' => 'Tu sesión ha sido cerrada porque iniciaste sesión en otro dispositivo.',
                        'code' => 'SESSION_EXPIRED' // Un código útil para tu frontend
                    ], 401);
                }
                
            } catch (\Exception $e) {
                return response()->json(['error' => 'Token inválido o manipulado.'], 401);
            }
        }

        // Si todo está correcto, le permitimos pasar
        return $next($request);
    }
}