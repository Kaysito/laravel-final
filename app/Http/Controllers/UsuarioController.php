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
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    // =========================================================
    // 📖 LISTAR (Optimizado para Datatables/Frontend)
    // =========================================================
    public function listar(Request $request)
    {
        $buscar = $request->query('buscar');
        
        $query = Usuario::select(
                'id', 'strNombreUsuario', 'strCorreo', 'strNumeroCelular', 
                'strImagen', 'idPerfil', 'idEstadoUsuario', 
                'correo_verificado_at', 'celular_verificado_at', 'google2fa_secret'
            )
            ->with(['perfil:id,strNombrePerfil'])
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
    // 🖼️ VISTAS
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

        try {
            DB::beginTransaction();

            $pwdPlana = $request->strPwd;
            $codigo = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $usuario = Usuario::create([
                'strNombreUsuario' => $request->strNombreUsuario,
                'strCorreo'        => $request->strCorreo,
                'strNumeroCelular' => $request->strNumeroCelular,
                'idPerfil'         => $request->idPerfil,
                'strPwd'           => Hash::make($pwdPlana),
                'idEstadoUsuario'  => $request->input('idEstadoUsuario', 0),
                'strImagen'        => $request->strImagen,
                'codigo_correo'    => $codigo,
            ]);

            Mail::to($usuario->strCorreo)->send(new VerificacionCorreoMail($usuario, $codigo, $pwdPlana));

            DB::commit();
            return response()->json(['success' => true, 'mensaje' => 'Usuario creado y correo de verificación enviado.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al guardar usuario: ' . $e->getMessage());
            return response()->json(['success' => false, 'mensaje' => 'Error al procesar el registro.'], 500);
        }
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
        $datos['idEstadoUsuario'] = $request->input('idEstadoUsuario', 0);

        if ($request->filled('strPwd')) {
            $datos['strPwd'] = Hash::make($request->strPwd);
        }

        $usuario->update($datos);
        return response()->json(['success' => true, 'mensaje' => 'Información actualizada correctamente.']);
    }

    public function eliminar($id)
    {
        // Protección Master Admin
        if ($id == 1) {
            return response()->json(['success' => false, 'mensaje' => 'No se puede eliminar al Administrador del Sistema.'], 403);
        }
        
        Usuario::destroy($id);
        return response()->json(['success' => true, 'mensaje' => 'Usuario eliminado permanentemente.']);
    }

    // =========================================================
    // 🔐 SEGURIDAD (2FA Google Authenticator)
    // =========================================================
    public function setup2FA(Request $request)
    {
        $google2fa = app('pragmarx.google2fa'); 
        $usuario = auth()->user();

        // Generar llave si no existe
        $secretKey = $google2fa->generateSecretKey();
        
        // Guardamos temporalmente en sesión para validar antes de guardar en DB
        session(['2fa_secret_temp' => $secretKey]);

        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $usuario->strCorreo,
            $secretKey
        );

        return response()->json([
            'success' => true,
            'qr_image' => $qrCodeUrl, // Esto devuelve el SVG/PNG listo para <img>
            'secret' => $secretKey
        ]);
    }

    public function verificar2FA(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $usuario = auth()->user();
        $google2fa = app('pragmarx.google2fa');
        
        $secret = session('2fa_secret_temp') ?: $usuario->google2fa_secret;

        if ($google2fa->verifyKey($secret, $request->code)) {
            if (session('2fa_secret_temp')) {
                $usuario->update(['google2fa_secret' => $secret]);
                session()->forget('2fa_secret_temp');
            }
            return response()->json(['success' => true, 'mensaje' => 'Doble factor de autenticación verificado.']);
        }

        return response()->json(['success' => false, 'mensaje' => 'El código introducido es inválido.'], 401);
    }

    // =========================================================
    // 📱 VERIFICACIÓN SMS (Twilio)
    // =========================================================
    public function enviarSmsVerificacion()
    {
        try {
            $usuario = auth()->user();
            if (!$usuario->strNumeroCelular) {
                return response()->json(['success' => false, 'message' => 'El usuario no tiene un número celular registrado.']);
            }

            $codigo = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $usuario->update(['codigo_sms' => $codigo]);

            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $twilio->messages->create('+52' . $usuario->strNumeroCelular, [
                "from" => env('TWILIO_NUMBER'), 
                "body" => "Tu código de seguridad para KairosAI es: {$codigo}. No lo compartas."
            ]);

            return response()->json(['success' => true, 'message' => 'Código enviado exitosamente vía SMS.']);
        } catch (\Exception $e) {
            Log::error('Error Twilio: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al conectar con el servicio de mensajería.'], 500);
        }
    }

    public function verificarCodigoSms(Request $request)
    {
        $request->validate(['codigo' => 'required|digits:6']);
        $usuario = auth()->user();

        if ($usuario && $usuario->codigo_sms === $request->codigo) {
            $usuario->update([
                'celular_verificado_at' => now(),
                'codigo_sms' => null
            ]);
            return response()->json(['success' => true, 'message' => 'Teléfono verificado correctamente.']);
        }

        return response()->json(['success' => false, 'message' => 'El código es incorrecto o ha expirado.']);
    }

    // =========================================================
    // 📧 VERIFICACIÓN POR CORREO (Link)
    // =========================================================
    public function verificarCorreoLink($id, $codigo)
    {
        $usuario = Usuario::where('id', $id)->where('codigo_correo', $codigo)->first();

        if ($usuario) {
            $usuario->update([
                'correo_verificado_at' => now(),
                'idEstadoUsuario'      => 1, // Activamos al usuario al verificar su correo
                'codigo_correo'        => null
            ]);
            return redirect()->route('login')->with('success', '¡Cuenta activada! Ya puedes iniciar sesión.');
        }

        return redirect()->route('login')->withErrors(['error' => 'El enlace de verificación es inválido o ya fue utilizado.']);
    }
}