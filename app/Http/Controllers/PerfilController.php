<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    // =========================================================
    // 📖 LISTADO PRINCIPAL (API JSON para la tabla)
    // =========================================================
    public function listar(Request $request)
    {
        $buscar = $request->query('buscar'); 
        $query = Perfil::orderBy('id', 'DESC');

        if (!empty($buscar)) {
            $query->where('strNombrePerfil', 'like', '%' . $buscar . '%');
        }

        $perfiles = $query->paginate(5);
        return response()->json($perfiles);
    }

    // =========================================================
    // ➕ VISTA: NUEVO PERFIL (vistasperfil/nperfil.blade.php)
    // =========================================================
    public function crear()
    {
        return view('modules.vistasperfil.nperfil', ['title' => 'Nuevo Perfil']);
    }

    // =========================================================
    // 💾 PROCESO: GUARDAR
    // =========================================================
    public function guardar(Request $request)
    {
        $request->validate([
            'strNombrePerfil' => 'required|string|unique:perfiles,strNombrePerfil',
            'bitAdministrador' => 'nullable'
        ]);

        $perfil = Perfil::create([
            'strNombrePerfil' => $request->strNombrePerfil,
            'bitAdministrador' => $request->bitAdministrador ? 1 : 0
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'mensaje' => '✅ Perfil creado exitosamente', 
                'id' => $perfil->id
            ]);
        }

        return redirect()->route('perfil.index')->with('success', 'Perfil creado');
    }

    // =========================================================
    // ✏️ VISTA: EDITAR PERFIL (vistasperfil/eperfil.blade.php)
    // =========================================================
    public function editar($id)
    {
        $perfil = Perfil::findOrFail($id);
        return view('modules.vistasperfil.eperfil', [
            'title' => 'Editar Perfil',
            'perfil' => $perfil
        ]);
    }

    // =========================================================
    // 🔄 PROCESO: ACTUALIZAR
    // =========================================================
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'strNombrePerfil' => 'required|string|unique:perfiles,strNombrePerfil,'.$id,
            'bitAdministrador' => 'nullable'
        ]);

        $perfil = Perfil::findOrFail($id);
        
        $perfil->update([
            'strNombrePerfil' => $request->strNombrePerfil,
            'bitAdministrador' => $request->bitAdministrador ? 1 : 0
        ]);

        return response()->json([
            'success' => true, 
            'mensaje' => 'Perfil actualizado correctamente'
        ]);
    }

    // =========================================================
    // 🔍 VISTA: DETALLE (vistasperfil/dperfil.blade.php)
    // =========================================================
    public function detalle($id)
    {
        $perfil = Perfil::find($id);

        if (!$perfil) {
            return redirect()->route('perfil.index')->with('error', 'El perfil no existe.');
        }

        // Traemos los permisos cruzados para mostrarlos en la ficha de inspección
        $permisosRelacionados = DB::table('permisos_perfil')
            ->join('modulos', 'permisos_perfil.idModulo', '=', 'modulos.id')
            ->where('permisos_perfil.idPerfil', $id)
            ->select(
                'modulos.strNombreModulo as modulo',
                'permisos_perfil.bitConsulta as v',
                'permisos_perfil.bitAgregar as c',
                'permisos_perfil.bitEditar as e',
                'permisos_perfil.bitEliminar as d',
                'permisos_perfil.bitDetalle as l'
            )
            ->get();

        return view('modules.vistasperfil.dperfil', [
            'perfil' => $perfil,
            'permisosReales' => $permisosRelacionados
        ]);
    }

    // =========================================================
    // 🗑️ ELIMINAR (API)
    // =========================================================
    public function eliminar($id)
    {
        // Evitar que eliminen el perfil ID 1 (usualmente el Admin Raíz)
        if($id == 1) {
            return response()->json(['success' => false, 'mensaje' => 'No se puede eliminar el perfil raíz del sistema.'], 403);
        }

        $perfil = Perfil::findOrFail($id);
        $perfil->delete();
        return response()->json(['success' => true, 'mensaje' => 'Perfil eliminado']);
    }
}