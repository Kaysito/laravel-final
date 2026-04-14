<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificacionCorreoMail; 

class UsuarioController extends Controller
{
    // =========================================================
    // 📖 LISTAR (Optimizado: Solo columnas necesarias)
    // =========================================================
    public function listar(Request $request)
    {
        $buscar = $request->query('buscar');
        
        // ⚡ Solo pedimos lo que la tabla muestra para ahorrar ancho de banda y RAM
        $query = Usuario::select(
                'id', 'strNombreUsuario', 'strCorreo', 'strNumeroCelular', 
                'strImagen', 'idPerfil', 'idEstadoUsuario', 
                'correo_verificado_at', 'celular_verificado_at'
            )
            ->with(['perfil' => function($q) {
                $q->select('id', 'strNombrePerfil'); 
            }])
            ->orderBy('id', 'DESC');

        if (!empty($buscar)) {
            $query->where(function($q) use ($buscar) {
                $q->where('strNombreUsuario', 'like', "%$buscar%")
                  ->orWhere('strCorreo', 'like', "%$buscar%");
            });
        }

        return response()->json($query->paginate(10));
    }

    // =========================================================
    // 🖼️ VISTAS (Organizadas por subcarpetas)
    // =========================================================
    public function crear()
    {
        $perfiles = Perfil::select('id', 'strNombrePerfil')->orderBy('strNombrePerfil', 'ASC')->get();
        return view('modules.vistasusuarios.nusuarios', compact('perfiles'));
    }

    public function editar($id)
    {
        $usuario = Usuario::findOrFail($id);
        $perfiles = Perfil::select('id', 'strNombrePerfil')->orderBy('strNombrePerfil', 'ASC')->get();
        return view('modules.vistasusuarios.eusuarios', compact('usuario', 'perfiles'));
    }

    public function detalle($id)
    {
        $usuario = Usuario::with('perfil:id,strNombrePerfil')->findOrFail($id);
        return view('modules.vistasusuarios.dusuarios', compact('usuario'));
    }

    // =========================================================
    // 💾 GUARDAR / ACTUALIZAR
    // =========================================================
    
    // 🛠️ CORRECCIÓN: Se cambió de 'store' a 'guardar' para que coincida con la ruta y el fetch de JS
    public function guardar(Request $request)
    {
        $request->validate([
            'strNombreUsuario' => 'required|string|max:70|regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/|unique:usuarios,strNombreUsuario',
            'strCorreo' => 'required|string|email:rfc,dns|max:100|unique:usuarios,strCorreo',
            'strNumeroCelular' => 'required|digits:10',
            'idPerfil' => 'required|exists:perfiles,id',
            'strPwd' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&.]/'],
            'strImagen' => 'nullable|string'
        ]);

        $datos = $request->all();
        $pwdPlana = $request->strPwd; 
        
        // Encriptar contraseña
        $datos['strPwd'] = Hash::make($pwdPlana);
        
        // 🛠️ CORRECCIÓN LÓGICA: Respetamos lo que el admin eligió en el switch (Activo 1 o Inactivo 0)
        $datos['idEstadoUsuario'] = $request->input('idEstadoUsuario', 0); 
        
        $datos['codigo_correo'] = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $usuario = Usuario::create($datos);

        try {
            Mail::to($usuario->strCorreo)->send(new VerificacionCorreoMail($usuario, $datos['codigo_correo'], $pwdPlana));
            $mensaje = 'Usuario creado. Se envió correo de verificación.';
        } catch (\Throwable $e) {
            \Log::error('Error Mail: ' . $e->getMessage());
            $mensaje = 'Usuario creado, pero falló el envío del correo.';
        }

