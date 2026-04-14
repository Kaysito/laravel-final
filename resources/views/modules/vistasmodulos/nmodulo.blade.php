@extends('layouts.app')

@section('title', 'Nuevo Módulo')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-3)]">Seguridad</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('modulo.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Módulos</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Crear Nuevo</span>
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

    /* Estética para el datalist */
    input::-webkit-calendar-picker-indicator { display: none !important; }
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">

        {{-- Header Consistente --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center shadow-lg text-white">
                <i class="fas fa-hammer text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Constructor de Módulos</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Añade nuevas funcionalidades y automatiza su aparición en el menú.</p>
            </div>
        </div>

        <form id="formNuevoModulo" action="javascript:void(0);">
            
            {{-- BLOQUE 1: IDENTIDAD --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)] border border-[var(--surface-4)]">
                        <i class="fas fa-id-card-clip"></i>
                    </div>
                    <div>
                        <h3 class="block-title">Identidad del Módulo</h3>
                        <p class="block-subtitle">Nombre técnico para la base de datos y la matriz de permisos.</p>
                    </div>
                </div>
                
                <div class="pl-11">
                    <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase tracking-wider">Nombre del Módulo <span class="text-[var(--neon)]">*</span></label>
                    <input type="text" id="strNombreModulo" required maxlength="70" placeholder="Ej. Reportes de Ventas" class="input-premium font-medium" autocomplete="off" autofocus>
                    
                    <div class="mt-4 bg-[var(--surface-1)] p-3 rounded-lg border border-blue-500/20 flex items-start gap-3 shadow-inner">
                        <i class="fas fa-robot text-blue-400 mt-0.5"></i>
                        <p class="text-[10px] text-[var(--text-3)] leading-relaxed">
                            Al registrar, se crearán automáticamente los permisos de <strong>Consulta, Agregar, Editar, Eliminar y Detalle</strong> para este módulo en todos los perfiles existentes.
                        </p>
                    </div>
                </div>
            </div>

            {{-- BLOQUE 2: UBICACIÓN Y APARIENCIA --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)] border border-[var(--surface-4)]">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div>
                        <h3 class="block-title">Ubicación y Apariencia</h3>
                        <p class="block-subtitle">Configura en qué carpeta del menú aparecerá y con qué icono.</p>
                    </div>
                </div>
                
                <div class="pl-11 grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Carpeta Dinámica --}}
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase tracking-wider">Carpeta / Grupo</label>
                        <input list="listaGrupos" type="text" id="strGrupo" maxlength="100" placeholder="Escribe o selecciona..." class="input-premium" autocomplete="off">
                        
                        <datalist id="listaGrupos">
                            @if(!empty($grupos))
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo }}"></option>
                                @endforeach
                            @endif
                        </datalist>
                        <p class="text-[10px] text-[var(--text-3)] mt-1.5 italic">Si se deja vacío, aparecerá en la raíz del Sidebar.</p>
                    </div>

                    {{-- Icono con Preview --}}
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase tracking-wider">Icono (FontAwesome)</label>
                        <div class="relative">
                            <i id="iconPreview" class="fas fa-icons absolute left-3 top-1/2 -translate-y-1/2 text-[var(--neon)] text-sm transition-all duration-300"></i>
                            <input type="text" id="strIcono" maxlength="100" placeholder="fas fa-cube" class="input-premium pl-9 font-mono text-xs" autocomplete="off">
                        </div>
                        <p class="text-[10px] text-[var(--text-3)] mt-1.5">Ej: <code>fas fa-user-gear</code></p>
                    </div>

                    {{-- Ruta Técnica --}}
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase tracking-wider">Nombre de la Ruta (Laravel Route)</label>
                        <div class="relative">
                            <i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="text" id="strRuta" maxlength="100" placeholder="Ej. ventas.index" class="input-premium pl-9 font-mono text-xs text-blue-500" autocomplete="off">
                        </div>
                        <p class="text-[10px] text-[var(--text-3)] mt-2 leading-relaxed">
                            <i class="fas fa-circle-exclamation mr-1 text-yellow-500"></i> 
                            Si la ruta no existe en <code>web.php</code>, el sistema activará automáticamente la <strong>Vista de Relleno (Modo Construcción)</strong>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6 mt-4">
                <a href="{{ route('modulo.index') }}" class="text-xs font-bold text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit" id="btnGuardar" class="btn-primary flex items-center gap-3 py-3 px-10 rounded-xl font-bold shadow-xl shadow-neon-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-save"></i> Registrar Módulo
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formNuevoModulo');
    const inputIcono = document.getElementById('strIcono');
    const iconPreview = document.getElementById('iconPreview');
    const btnGuardar = document.getElementById('btnGuardar');

    // Live Preview
    inputIcono.addEventListener('input', (e) => {
        const val = e.target.value.trim();
        iconPreview.className = val ? `${val} absolute left-3 top-1/2 -translate-y-1/2 text-[var(--neon)] text-sm` : 'fas fa-icons absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs';
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        btnGuardar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Guardando...';
        btnGuardar.disabled = true;

        const payload = {
            strNombreModulo: document.getElementById('strNombreModulo').value.trim(),
            strGrupo: document.getElementById('strGrupo').value.trim() || null,
            strIcono: document.getElementById('strIcono').value.trim() || null,
            strRuta: document.getElementById('strRuta').value.trim() || null
        };

        try {
            const res = await fetch('/api/modulos', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            const data = await res.json();
            
            if (res.ok && data.success) {
                if(window.showToast) window.showToast('✅ Módulo integrado con éxito', 'success');
                setTimeout(() => window.location.href = "{{ route('modulo.index') }}", 1000);
            } else {
                throw new Error(data.message || 'Error en el servidor');
            }
        } catch (err) {
            if(window.showToast) window.showToast(err.message, 'error');
            btnGuardar.innerHTML = '<i class="fas fa-save"></i> Registrar Módulo';
            btnGuardar.disabled = false;
        }
    });
});
</script>
@endsection