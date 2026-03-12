<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // 👈 Superpoder de Login
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;    // 👈 Superpoder de Tokens

class Usuario extends Authenticatable implements JWTSubject
{
    // 1. Le decimos exactamente qué tabla usar
    protected $table = 'usuarios';

    // 2. Los campos de tu especificación
    protected $fillable = [
        'strNombreUsuario',
        'idPerfil',
        'strPwd',
        'idEstadoUsuario',
        'strCorreo',
        'strNumeroCelular',
    ];

    // 3. Ocultamos la contraseña por seguridad
    protected $hidden = [
        'strPwd',
    ];

    // 4. TRUCO MAESTRO: Le decimos a Laravel que tu contraseña NO se llama 'password'
    public function getAuthPasswordName()
    {
        return 'strPwd';
    }

    // --- MÉTODOS OBLIGATORIOS PARA JWT ---
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return []; 
    }
}