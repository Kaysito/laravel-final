<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modulo;
use App\Models\Perfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ModuloController extends Controller
{
    protected $cacheKey = 'sidebar_modulos_menu';

    // =========================================================
    // 🧠 MOTOR DE CARPETAS (CORREGIDO)
    // Extrae SOLO los nombres de las carpetas o los padres reales
    // =========================================================
    private function obtenerCarpetas() {
        return Modulo::all()->map(function($m) {
            $g = trim($m->strGrupo ?? '');
            // Si el grupo no está vacío, ese es el Padre. 
            // Si está vacío, el propio nombre del módulo es el Padre.
            return $g !== '' ? $g : trim($m->strNombreModulo);
        })->unique()->filter()->sort()->values()->toArray();
    }

    // =========================================================
    // 📖 LISTADO PRINCIPAL (Paginación Segura en Memoria)
    // =========================================================
    public function listar(Request $request)
    {
        $buscar = trim($request->query('buscar'));
        $modulosBase = Modulo::all();

        // 1. Filtro de Búsqueda
        if (!empty($buscar)) {
            $term = strtolower($buscar);
            $modulosBase = $modulosBase->filter(function($m) use ($term) {
                $nombre = strtolower($m->strNombreModulo);
                $grupo = strtolower($m->strGrupo ?? '');
                return str_contains($nombre, $term) || str_contains($grupo, $term);
            });
        }

        // 2. Extraer a los Padres Reales
        $gruposUnicos = $modulosBase->map(function ($m) {
            $g = trim($m->strGrupo ?? '');
            return !empty($g) ? $g : trim($m->strNombreModulo);
        })->unique()->sort()->values();

        // 3. Paginación Manual (5 Padres por página)
        $page = (int) $request->query('page', 1);
        $perPage = 5;
        $totalGrupos = $gruposUnicos->count();
        $gruposPagina = $gruposUnicos->slice(($page - 1) * $perPage, $perPage)->toArray();

        // 4. Traer a toda la familia
        $familia = Modulo::all()->filter(function($m) use ($gruposPagina) {
            $g = trim($m->strGrupo ?? '');
            $grupoVirtual = !empty($g) ? $g : trim($m->strNombreModulo);
            return in_array($grupoVirtual, $gruposPagina);
        })->sortBy(function($m) {
            $g = trim($m->strGrupo ?? '');
            $grupoVirtual = !empty($g) ? $g : trim($m->strNombreModulo);
            
            // Prioridad: 0 si es el Padre, 1 si es un Hijo
            $isParent = ($g === '' || strtolower($g) === strtolower(trim($m->strNombreModulo))) ? 0 : 1;
            
            return $grupoVirtual . '-' . $isParent . '-' . str_pad($m->id, 5, '0', STR_PAD_LEFT);
        })->values();

        return response()->json([
            'current_page' => $page,
            'data'         => $familia,
            'from'         => $totalGrupos > 0 ? (($page - 1) * $perPage) + 1 : null,
            'last_page'    => ceil($totalGrupos / $perPage) ?: 1,
            'to'           => min($page * $perPage, $totalGrupos),
            'total'        => $totalGrupos,
        ]);
    }

    // =========================================================
    // ➕ VISTA: NUEVO MÓDULO 
    // =========================================================
    public function crear()
    {
        // Llamamos al motor de carpetas que ya no jala a los hijos
        $grupos = $this->obtenerCarpetas();

        return view('modules.vistasmodulos.nmodulo', [
            'title' => 'Nuevo Módulo',
            'grupos' => $grupos
        ]);
    }

    // =========================================================
    // 💾 PROCESO: GUARDAR (A PRUEBA DE BALAS)
    // =========================================================
    public function guardar(Request $request)
    {
        $request->validate([
            'strNombreModulo' => 'required|string|max:100|unique:modulos,strNombreModulo',
            'strGrupo'        => 'nullable|string|max:100',
            'strRuta'         => 'nullable|string|max:100',
            'strIcono'        => 'nullable|string|max:100'
        ]);

        // Guardado forzado burlando el Mass Assignment de Laravel
        $modulo = new Modulo();
        $modulo->strNombreModulo = $request->strNombreModulo;
        $modulo->strGrupo = $request->strGrupo;
        $modulo->strRuta = $request->strRuta;
        $modulo->strIcono = $request->strIcono ?? 'fas fa-cube';
        $modulo->save();

        $perfiles = Perfil::all();
        $permisosInyectar = [];
        $ahora = now();

        foreach ($perfiles as $perfil) {
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

        if (!empty($permisosInyectar)) {
            DB::table('permisos_perfil')->insert($permisosInyectar);
        }

        Cache::forget($this->cacheKey);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'mensaje' => '✅ Módulo registrado e integrado con éxito.',
                'id' => $modulo->id
            ]);
        }

        return redirect()->route('modulo.index')->with('success', 'Módulo registrado');
    }

    // =========================================================
    // ✏️ VISTA: EDITAR MÓDULO 
    // =========================================================
    public function editar($id)
    {
        $modulo = Modulo::findOrFail($id);
        $grupos = $this->obtenerCarpetas();

        return view('modules.vistasmodulos.emodulos', [
            'title' => 'Editar Módulo',
            'modulo' => $modulo,
            'grupos' => $grupos
        ]);
    }

    // =========================================================
    // 🔄 PROCESO: ACTUALIZAR (A PRUEBA DE BALAS)
    // =========================================================
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'strNombreModulo' => 'required|string|max:100|unique:modulos,strNombreModulo,' . $id,
            'strGrupo'        => 'nullable|string|max:100',
            'strRuta'         => 'nullable|string|max:100',
            'strIcono'        => 'nullable|string|max:100'
        ]);

        $modulo = Modulo::findOrFail($id);
        
        // Actualizado forzado burlando el Mass Assignment de Laravel
        $modulo->strNombreModulo = $request->strNombreModulo;
        $modulo->strGrupo = $request->strGrupo;
        $modulo->strRuta = $request->strRuta;
        $modulo->strIcono = empty($request->strIcono) ? 'fas fa-cube' : $request->strIcono;
        $modulo->save();

        Cache::forget($this->cacheKey);

        return response()->json([
            'success' => true,
            'mensaje' => 'Módulo actualizado correctamente'
        ]);
    }

    // =========================================================
    // 🔍 VISTA: DETALLE
    // =========================================================
    public function detalle($id)
    {
        $modulo = Modulo::findOrFail($id);

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
    // 🗑️ ELIMINAR 
    // =========================================================
    public function eliminar($id)
    {
        $modulo = Modulo::find($id);
        if ($modulo) {
            $modulo->delete();
            Cache::forget($this->cacheKey);
            return response()->json(['success' => true, 'mensaje' => 'Módulo eliminado']);
        }
        return response()->json(['success' => false, 'message' => 'No encontrado'], 404);
    }

    // =========================================================
    // 🚧 VISTA: CONSTRUCCIÓN (Comodín)
    // =========================================================
    public function construccion($id)
    {
        $modulo = Modulo::findOrFail($id);
        return view('modules.vistasmodulos.construccion', [
            'modulo' => $modulo
        ]);
    }
}