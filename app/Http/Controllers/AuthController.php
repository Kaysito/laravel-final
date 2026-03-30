<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Usuario;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. VALIDACIÓN INICIAL
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean',
            'g-recaptcha-response' => 'required' // Requerimos el captcha del frontend
        ], [
            'usuario.required' => 'Debes ingresar tu usuario.',
            'password.required' => 'Debes ingresar tu contraseña.',
            'g-recaptcha-response.required' => 'Por favor, completa el reCAPTCHA.'
        ]);

        // 2. VERIFICACIÓN DE RECAPTCHA CON GOOGLE
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip()
        ]);

        if (!$recaptchaResponse->json('success')) {
            return response()->json(['error' => 'La verificación de seguridad falló. Inténtalo de nuevo.'], 401);
        }

        // 3. BUSCAR USUARIO (POR NOMBRE O CORREO)
        $user = Usuario::where('strNombreUsuario', $request->usuario)
                    ->orWhere('strCorreo', $request->usuario)
                    ->first();
        
        if (!$user) {
            return response()->json(['error' => 'Las credenciales ingresadas no coinciden.'], 401);
        }

        // 4. VALIDAR CONTRASEÑA (USANDO NUESTRA COLUMNA PERSONALIZADA strPwd)
        if (!Hash::check($request->password, $user->strPwd)) {
            return response()->json(['error' => 'La contraseña es incorrecta.'], 401);
        }

        // 5. VALIDAR ESTADO Y VERIFICACIÓN
        if ($user->idEstadoUsuario != 1) {
            if (is_null($user->correo_verificado_at)) {
                return response()->json(['error' => 'Tu cuenta no está verificada. Revisa tu correo.'], 403);
            }
            return response()->json(['error' => 'Tu cuenta ha sido desactivada.'], 403);
        }

        // 6. OBTENER PERMISOS DINÁMICOS
        $permisos = DB::table('permisos_perfil')
            ->join('modulos', 'permisos_perfil.idModulo', '=', 'modulos.id')
            ->where('idPerfil', $user->idPerfil)
            ->select(
                'modulos.strNombreModulo as modulo',
                'bitConsulta', 'bitAgregar', 'bitEditar', 'bitEliminar', 'bitDetalle'
            )
            ->get()
            ->keyBy('modulo'); 

        // 7. LÓGICA DE SESIÓN ÚNICA (ELIMINA LA SESIÓN ANTERIOR)
        // Generamos un nuevo ID aleatorio. Al guardarlo, cualquier token viejo que 
        // intente entrar ya no coincidirá con este nuevo ID en la DB.
        $newSessionId = Str::random(40);
        $user->active_session_id = $newSessionId;
        $user->save();

        // 8. CONFIGURAR TIEMPO DE VIDA DEL TOKEN
        $minutos = $request->remember ? 43200 : 60; // 30 días o 1 hora
        JWTAuth::factory()->setTTL($minutos);

        // 9. GENERAR TOKEN CON CLAIM PERSONALIZADO 'sid'
        $token = JWTAuth::customClaims([
            'permissions' => $permisos,
            'sid' => $newSessionId // Aquí inyectamos la "llave" de la sesión única
        ])->fromUser($user);

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
            // Limpiamos el ID de sesión en la DB para que el token quede invalidado totalmente
            if ($user = JWTAuth::user()) {
                $user->active_session_id = null;
                $user->save();
            }
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {
            // Si el token ya expiró o es inválido, no hacemos nada
        }

        return redirect('/')
            ->withCookie(Cookie::forget('jwt_token'))
            ->with('success', 'Sesión cerrada correctamente.');
    }
}