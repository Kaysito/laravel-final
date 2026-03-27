<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        $user = Usuario::where('strNombreUsuario', $request->usuario)
                    ->orWhere('strCorreo', $request->usuario)
                    ->first();
        
        if (!$user) {
            return response()->json(['error' => 'Las credenciales ingresadas no coinciden.'], 401);
        }

        if (!Hash::check($request->password, $user->strPwd)) {
            return response()->json(['error' => 'La contraseña es incorrecta.'], 401);
        }

        if ($user->idEstadoUsuario != 1) {
            if (is_null($user->correo_verificado_at)) {
                return response()->json(['error' => 'Tu cuenta no está verificada. Revisa tu correo.'], 403);
            }
            return response()->json(['error' => 'Tu cuenta ha sido desactivada.'], 403);
        }

        // 👇 AQUI ESTÁ LA CORRECCIÓN: Usamos los nombres REALES de tu base de datos 👇
        $permisos = DB::table('permisos_perfil')
            ->join('modulos', 'permisos_perfil.idModulo', '=', 'modulos.id')
            ->where('idPerfil', $user->idPerfil)
            ->select(
                'modulos.strNombreModulo as modulo',
                'bitConsulta',
                'bitAgregar',
                'bitEditar',
                'bitEliminar',
                'bitDetalle'
            )
            ->get()
            ->keyBy('modulo'); 

        $minutos = $request->remember ? 43200 : 60;
        JWTAuth::factory()->setTTL($minutos);

        $token = JWTAuth::customClaims(['permissions' => $permisos])->fromUser($user);

        return response()->json([
            'token' => $token,
            'redirect' => '/home',
            'expires_in' => $minutos,
            'user' => [
                'nombre' => $user->strNombreUsuario,
                'perfil' => $user->idPerfil,
                'permisos' => $permisos 
            ]
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {}

        return redirect('/')->withCookie(Cookie::forget('jwt_token'));
    }
}