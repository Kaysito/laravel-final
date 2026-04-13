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
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">

        {{-- Header Consistente --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center shadow-lg text-white">
                <i class="fas fa-cube text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Nuevo Módulo</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Registro de nueva funcionalidad para la matriz y el menú.</p>
            </div>
        </div>

        <form id="formNuevoModulo" action="javascript:void(0);">
            
            {{-- BLOQUE 1: INFORMACIÓN DEL MÓDULO --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <h3 class="block-title">Identificador del Módulo</h3>
                        <p class="block-subtitle">Define el nombre visible en la matriz de permisos.</p>
                    </div>
                </div>
                
                <div class="pl-11">
                    <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Nombre del Módulo <span class="text-[var(--neon)]">*</span></label>
                    <input type="text" id="strNombreModulo" required maxlength="70" placeholder="Ej. Gestión de Inventarios" class="input-premium" autocomplete="off" autofocus>
                    
                    <div class="mt-4 bg-[var(--surface-1)] p-3 rounded-lg border border-blue-500/20 flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                        <p class="text-[10px] text-[var(--text-3)] leading-relaxed">
                            Asegúrate de que el nombre sea único. Este nombre se utilizará automáticamente en la <strong>Matriz de Permisos (RBAC)</strong>.
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
                        <p class="block-subtitle">Configura cómo se verá este módulo en el panel izquierdo.</p>
                    </div>
                </div>
                
                <div class="pl-11 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Carpeta / Grupo</label>
                        <input type="text" id="strGrupo" maxlength="100" placeholder="Ej. Finanzas, Seguridad..." class="input-premium" autocomplete="off">
                        <p class="text-[10px] text-[var(--text-3)] mt-1">Déjalo en blanco para dejar el módulo suelto en el menú.</p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Icono (FontAwesome)</label>
                        <div class="relative">
                            <i class="fas fa-icons absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="text" id="strIcono" maxlength="100" placeholder="fas fa-chart-pie" class="input-premium pl-8" autocomplete="off">
                        </div>
                        <p class="text-[10px] text-[var(--text-3)] mt-1">Por defecto: <i class="fas fa-cube mx-1"></i> fas fa-cube</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Ruta del Sistema (Laravel Route)</label>
                        <div class="relative">
                            <i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="text" id="strRuta" maxlength="100" placeholder="Ej. inventarios.index" class="input-premium pl-8 font-mono text-xs" autocomplete="off">
                        </div>
                        <p class="text-[10px] text-[var(--text-3)] mt-1">Nombre de la ruta configurada en web.php</p>
                    </div>
                </div>
            </div>

            {{-- Footer de Acciones --}}
            <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6">
                <a href="{{ route('modulo.index') }}" class="text-xs font-bold text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar y volver
                </a>
                <button type="submit" id="btnGuardar" class="btn-primary flex items-center gap-3 py-3 px-10 rounded-xl font-bold shadow-xl shadow-neon-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-cloud-arrow-up"></i> Registrar Módulo
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
    const inputNombre = document.getElementById('strNombreModulo');
    const inputGrupo  = document.getElementById('strGrupo');
    const inputIcono  = document.getElementById('strIcono');
    const inputRuta   = document.getElementById('strRuta');
    const btnGuardar  = document.getElementById('btnGuardar');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const nombre = inputNombre.value.trim();
        if(!nombre) return;

        btnGuardar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Registrando...';
        btnGuardar.disabled = true;

        try {
            const res = await fetch('/api/modulos', {
                method: 'POST',
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
                if(window.showToast) window.showToast('Módulo registrado y añadido al menú', 'success');
                setTimeout(() => window.location.href = "{{ route('modulo.index') }}", 1000);
            } else {
                // 🛡️ TRADUCTOR DE ERRORES DE LARAVEL A HUMANO
                let errorMsg = data.message || 'Ocurrió un error al registrar el módulo.';
                if (data.errors) {
                    const primerError = Object.values(data.errors)[0][0];
                    if (primerError.includes('already been taken')) {
                        errorMsg = 'Este nombre de módulo ya está registrado. Elige uno diferente.';
                    } else {
                        errorMsg = primerError; 
                    }
                } else if (data.message && data.message.includes('already been taken')) {
                    errorMsg = 'Este nombre de módulo ya está registrado. Elige uno diferente.';
                }
                throw new Error(errorMsg);
            }
        } catch (err) {
            if(window.showToast) window.showToast(err.message, 'error');
            btnGuardar.innerHTML = '<i class="fas fa-cloud-arrow-up"></i> Registrar Módulo';
            btnGuardar.disabled = false;
        }
    });
});
</script>
@endsection