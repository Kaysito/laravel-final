<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPermiso
{
    /**
     * Maneja la restricción de acceso por Módulo y Acción.
     */
    public function handle(Request $request, Closure $next, $modulo, $accion = 'bitConsulta'): Response
    {
        // 1. Obtenemos al usuario autenticado
        $usuario = auth()->user();

        // 2. Verificamos el permiso usando la lógica del modelo (que ya incluye el bypass de Admin)
        if (!$usuario || !$usuario->tienePermiso($modulo, $accion)) {
            
            // Respuesta para peticiones API/JS
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Acceso denegado: Privilegios insuficientes.'
                ], 403);
            }

            // Respuesta para navegación web tradicional
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a la sección: ' . $modulo);
        }

        // 3. Todo en orden, adelante
        return $next($request);
    }
}