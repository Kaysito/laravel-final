<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear el Perfil Maestro (Súper Administrador - ID 1)
        DB::table('perfiles')->insert([
            'strNombrePerfil' => 'Súper Administrador',
            // Eliminamos la descripción para que coincida con tu migración real
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Crear el Usuario Root (ID 1) vinculado al Perfil 1
        DB::table('usuarios')->insert([
            'strNombreUsuario' => 'admin',
            'strCorreo' => 'admin@empresa.com',
            'strPwd' => Hash::make('Admin123!'), // Contraseña encriptada
            'idEstadoUsuario' => 1, // Activo
            'idPerfil' => 1, // Súper Administrador
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}