<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\PermisoPerfilController;

// 1. Pantalla de Login
Route::get('/', function () { 
    return view('login'); 
})->name('login');

// 2. Proceso de Login
Route::post('/login', [AuthController::class, 'login']);

// 3. Proceso de Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Magic Link de Verificación (Público)
Route::get('/verificar-cuenta/{id}/{codigo}', [UsuarioController::class, 'verificarCorreoLink'])->name('verificar.correo');


// =========================================================
// 🛡️ ZONA SEGURA: Todo aquí adentro requiere Token JWT
// y además verifica que no haya doble sesión ('single.session')
// =========================================================
Route::middleware(['jwt.verify', 'single.session'])->group(function () {
    
    // 👇 Dashboard Central 👇
    Route::get('/home', function () { 
        $data = [
            'totalUsuarios' => \App\Models\Usuario::count(),
            'usuariosActivos' => \App\Models\Usuario::where('idEstadoUsuario', 1)->count(),
            'usuariosInactivos' => \App\Models\Usuario::where('idEstadoUsuario', 0)->count(),
            'totalPerfiles' => \App\Models\Perfil::count(),
            'ultimosUsuarios' => \App\Models\Usuario::with('perfil')
                                ->orderBy('id', 'DESC')
                                ->take(5)
                                ->get()
        ];
        return view('home', $data); 
    })->name('home');

    // 👤 Mi Perfil (Usuario Autenticado)
    Route::get('/mi-perfil', [UsuarioController::class, 'miPerfil'])->name('miperfil');
    Route::put('/mi-perfil/guardar', [UsuarioController::class, 'actualizarMiPerfil'])->name('miperfil.guardar');
    
    // Verificación SMS (Twilio)
    Route::post('/mi-perfil/enviar-sms', [UsuarioController::class, 'enviarSmsVerificacion']);
    Route::post('/mi-perfil/verificar-sms', [UsuarioController::class, 'verificarCodigoSms']);
    
    // 🔐 Verificación 2FA (Google Authenticator)
    Route::get('/mi-perfil/2fa/setup', [UsuarioController::class, 'setup2FA']);
    Route::post('/mi-perfil/2fa/verificar', [UsuarioController::class, 'verificar2FA']);


    // ── Módulo: Seguridad (Perfiles) ──
    Route::get('/perfiles', function () { return view('modules.perfil', ['title' => 'Perfiles']); })
        ->name('perfil.index')
        ->middleware('permiso:Perfiles,bitConsulta'); // 👈 Actualizado a plural

    Route::get('/perfiles/nuevo', [PerfilController::class, 'crear'])
        ->name('perfil.crear')
        ->middleware('permiso:Perfiles,bitAgregar');

    Route::get('/perfiles/{id}/editar', [PerfilController::class, 'editar'])
        ->name('perfil.editar')
        ->middleware('permiso:Perfiles,bitEditar');

    Route::get('/perfiles/{id}/detalle', [PerfilController::class, 'detalle'])
        ->name('perfil.detalle')
        ->middleware('permiso:Perfiles,bitDetalle');

    Route::get('/api/perfiles', [PerfilController::class, 'listar']);
    Route::post('/api/perfiles', [PerfilController::class, 'guardar'])->middleware('permiso:Perfiles,bitAgregar');
    Route::put('/api/perfiles/{id}', [PerfilController::class, 'actualizar'])->middleware('permiso:Perfiles,bitEditar'); 
    Route::delete('/api/perfiles/{id}', [PerfilController::class, 'eliminar'])->middleware('permiso:Perfiles,bitEliminar');


    // ── Módulo: Módulos del Sistema ──
    Route::get('/modulos', function () { return view('modules.modulo', ['title' => 'Módulos']); })
        ->name('modulo.index')
        ->middleware('permiso:Modulos,bitConsulta'); // 👈 Actualizado a plural

    Route::get('/modulos/nuevo', [ModuloController::class, 'crear'])
        ->name('modulo.crear')
        ->middleware('permiso:Modulos,bitAgregar');

    Route::get('/modulos/{id}/editar', [ModuloController::class, 'editar'])
        ->name('modulo.editar')
        ->middleware('permiso:Modulos,bitEditar');

    Route::get('/modulos/{id}/detalle', [ModuloController::class, 'detalle'])
        ->name('modulo.detalle')
        ->middleware('permiso:Modulos,bitDetalle');

    Route::get('/api/modulos', [ModuloController::class, 'listar']);
    Route::post('/api/modulos', [ModuloController::class, 'guardar'])->middleware('permiso:Modulos,bitAgregar');
    Route::put('/api/modulos/{id}', [ModuloController::class, 'actualizar'])->middleware('permiso:Modulos,bitEditar');
    Route::delete('/api/modulos/{id}', [ModuloController::class, 'eliminar'])->middleware('permiso:Modulos,bitEliminar');


    // ── Módulo: Permisos (Matriz RBAC) ──
    Route::get('/permisos', function () { return view('modules.permisos', ['title' => 'Permisos-Perfil']); })
        ->name('permisos.index')
        ->middleware('permiso:Permisos-Perfil,bitConsulta'); // 👈 Actualizado al nombre exacto

    Route::get('/api/permisos', [PermisoPerfilController::class, 'listar']);
    Route::get('/api/permisos/catalogos', [PermisoPerfilController::class, 'catalogos']);
    Route::post('/api/permisos', [PermisoPerfilController::class, 'guardar'])->middleware('permiso:Permisos-Perfil,bitEditar');


    // ── Módulo: Usuarios ──
    Route::get('/usuarios', function () { return view('modules.usuarios', ['title' => 'Gestión de Usuarios']); })
        ->name('usuarios.index')
        ->middleware('permiso:Usuarios,bitConsulta');
    
    // Vistas
    Route::get('/usuarios/nuevo', [UsuarioController::class, 'crear'])
        ->name('usuarios.crear')
        ->middleware('permiso:Usuarios,bitAgregar'); 
    
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'editar'])
        ->name('usuarios.editar')
        ->middleware('permiso:Usuarios,bitEditar');

    Route::get('/usuarios/{id}/detalle', [UsuarioController::class, 'detalle'])
        ->name('usuarios.detalle')
        ->middleware('permiso:Usuarios,bitDetalle');

    // API Acciones Usuarios
    Route::post('/usuarios/guardar', [UsuarioController::class, 'guardar'])
        ->name('usuarios.guardar')
        ->middleware('permiso:Usuarios,bitAgregar'); 
        
    Route::put('/usuarios/{id}/actualizar', [UsuarioController::class, 'actualizarDesdeDetalle'])
        ->name('usuarios.actualizar')
        ->middleware('permiso:Usuarios,bitEditar');
        
    Route::delete('/api/usuarios/{id}', [UsuarioController::class, 'eliminar'])
        ->name('usuarios.eliminar')
        ->middleware('permiso:Usuarios,bitEliminar');
        
    Route::get('/api/usuarios', [UsuarioController::class, 'listar'])
        ->name('usuarios.lista')
        ->middleware('permiso:Usuarios,bitConsulta');


    // ── Menú: Vistas Genéricas / Blank ──
    Route::get('/principal-1-1', function () { return view('modules.blank', ['title' => 'Principal 1.1']); })->name('p1.1.index');
    Route::get('/principal-1-2', function () { return view('modules.blank', ['title' => 'Principal 1.2']); })->name('p1.2.index');
    Route::get('/principal-2-1', function () { return view('modules.blank', ['title' => 'Principal 2.1']); })->name('p2.1.index');
    Route::get('/principal-2-2', function () { return view('modules.blank', ['title' => 'Principal 2.2']); })->name('p2.2.index');

    // ── NUEVO: Ruta Genérica para Módulos Creados Dinámicamente ──
    Route::get('/modulo-generico/{id}', [ModuloController::class, 'construccion'])->name('modulo.generico');

});