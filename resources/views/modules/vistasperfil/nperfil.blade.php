@extends('layouts.app')

@section('title', 'Nuevo Perfil')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-3)]">Seguridad</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('perfil.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Perfiles</a>
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

/* Status pill específico para el dropdown de perfil */
.status-pill { padding: 6px 14px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 8px; width: max-content;}
.status-pill.no { background: var(--surface-4); color: var(--text-3); border: 1px solid transparent; }
.status-pill.yes { background: var(--neon-muted); color: var(--neon); border: 1px solid var(--neon-border); }
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">

        {{-- Header Consistente --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center shadow-lg text-white">
                <i class="fas fa-shield-halved text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Nuevo Perfil de Acceso</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Configuración inicial de privilegios para un nuevo rol en el sistema.</p>
            </div>
        </div>

        <form id="formNuevoPerfil" action="javascript:void(0);">
            
            {{-- BLOQUE 1: INFORMACIÓN DEL PERFIL --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div>
                        <h3 class="block-title">Información Principal</h3>
                        <p class="block-subtitle">Define el nombre y el nivel de acceso base.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Nombre del Perfil <span class="text-[var(--neon)]">*</span></label>
                        {{-- 🛡️ Límite HTML de 100 caracteres --}}
                        <input type="text" id="strNombrePerfil" required maxlength="100" placeholder="Ej. Analista de Riesgos" class="input-premium" autocomplete="off" autofocus>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Tipo de Cuenta <span class="text-[var(--neon)]">*</span></label>
                        <div class="flex flex-col gap-3">
                            <select id="bitAdministrador" class="input-premium cursor-pointer">
                                <option value="0" selected>ESTÁNDAR</option>
                                <option value="1">SÚPER ADMIN</option>
                            </select>
                            <span id="adminStatusBadge" class="status-pill no">
                                <i class="fas fa-user"></i> Acceso Limitado
                            </span>
                        </div>
                    </div>
                </div>

                <div class="pl-11 mt-6">
                    <div class="bg-[var(--surface-1)] p-3 rounded-lg border border-blue-500/20 flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                        <p class="text-[10px] text-[var(--text-3)] leading-relaxed">
                            Los perfiles <strong>ESTÁNDAR</strong> inician sin permisos por defecto. Después de crear el perfil, deberás ir a la Matriz de Permisos para configurar a qué módulos tendrá acceso. Los <strong>SÚPER ADMINS</strong> tienen acceso total automático e irrebocable.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer de Acciones --}}
            <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6">
                <a href="{{ route('perfil.index') }}" class="text-xs font-bold text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar y volver
                </a>
                <button type="submit" id="btnGuardar" class="btn-primary flex items-center gap-3 py-3 px-10 rounded-xl font-bold shadow-xl shadow-neon-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-cloud-arrow-up"></i> Crear Perfil
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formNuevoPerfil');
    const selectAdmin = document.getElementById('bitAdministrador');
    const badge = document.getElementById('adminStatusBadge');
    const btnGuardar = document.getElementById('btnGuardar');

    // Cambiar la "pill" visual según el select
    selectAdmin.addEventListener('change', (e) => {
        const esAdmin = e.target.value === '1';
        badge.className = `status-pill ${esAdmin ? 'yes' : 'no'}`;
        badge.innerHTML = `<i class="fas ${esAdmin ? 'fa-bolt' : 'fa-user'}"></i> ${esAdmin ? 'Súper Usuario' : 'Acceso Limitado'}`;
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const nombre = document.getElementById('strNombrePerfil').value.trim();
        if(!nombre) return;

        btnGuardar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Creando...';
        btnGuardar.disabled = true;

        try {
            const res = await fetch('/api/perfiles', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    strNombrePerfil: nombre, 
                    bitAdministrador: selectAdmin.value === '1' 
                })
            });
            
            const data = await res.json();
            
            if (res.ok && data.success) {
                if(window.showToast) window.showToast('Perfil creado con éxito', 'success');
                setTimeout(() => window.location.href = "{{ route('perfil.index') }}", 1000);
            } else {
                // 🛡️ TRADUCTOR DE ERRORES DE LARAVEL A HUMANO
                let errorMsg = data.message || data.mensaje || 'Error al crear el perfil.';
                if (data.errors) {
                    const primerError = Object.values(data.errors)[0][0];
                    if (primerError.includes('already been taken')) {
                        errorMsg = 'Este nombre de perfil ya está en uso. Elige uno diferente.';
                    } else {
                        errorMsg = primerError; 
                    }
                } else if ((data.message || data.mensaje || '').includes('already been taken')) {
                    errorMsg = 'Este nombre de perfil ya está en uso. Elige uno diferente.';
                }
                throw new Error(errorMsg);
            }
        } catch (err) {
            if(window.showToast) window.showToast(err.message, 'error');
            btnGuardar.innerHTML = '<i class="fas fa-cloud-arrow-up"></i> Crear Perfil';
            btnGuardar.disabled = false;
        }
    });
});
</script>
@endsection