        return response()->json(['success' => true, 'mensaje' => $mensaje]);
    }

    public function actualizarDesdeDetalle(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'strNombreUsuario' => 'required|unique:usuarios,strNombreUsuario,'.$id,
            'strCorreo'        => 'required|email|unique:usuarios,strCorreo,'.$id,
            'strNumeroCelular' => 'required|digits:10',
            'idPerfil'         => 'required|exists:perfiles,id'
        ]);

        $datos = $request->only(['strNombreUsuario', 'strCorreo', 'strNumeroCelular', 'idPerfil', 'strImagen']);
        $datos['idEstadoUsuario'] = $request->has('idEstadoUsuario') ? $request->idEstadoUsuario : 0;

        if ($request->filled('strPwd')) {
            $datos['strPwd'] = Hash::make($request->strPwd);
        }

        $usuario->update($datos);
        return response()->json(['success' => true, 'mensaje' => 'Usuario actualizado con éxito.']);
    }

    public function eliminar($id)
    {
        if ($id == 1) return response()->json(['success' => false, 'mensaje' => 'El sistema protege al Administrador Maestro.'], 403);
        
        Usuario::destroy($id);
        return response()->json(['success' => true, 'mensaje' => 'Usuario eliminado.']);
    }

    // =========================================================
    // 🔐 SEGURIDAD (2FA y SMS)
    // =========================================================
    public function setup2FA(Request $request)
    {
        $google2fa = app('pragmarx.google2fa'); 
        $usuario = auth()->user();

        $secretKey = $usuario->google2fa_secret ?: $google2fa->generateSecretKey();
        session(['2fa_secret_temp' => $secretKey]);

        $QR_Image = $google2fa->getQRCodeInline(config('app.name'), $usuario->strCorreo, $secretKey);

        return response()->json([
            'success' => true,
            'qr_image' => base64_encode($QR_Image),
            'secret' => $secretKey
        ]);
    }

    public function verificar2FA(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $usuario = auth()->user();
        $google2fa = app('pragmarx.google2fa');
        $secret = session('2fa_secret_temp') ?: $usuario->google2fa_secret;

        if ($google2fa->verifyKey($secret, $request->code)) {
            if (session('2fa_secret_temp')) {
                $usuario->update(['google2fa_secret' => $secret]);
                session()->forget('2fa_secret_temp');
            }
            return response()->json(['success' => true, 'mensaje' => '2FA activado correctamente.']);
        }
        return response()->json(['success' => false, 'mensaje' => 'Código incorrecto.'], 401);
    }

    // =========================================================
    // 👤 MI PERFIL
    // =========================================================
    public function miPerfil()
    {
        $usuario = auth()->user()->load('perfil:id,strNombrePerfil');
        return view('modules.miperfil', compact('usuario'));
    }

    public function actualizarMiPerfil(Request $request)
    {
        $usuario = auth()->user();
        $request->validate([
            'strNombreUsuario' => 'required|unique:usuarios,strNombreUsuario,'.$usuario->id,
            'strNumeroCelular' => 'required|digits:10',
            'strImagen'        => 'nullable|string'
        ]);

        $datos = $request->only(['strNombreUsuario', 'strNumeroCelular', 'strImagen']);
        if ($request->filled('strPwd')) $datos['strPwd'] = Hash::make($request->strPwd);

        $usuario->update($datos);
        return redirect()->route('miperfil')->with('success', 'Perfil actualizado.');
    }

    // =========================================================
    // 📱 TWILIO SMS
    // =========================================================
    public function enviarSmsVerificacion()
    {
        try {
            $usuario = auth()->user();
            if (!$usuario->strNumeroCelular) return response()->json(['success' => false, 'message' => 'Sin número celular.']);

            $codigo = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $usuario->update(['codigo_sms' => $codigo]);

            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $twilio->messages->create('+52'.$usuario->strNumeroCelular, [
                "from" => env('TWILIO_NUMBER'), 
                "body" => "Código Kairos: {$codigo}"
            ]);

            return response()->json(['success' => true, 'message' => 'Código enviado.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error Twilio.'], 500);
        }
    }

    public function verificarCodigoSms(Request $request)
    {
        $usuario = auth()->user();
        if ($usuario && $usuario->codigo_sms === $request->codigo) {
            $usuario->update(['celular_verificado_at' => now(), 'codigo_sms' => null]);
            return response()->json(['success' => true, 'message' => 'Celular verificado.']);
        }
        return response()->json(['success' => false, 'message' => 'Código incorrecto.']);
    }

    public function verificarCorreoLink($id, $codigo)
    {
        $usuario = Usuario::where('id', $id)->where('codigo_correo', $codigo)->first();
        if ($usuario) {
            $usuario->update(['correo_verificado_at' => now(), 'idEstadoUsuario' => 1, 'codigo_correo' => null]);
            return redirect()->route('login')->with('success', '¡Correo verificado!');
        }
        return redirect()->route('login')->withErrors(['error' => 'Enlace inválido.']);
    }
}