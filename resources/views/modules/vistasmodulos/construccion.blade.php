@extends('layouts.app')

@section('title', $modulo->strNombreModulo)

@section('breadcrumb')
    <span class="text-[var(--text-3)]">{{ $modulo->strGrupo ?? 'Módulos' }}</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">{{ $modulo->strNombreModulo }}</span>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">
    
    {{-- Header del Módulo Dinámico --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-1)] tracking-tight">
                <i class="{{ $modulo->strIcono ?? 'fas fa-cube' }} mr-2 text-[var(--neon)]"></i> 
                {{ $modulo->strNombreModulo }}
            </h1>
            <p class="text-xs sm:text-sm text-[var(--text-3)] mt-0.5">Vista generada dinámicamente. Conectada a la Matriz RBAC.</p>
        </div>
        
        {{-- Botón Agregar (Protegido) --}}
        <button id="btnAgregar" class="btn-primary flex items-center gap-2 px-4 py-2 text-sm shadow-lg">
            <i class="fas fa-plus text-xs"></i> <span>Nuevo Registro</span>
        </button>
    </div>

    {{-- Tabla de Datos de Relleno --}}
    <div class="card shadow-sm border border-[var(--surface-4)] overflow-hidden">
        <div class="overflow-x-auto bg-[var(--surface-1)]">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--surface-2)]">
                    <tr>
                        <th class="py-3 px-4 text-[10px] font-bold text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-10">#</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Dato de Prueba</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] text-center">Estado</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] text-right w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--surface-4)]">
                    @for($i = 1; $i <= 3; $i++)
                    <tr class="hover:bg-[var(--surface-3)] transition-colors">
                        <td class="py-3 px-4 text-xs font-mono text-[var(--text-3)]">00{{ $i }}</td>
                        <td class="py-3 px-4 text-[var(--text-1)] font-medium">Registro de prueba #{{ $i }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-500">Activo</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex justify-end gap-1.5">
                                <button class="btn-detalle action-btn tooltip" data-tip="Ver detalle"><i class="fas fa-eye text-blue-400"></i></button>
                                <button class="btn-editar action-btn tooltip" data-tip="Editar"><i class="fas fa-pen text-yellow-500"></i></button>
                                <button class="btn-eliminar action-btn tooltip" data-tip="Eliminar"><i class="fas fa-trash text-red-500"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Extraemos el nombre real del módulo actual inyectado por el backend
    const moduloActual = "{{ $modulo->strNombreModulo }}";

    // 2. Evaluamos los permisos desde el LocalStorage
    const puedeAgregar  = window.tienePermiso(moduloActual, 'bitAgregar');
    const puedeEditar   = window.tienePermiso(moduloActual, 'bitEditar');
    const puedeEliminar = window.tienePermiso(moduloActual, 'bitEliminar');
    const puedeDetalle  = window.tienePermiso(moduloActual, 'bitDetalle');

    // 3. Aplicamos bloqueos visuales a los botones
    if (!puedeAgregar) document.getElementById('btnAgregar').style.display = 'none';

    document.querySelectorAll('.btn-editar').forEach(btn => {
        if (!puedeEditar) { btn.style.opacity = '0.2'; btn.style.cursor = 'not-allowed'; btn.dataset.tip = 'Bloqueado'; }
    });

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        if (!puedeEliminar) { btn.style.opacity = '0.2'; btn.style.cursor = 'not-allowed'; btn.dataset.tip = 'Bloqueado'; }
    });

    document.querySelectorAll('.btn-detalle').forEach(btn => {
        if (!puedeDetalle) { btn.style.opacity = '0.2'; btn.style.cursor = 'not-allowed'; btn.dataset.tip = 'Bloqueado'; }
    });
});
</script>
@endsection