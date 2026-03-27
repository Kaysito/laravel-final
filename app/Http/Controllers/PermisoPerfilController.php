<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermisoPerfil;
use App\Models\Perfil;
use App\Models\Modulo;
use Illuminate\Support\Facades\DB; // 👈 Importamos DB para las transacciones

class PermisoPerfilController extends Controller
{
    // 📖 LISTAR: Si recibe un idPerfil, trae solo los de ese perfil.
    public function listar(Request $request)
    {
        $idPerfil = $request->query('perfil');
        
        $query = PermisoPerfil::with(['modulo', 'perfil']);
        
        if ($idPerfil) {
            $query->where('idPerfil', $idPerfil);
            return response()->json($query->get()); // Sin paginar para la matriz
        }

        // Si no hay filtro (vista general), paginamos a 5
        return response()->json($query->paginate(5));
    }

    // 📋 CATÁLOGOS: Trae todos los perfiles y módulos
    public function catalogos()
    {
        return response()->json([
            'perfiles' => Perfil::all(),
            'modulos' => Modulo::all()
        ]);
    }

    // 💾 GUARDAR: Mejorado con transacciones (Bulk Safe)
    public function guardar(Request $request)
    {
        $request->validate([
            'idPerfil' => 'required|integer',
            'modulos' => 'required|array', 
        ]);

        $idPerfil = $request->idPerfil;

        try {
            // 🛡️ Iniciamos la transacción: Todo o Nada
            DB::beginTransaction(); 

            foreach ($request->modulos as $modulo) {
                PermisoPerfil::updateOrCreate(
                    ['idPerfil' => $idPerfil, 'idModulo' => $modulo['idModulo']],
                    [
                        'bitConsulta' => $modulo['bitConsulta'] ? 1 : 0,
                        'bitAgregar'  => $modulo['bitAgregar'] ? 1 : 0,
                        'bitEditar'   => $modulo['bitEditar'] ? 1 : 0,
                        'bitEliminar' => $modulo['bitEliminar'] ? 1 : 0,
                        'bitDetalle'  => $modulo['bitDetalle'] ? 1 : 0,
                    ]
                );
            }

            // 🛡️ Si el bucle termina sin errores, aplicamos los cambios a la BD
            DB::commit(); 
            return response()->json(['success' => true, 'mensaje' => 'Permisos guardados correctamente.']);

        } catch (\Exception $e) {
            // 🛡️ Si algo explota a la mitad, deshacemos todos los cambios del bucle
            DB::rollBack(); 
            \Log::error("Error guardando permisos: " . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'mensaje' => 'Ocurrió un error al guardar los permisos.'
            ], 500);
        }
    }
}