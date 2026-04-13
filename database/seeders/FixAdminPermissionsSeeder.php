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
        // 1. Buscamos al Administrador Absoluto.
        // Priorizamos el bit de administrador, luego buscamos por nombres comunes si el bit falló.
        $perfilAdmin = Perfil::where('bitAdministrador', 1)
                            ->orWhere('strNombrePerfil', 'like', '%Admin%')
                            ->orWhere('strNombrePerfil', 'like', '%Super%')
                            ->first();

        // 2. Si NO existe un perfil admin claro, tomamos el ID 1 y lo ascendemos a la fuerza
        if (!$perfilAdmin) {
            $perfilAdmin = Perfil::first();
            
            if (!$perfilAdmin) {
                $this->command->error('❌ No hay ningún perfil en la base de datos. Ejecuta los seeders base o crea un perfil primero.');
                return;
            } else {
                $perfilAdmin->update(['bitAdministrador' => 1]);
            }
        } else {
            // Si lo encontró, nos aseguramos de que su bit esté en 1 por seguridad
            $perfilAdmin->update(['bitAdministrador' => 1]);
        }

        $this->command->info("👑 Perfil Dios detectado: {$perfilAdmin->strNombrePerfil} (ID: {$perfilAdmin->id})");

        // 3. Obtenemos TODOS los módulos (incluyendo los que tienen Menús y Submenús dinámicos)
        $modulos = Modulo::all();

        if ($modulos->isEmpty()) {
            $this->command->error('❌ No hay módulos en la tabla. Crea los módulos en el sistema primero.');
            return;
        }

        // 4. Asignamos la matriz de permisos absolutos a ese ID en la tabla pivote
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

        $this->command->info('✅ ÉXITO: Matriz RBAC sincronizada. El Administrador tiene acceso total a los ' . $modulos->count() . ' módulos del sistema.');
    }
}