<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfil;
use App\Models\Modulo;
use Illuminate\Support\Facades\DB;

class FixAdminPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos al Super Administrador por nombre o por bit
        $perfilAdmin = Perfil::where('strNombrePerfil', 'like', '%Super%')
                            ->orWhere('bitAdministrador', 1)
                            ->first();

        // 2. Si NO existe, tomamos el ID 1 y lo convertimos
        if (!$perfilAdmin) {
            $perfilAdmin = Perfil::first();
            
            if (!$perfilAdmin) {
                // Si la tabla está vacía, lo creamos
                $perfilAdmin = Perfil::create([
                    'strNombrePerfil' => 'Súper Administrador',
                    'bitAdministrador' => 1
                ]);
            } else {
                // Si existía pero no era admin, lo ascendemos
                $perfilAdmin->update(['bitAdministrador' => 1]);
            }
        } else {
            // Si lo encontró por nombre pero el bit estaba en 0, lo encendemos
            $perfilAdmin->update(['bitAdministrador' => 1]);
        }

        $this->command->info("Perfil identificado como Dios: {$perfilAdmin->strNombrePerfil} (ID: {$perfilAdmin->id})");

        // 3. Obtenemos todos los módulos
        $modulos = Modulo::all();

        if ($modulos->isEmpty()) {
            $this->command->error('No hay módulos en la tabla. Ejecuta el ModulosSeeder primero.');
            return;
        }

        // 4. Asignamos permisos totales a ese ID en la tabla pivote
        foreach ($modulos as $modulo) {
            DB::table('permisos_perfil')->updateOrInsert(
                ['idPerfil' => $perfilAdmin->id, 'idModulo' => $modulo->id],
                [
                    'bitConsulta' => 1,
                    'bitAgregar'  => 1,
                    'bitEditar'   => 1,
                    'bitEliminar' => 1,
                    'bitDetalle'  => 1,
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ]
            );
        }

        $this->command->info('✅ EXITO: El perfil ahora es Administrador y tiene todos los permisos RBAC.');
    }
}