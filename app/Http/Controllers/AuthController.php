<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\Usuario;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean' // 👈 Recibimos si el usuario marcó la casilla
        ]);

        $user = Usuario::where('strNombreUsuario', $request->usuario)->first();
        
        if (!$user) {
            return response()->json(['error' => '❌ BLOQUEO: El usuario NO existe.'], 401);
        }

        if (!Hash::check($request->password, $user->strPwd)) {
            return response()->json(['error' => '❌ BLOQUEO: Contraseña incorrecta.'], 401);
        }

        if ($user->idEstadoUsuario != 1) {
            return response()->json(['error' => '❌ BLOQUEO: Usuario inactivo.'], 403);
        }

        // ⏱️ LÓGICA DE TIEMPO (REMEMBER ME)
        // Si marcó recordar, le damos 30 días (43200 min). Si no, 1 hora (60 min).
        $minutos = $request->remember ? 43200 : 60;
        
        // Le indicamos al motor JWT cuánto debe durar este token específico
        JWTAuth::factory()->setTTL($minutos);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'redirect' => '/home',
            'expires_in' => $minutos // Le mandamos el tiempo al Frontend
        ]);
    }

    // 🚪 LÓGICA DE CERRAR SESIÓN
    public function logout()
    {
        try {
            // "Quemamos" el token actual para que quede inservible en el servidor
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {
            // Si el token ya había expirado, lo ignoramos y seguimos
        }

        // Destruimos la cookie en el navegador y mandamos al login
        return redirect('/')->withCookie(Cookie::forget('jwt_token'));
    }
}