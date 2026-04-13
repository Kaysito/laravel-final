@extends('layouts.app')

@section('title', 'Editar Módulo')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('modulo.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Módulos</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Modificar</span>
@endsection

@section('styles')
<style>
.input-premium {
    width: 100%; padding: 0.65rem 1rem;
    background-color: var(--surface-1); border: 1px solid var(--surface-4);
    color: var(--text-1); border-radius: 8px; font-size: 13px;
    transition: all 0.2s ease;
}
.input-premium:focus {
    outline: none; border-color: var(--neon-border);
    box-shadow: 0 0 0 3px rgba(230,55,87,0.1);
}

.stacked-block {
    background: var(--surface-2); border: 1px solid var(--surface-4);
    border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.block-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
.block-title { font-size: 0.9rem; font-weight: 700; color: var(--text-1); display: flex; align-items: center; gap: 0.5rem; }
.block-subtitle { font-size: 0.7rem; color: var(--text-3); margin-top: 0.15rem; }

.date-info { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-3); font-weight: 500; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">

        {{-- Header Consistente --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center shadow-lg">
                <i class="fas fa-pen-to-square text-xl text-blue-400"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Editar Módulo</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Actualizando registro de módulo <strong>#{{ str_pad($modulo->id, 3, '0', STR_PAD_LEFT) }}</strong></p>
            </div>
        </div>

        <form id="formEditarModulo" action="javascript:void(0);">
            <input type="hidden" id="moduloId" value="{{ $modulo->id }}">
            
            {{-- BLOQUE 1: INFORMACIÓN DEL MÓDULO --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <h3 class="block-title">Identificador del Módulo</h3>
                        <p class="block-subtitle">Modifica el nombre visible en la matriz de permisos.</p>
                    </div>
                </div>
                
                <div class="pl-11">
                    <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Nombre del Módulo <span class="text-[var(--neon)]">*</span></label>
                    <input type="text" id="strNombreModulo" class="input-premium" value="{{ $modulo->strNombreModulo }}" required maxlength="70" autofocus autocomplete="off">
                    
                    <div class="mt-4 bg-[var(--surface-1)] p-3 rounded-lg border border-blue-500/20 flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                        <p class="text-[10px] text-[var(--text-3)] leading-relaxed">
                            Cualquier cambio que realices aquí se verá reflejado inmediatamente en todos los perfiles y menús laterales del sistema.
                        </p>
                    </div>
                </div>
            </div>

            {{-- BLOQUE 2: CONFIGURACIÓN VISUAL (MENÚ DINÁMICO) --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]">
                        <i class="fas fa-bars-staggered"></i>
                    </div>
                    <div>
                        <h3 class="block-title">Ubicación en el Menú (Slider)</h3>
                        <p class="block-subtitle">Modifica la carpeta, el icono o la ruta donde vive este módulo.</p>
                    </div>
                </div>
                
                <div class="pl-11 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Carpeta / Grupo</label>
                        <input type="text" id="strGrupo" value="{{ $modulo->strGrupo }}" maxlength="100" placeholder="Ej. Finanzas, Seguridad..." class="input-premium" autocomplete="off">
                        <p class="text-[10px] text-[var(--text-3)] mt-1">Déjalo en blanco para dejar el módulo suelto en el menú.</p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Icono (FontAwesome)</label>
                        <div class="relative">
                            <i class="fas fa-icons absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="text" id="strIcono" value="{{ $modulo->strIcono }}" maxlength="100" placeholder="fas fa-chart-pie" class="input-premium pl-8" autocomplete="off">
                        </div>
                        <p class="text-[10px] text-[var(--text-3)] mt-1">Si lo dejas vacío, usará el cubo por defecto.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Ruta del Sistema (Laravel Route)</label>
                        <div class="relative">
                            <i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="text" id="strRuta" value="{{ $modulo->strRuta }}" maxlength="100" placeholder="Ej. inventarios.index" class="input-premium pl-8 font-mono text-xs" autocomplete="off">
                        </div>
                        <p class="text-[10px] text-[var(--text-3)] mt-1">Nombre exacto de la ruta registrada en web.php</p>
                    </div>
                </div>
            </div>

            {{-- BLOQUE 3: AUDITORÍA --}}
            <div class="stacked-block bg-[var(--surface-1)] border-dashed">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-2)] flex items-center justify-center text-[var(--text-3)] border border-[var(--surface-4)]">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="block-title">Historial del Registro</h3>
                </div>
                <div class="flex flex-wrap gap-6 pl-11">
                    <div class="date-info">
                        <i class="far fa-calendar-plus opacity-40 text-lg"></i>
                        <div>
                            <span class="block text-[9px] uppercase tracking-widest opacity-60">Creado el</span>
                            <span>{{ $modulo->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="date-info">
                        <i class="far fa-pen-to-square opacity-40 text-lg"></i>
                        <div>
                            <span class="block text-[9px] uppercase tracking-widest opacity-60">Última modificación</span>
                            <span>{{ $modulo->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer de Acciones --}}
            <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6">
                <a href="{{ route('modulo.index') }}" class="text-xs font-bold text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar y volver
                </a>
                <button type="submit" id="btnActualizar" class="btn-primary flex items-center gap-3 py-3 px-10 rounded-xl font-bold shadow-xl shadow-neon-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-floppy-disk"></i> Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEditarModulo');
    const inputNombre = document.getElementById('strNombreModulo');
    const inputGrupo  = document.getElementById('strGrupo');
    const inputIcono  = document.getElementById('strIcono');
    const inputRuta   = document.getElementById('strRuta');
    const btnActualizar = document.getElementById('btnActualizar');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const id = document.getElementById('moduloId').value;
        const nombre = inputNombre.value.trim();

        if(!nombre) return;

        btnActualizar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Guardando...';
        btnActualizar.disabled = true;

        try {
            const res = await fetch(`/api/modulos/${id}`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    strNombreModulo: nombre,
                    strGrupo: inputGrupo.value.trim() || null,
                    strIcono: inputIcono.value.trim() || null,
                    strRuta: inputRuta.value.trim() || null
                })
            });
            
            const data = await res.json();
            
            if (res.ok && data.success) {
                if(window.showToast) window.showToast('Cambios aplicados con éxito', 'success');
                setTimeout(() => window.location.href = "{{ route('modulo.index') }}", 1000);
            } else {
                // 🛡️ TRADUCTOR DE ERRORES DE LARAVEL A HUMANO
                let errorMsg = data.message || data.mensaje || 'Ocurrió un error al actualizar el módulo.';
                if (data.errors) {
                    const primerError = Object.values(data.errors)[0][0];
                    if (primerError.includes('already been taken')) {
                        errorMsg = 'Este nombre de módulo ya está en uso. Elige uno diferente.';
                    } else {
                        errorMsg = primerError; 
                    }
                } else if ((data.message || data.mensaje || '').includes('already been taken')) {
                    errorMsg = 'Este nombre de módulo ya está en uso. Elige uno diferente.';
                }
                throw new Error(errorMsg);
            }
        } catch (err) {
            if(window.showToast) window.showToast(err.message, 'error');
            btnActualizar.innerHTML = '<i class="fas fa-floppy-disk"></i> Guardar Cambios';
            btnActualizar.disabled = false;
        }
    });
});
</script>
@endsection