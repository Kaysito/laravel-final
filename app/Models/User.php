<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB; // <-- Importante agregar esto

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'idPerfil', // <-- Agregado para que puedas asignar perfiles masivamente
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Verifica si el usuario tiene un permiso específico en un módulo.
     *
     * @param string $nombreModulo (Ej: 'Usuarios', 'Perfil')
     * @param string $accion (Ej: 'bitConsulta', 'bitAgregar', 'bitEditar')
     * @return bool
     */
    public function tienePermiso($nombreModulo, $accion = 'bitConsulta')
    {
        // 1. Si el usuario no tiene un perfil asignado, denegamos por defecto
        if (!$this->idPerfil) {
            return false;
        }

        // 2. Buscamos el permiso exacto cruzando la tabla pivote con la de módulos
        $permiso = DB::table('permisos_perfil')
            ->join('modulos', 'permisos_perfil.idModulo', '=', 'modulos.id')
            ->where('permisos_perfil.idPerfil', $this->idPerfil)
            ->where('modulos.strNombreModulo', $nombreModulo)
            ->first();

        // 3. Si existe el registro, devolvemos el valor de la acción (1 o 0 convertido a booleano)
        if ($permiso) {
            return (bool) $permiso->$accion;
        }

        // 4. Si no existe el registro en la matriz, denegamos el acceso
        return false;
    }
}