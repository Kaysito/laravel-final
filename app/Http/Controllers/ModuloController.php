<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modulo;
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
    // 💾 PROCESO: GUARDAR
    // =========================================================
    public function guardar(Request $request)
    {
        $request->validate([
            'strNombreModulo' => 'required|string|max:100|unique:modulos,strNombreModulo',
        ]);

        $modulo = Modulo::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'mensaje' => '✅ Módulo registrado exitosamente',
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
        $request->validate([
            'strNombreModulo' => 'required|string|max:100|unique:modulos,strNombreModulo,' . $id,
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->update($request->all());

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
            $modulo->delete();
            return response()->json(['success' => true, 'mensaje' => 'Módulo eliminado']);
        }
        return response()->json(['success' => false, 'message' => 'No encontrado'], 404);
    }
}