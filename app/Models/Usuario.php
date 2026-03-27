<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $table = 'usuarios';

    /**
     * Los atributos que se pueden asignar masivamente.
     * Se agregó 'google2fa_secret' para la autenticación de dos factores.
     */
    protected $fillable = [
        'strNombreUsuario',
        'idPerfil',
        'strPwd',
        'idEstadoUsuario',
        'strCorreo',
        'strNumeroCelular',
        'strImagen',
        'codigo_correo',
        'correo_verificado_at',
        'codigo_sms',
        'celular_verificado_at',
        'google2fa_secret', // 🔐 Campo para la llave secreta del QR
    ];

    /**
     * Los atributos que deben estar ocultos para los arrays.
     */
    protected $hidden = [
        'strPwd',
        'google2fa_secret', // 🛡️ Oculto por seguridad en respuestas API
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'correo_verificado_at' => 'datetime',
        'celular_verificado_at' => 'datetime',
    ];

    /**
     * Define el nombre de la columna de contraseña para la autenticación.
     */
    public function getAuthPasswordName()
    {
        return 'strPwd';
    }

    /**
     * Relación con el Perfil asignado.
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'idPerfil');
    }

    // ── Métodos requeridos por JWT ─────────────────────────────────────

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return []; 
    }

    // ── Lógica de Seguridad y Permisos ───────────────────────────────

    /**
     * Verifica si el usuario tiene un permiso específico en un módulo.
     * * Implementa:
     * 1. Bypass total para Súper Administradores (bitAdministrador = 1).
     * 2. Tolerancia a errores de escritura en nombres de módulos (LIKE).
     */
    public function tienePermiso($nombreModulo, $accion = 'bitConsulta')
    {
        // 1. Cargamos el perfil si no existe para evitar errores de objeto nulo
        if (!$this->perfil) {
            $this->load('perfil');
        }

        // 🛡️ REGLA MAESTRA: Si el perfil es Súper Administrador, tiene acceso total.
        if ($this->perfil && $this->perfil->bitAdministrador == 1) {
            return true;
        }

        // 2. Si no es admin, buscamos el permiso en la matriz.
        // Usamos trim() y comparación flexible para evitar fallos por espacios o guiones.
        $permiso = DB::table('permisos_perfil')
            ->join('modulos', 'permisos_perfil.idModulo', '=', 'modulos.id')
            ->where('permisos_perfil.idPerfil', $this->idPerfil)
            ->where(function($query) use ($nombreModulo) {
                $nombreLimpio = trim($nombreModulo);
                $query->where('modulos.strNombreModulo', $nombreLimpio)
                      ->orWhere('modulos.strNombreModulo', 'LIKE', '%' . $nombreLimpio . '%');
            })
            ->select("permisos_perfil.$accion as bitResultado")
            ->first();

        // 3. Retornamos true solo si el registro existe y el bit es 1
        return $permiso ? (bool) $permiso->bitResultado : false;
    }
}