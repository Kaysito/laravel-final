<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    // 1. Apuntamos a la tabla correcta
    protected $table = 'perfiles';

    // 2. Permitimos el llenado masivo de tus campos
    protected $fillable = [
        'strNombrePerfil',
        'bitAdministrador'
    ];
}