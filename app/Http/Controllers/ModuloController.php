<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modulo;
use App\Models\Perfil; // IMPORTANTE: Agregado para leer los perfiles
use Illuminate\Support\Facades\DB;

class ModuloController extends Controller
{
    // =========================================================
    // 📖 LISTADO PRINCIPAL (API JSON para la tabla)
    // =========================================================
    public function listar(Request $request)
    {
        $buscar = $request->query('buscar');
        $query = Modulo::orderBy('id', 'ASC');

        if (!empty($buscar)) {
            $query->where('strNombreModulo', 'like', '%' . $buscar . '%');
        }

        return response()->json($query->paginate(5));
    }

    // =========================================================
    // ➕ VISTA: NUEVO MÓDULO (vistasmodulos/nmodulos.blade.php)
    // =========================================================
    public function crear()
    {
        return view('modules.vistasmodulos.nmodulos', ['title' => 'Nuevo Módulo']);
    }

    // =========================================================
    // 💾 PROCESO: GUARDAR E INYECTAR EN LA MATRIZ
    // =========================================================
    public function guardar(Request $request)
    {
        // 1. Validamos incluyendo los nuevos campos del menú dinámico
        $request->validate([
            'strNombreModulo' => 'required|string|max:100|unique:modulos,strNombreModulo',
            'strGrupo'        => 'nullable|string|max:100',
            'strRuta'         => 'nullable|string|max:100',
            'strIcono'        => 'nullable|string|max:100'
        ]);

        // 2. Creamos el Módulo con su icono por defecto si no enviaron uno
        $modulo = Modulo::create([
            'strNombreModulo' => $request->strNombreModulo,
            'strGrupo'        => $request->strGrupo,
            'strRuta'         => $request->strRuta,
            'strIcono'        => $request->strIcono ?? 'fas fa-cube',
        ]);

        // 3. MAGIA: Auto-siembra en la tabla permisos_perfil
        $perfiles = Perfil::all();
        $permisosInyectar = [];
        $ahora = now();

        foreach ($perfiles as $perfil) {
            // El Perfil ID 1 (Súper Administrador) obtiene acceso total (1). El resto queda bloqueado (0).
            $esAdmin = ($perfil->id == 1) ? 1 : 0; 

            $permisosInyectar[] = [
                'idPerfil'    => $perfil->id,
                'idModulo'    => $modulo->id,
                'bitConsulta' => $esAdmin,
                'bitAgregar'  => $esAdmin,
                'bitEditar'   => $esAdmin,
                'bitEliminar' => $esAdmin,
                'bitDetalle'  => $esAdmin,
                'created_at'  => $ahora,
                'updated_at'  => $ahora,
            ];
        }

        // Insertamos todos los registros de golpe (Optimización de base de datos)
        if (!empty($permisosInyectar)) {
            DB::table('permisos_perfil')->insert($permisosInyectar);
        }

        // 4. Retornamos respuesta
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'mensaje' => '✅ Módulo registrado e integrado a la Matriz de Seguridad.',
                'id' => $modulo->id
            ]);
        }

        return redirect()->route('modulo.index')->with('success', 'Módulo registrado');
    }

    // =========================================================
    // ✏️ VISTA: EDITAR MÓDULO (vistasmodulos/emodulos.blade.php)
    // =========================================================
    public function editar($id)
    {
        $modulo = Modulo::findOrFail($id);
        return view('modules.vistasmodulos.emodulos', [
            'title' => 'Editar Módulo',
            'modulo' => $modulo
        ]);
    }

    // =========================================================
    // 🔄 PROCESO: ACTUALIZAR
    // =========================================================
    public function actualizar(Request $request, $id)
    {
        // Actualizamos las reglas para permitir la edición de los nuevos campos
        $request->validate([
            'strNombreModulo' => 'required|string|max:100|unique:modulos,strNombreModulo,' . $id,
            'strGrupo'        => 'nullable|string|max:100',
            'strRuta'         => 'nullable|string|max:100',
            'strIcono'        => 'nullable|string|max:100'
        ]);

        $modulo = Modulo::findOrFail($id);
        
        // Si mandaron el icono vacío en la edición, le devolvemos un valor por defecto
        $datosActualizar = $request->all();
        if (empty($datosActualizar['strIcono'])) {
            $datosActualizar['strIcono'] = 'fas fa-cube';
        }

        $modulo->update($datosActualizar);

        return response()->json([
            'success' => true,
            'mensaje' => 'Módulo actualizado correctamente'
        ]);
    }

    // =========================================================
    // 🔍 VISTA: DETALLE (vistasmodulos/dmodulos.blade.php)
    // =========================================================
    public function detalle($id)
    {
        $modulo = Modulo::find($id);

        if (!$modulo) {
            return redirect()->route('modulo.index')->with('error', 'El módulo no existe.');
        }

        // Obtenemos qué perfiles tienen acceso a este módulo específico
        $permisosRelacionados = DB::table('permisos_perfil')
            ->join('perfiles', 'permisos_perfil.idPerfil', '=', 'perfiles.id')
            ->where('permisos_perfil.idModulo', $id)
            ->select(
                'perfiles.strNombrePerfil as perfil',
                'permisos_perfil.bitConsulta as v',
                'permisos_perfil.bitAgregar as c',
                'permisos_perfil.bitEditar as e',
                'permisos_perfil.bitEliminar as d',
                'permisos_perfil.bitDetalle as l'
            )
            ->get();

        return view('modules.vistasmodulos.dmodulos', [
            'modulo' => $modulo,
            'permisosReales' => $permisosRelacionados
        ]);
    }

    // =========================================================
    // 🗑️ ELIMINAR (API)
    // =========================================================
    public function eliminar($id)
    {
        $modulo = Modulo::find($id);
        if ($modulo) {
            // Nota: Debido a las llaves foráneas y el "onDelete('cascade')" en tus migraciones,
            // al eliminar el módulo se borrarán automáticamente sus registros en 'permisos_perfil'
            $modulo->delete();
            return response()->json(['success' => true, 'mensaje' => 'Módulo eliminado']);
        }
        return response()->json(['success' => false, 'message' => 'No encontrado'], 404);
    }
}