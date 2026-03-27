@extends('layouts.app')

@section('title', 'Detalle de Módulo')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('modulo.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Módulos</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Inspección</span>
@endsection

@section('styles')
<style>
/* ── Contenedor de Inspección ── */
.detail-container { background: var(--surface-2); border: 1px solid var(--surface-4); border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 40px -15px rgba(0,0,0,0.4); }

/* ── Estilos de Campos ── */
.info-group { margin-bottom: 2rem; }
.info-label { font-size: 0.65rem; font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-3); margin-bottom: 0.75rem; display: block; opacity: 0.8; }

/* ── Visualización de Datos ── */
.info-display { background: var(--surface-3); border: 1px solid var(--surface-4); border-radius: 12px; color: var(--text-1); padding: 0.85rem 1.25rem; width: 100%; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; min-height: 54px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }

/* ── Badge y Fechas ── */
.id-badge { background: var(--surface-4); color: var(--text-2); padding: 4px 10px; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: bold; }
.date-box { display: flex; align-items: center; gap: 10px; padding: 0.75rem; background: var(--surface-1); border-radius: 12px; border: 1px solid var(--surface-4); color: var(--text-2); font-size: 0.9rem; font-weight: 500; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">
    <div class="max-w-4xl mx-auto">

        <div class="detail-container">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-12 pb-6 border-b border-[var(--surface-4)]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[var(--surface-3)] flex items-center justify-center text-[var(--neon)] text-xl border border-[var(--surface-4)] shadow-inner">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-[var(--text-1)] tracking-tight">Ficha del Módulo</h2>
                        <p class="text-[10px] text-[var(--text-3)] font-mono uppercase tracking-[0.2em]">Modo de solo lectura</p>
                    </div>
                </div>
                <span class="id-badge">ID #{{ str_pad($modulo->id, 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                
                {{-- Nombre del Módulo --}}
                <div class="info-group md:col-span-2">
                    <label class="info-label">Nombre del Componente</label>
                    <div class="info-display">
                        {{ $modulo->strNombreModulo }}
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="info-group">
                    <label class="info-label">Registrado el</label>
                    <div class="date-box">
                        <i class="far fa-calendar-check text-[var(--neon)] opacity-70"></i>
                        {{ $modulo->created_at->format('d/m/Y, H:i:s') }}
                    </div>
                </div>

                <div class="info-group">
                    <label class="info-label">Última modificación</label>
                    <div class="date-box">
                        <i class="far fa-clock text-blue-400 opacity-70"></i>
                        {{ $modulo->updated_at->format('d/m/Y, H:i:s') }}
                    </div>
                </div>

            </div>

            {{-- Footer de Acciones --}}
            <div class="mt-16 pt-8 border-t border-[var(--surface-4)] flex items-center justify-between">
                <a href="{{ route('modulo.index') }}" class="btn-ghost flex items-center gap-2 px-6 py-3 text-sm font-bold border border-[var(--surface-4)] rounded-xl bg-[var(--surface-2)] transition-all hover:bg-[var(--surface-3)]">
                    <i class="fas fa-chevron-left text-[10px]"></i> Regresar al listado
                </a>
                
                <button id="btnEditarModulo" class="btn-primary px-10 py-3 rounded-xl font-bold flex items-center gap-3 shadow-xl shadow-neon-sm transition-all hover:scale-[1.02]">
                    <i class="fas fa-pen-to-square"></i> Editar Módulo
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const puedeEditar = window.tienePermiso ? window.tienePermiso('Modulo', 'bitEditar') : true;
    const btnEditar = document.getElementById('btnEditarModulo');

    // Deshabilitar botón si no tiene permiso
    if (btnEditar && !puedeEditar) {
        btnEditar.style.display = 'none';
    }

    if (btnEditar) {
        btnEditar.addEventListener('click', () => {
            // ✅ Redirección directa a la nueva vista de edición
            window.location.href = `/modulos/{{ $modulo->id }}/editar`;
        });
    }
});
</script>
@endsection