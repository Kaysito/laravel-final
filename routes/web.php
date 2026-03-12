<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\AuthController;

// 1. Pantalla de Login
Route::get('/', function () { 
    return view('login'); 
})->name('login');

// 2. Proceso de Login
Route::post('/login', [AuthController::class, 'login']);

// 3. Proceso de Logout (Apunta a la nueva función del controlador)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================
// 🛡️ ZONA SEGURA: Todo aquí adentro requiere Token JWT
// =========================================================
Route::middleware(['jwt.verify'])->group(function () {
    
    // Dashboard Central
    Route::get('/home', function () { 
        return view('home'); 
    })->name('home');

    // ── Módulo: Seguridad ──
    Route::get('/perfiles', function () { 
        return view('modules.perfil', ['title' => 'Perfil']); 
    })->name('perfil.index');

    Route::get('/modulos', function () { 
        return view('modules.modulo', ['title' => 'Módulo']); 
    })->name('modulo.index');

    Route::get('/permisos', function () { 
        return view('modules.permisos', ['title' => 'Permisos-Perfil']); 
    })->name('permisos.index');

    Route::get('/usuarios', function () { 
        // Asumiendo que guardaste tu vista de usuarios como usuarios.blade.php
        return view('modules.usuarios', ['title' => 'Usuario']); 
    })->name('usuarios.index');

    // ── Menú: Principal 1 (Usando tu blank.blade.php estático) ──
    Route::get('/principal-1-1', function () { 
        return view('modules.blank', [
            'title' => 'Principal 1.1', 
            'breadcrumb' => '<span class="text-[var(--text-3)]">Principal 1</span><span class="breadcrumb-sep">/</span><span class="text-[var(--text-1)] font-medium">Principal 1.1</span>'
        ]); 
    })->name('p1.1.index');

    Route::get('/principal-1-2', function () { 
        return view('modules.blank', [
            'title' => 'Principal 1.2',
            'breadcrumb' => '<span class="text-[var(--text-3)]">Principal 1</span><span class="breadcrumb-sep">/</span><span class="text-[var(--text-1)] font-medium">Principal 1.2</span>'
        ]); 
    })->name('p1.2.index');

    // ── Menú: Principal 2 (Usando tu blank.blade.php estático) ──
    Route::get('/principal-2-1', function () { 
        return view('modules.blank', [
            'title' => 'Principal 2.1',
            'breadcrumb' => '<span class="text-[var(--text-3)]">Principal 2</span><span class="breadcrumb-sep">/</span><span class="text-[var(--text-1)] font-medium">Principal 2.1</span>'
        ]); 
    })->name('p2.1.index');

    Route::get('/principal-2-2', function () { 
        return view('modules.blank', [
            'title' => 'Principal 2.2',
            'breadcrumb' => '<span class="text-[var(--text-3)]">Principal 2</span><span class="breadcrumb-sep">/</span><span class="text-[var(--text-1)] font-medium">Principal 2.2</span>'
        ]); 
    })->name('p2.2.index');

});