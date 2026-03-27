@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('usuarios.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Usuarios</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Editar Perfil</span>
@endsection

@section('styles')
<style>
/* Ocultar Uploadcare nativo */
.uploadcare--widget_type_hidden .uploadcare--widget__button_type_open { display: none !important; }

.input-premium {
    width: 100%; padding: 0.65rem 1rem;
    background-color: var(--surface-1); border: 1px solid var(--surface-4);
    color: var(--text-1); border-radius: 8px; font-size: 13px;
    transition: all 0.2s ease;
}
.input-premium:focus:not(:disabled) {
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

/* Switch Neón */
.switch { position: relative; display: inline-block; width: 40px; height: 20px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background-color: var(--surface-4); transition: .3s; border-radius: 20px;
}
.slider:before {
    position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px;
    background-color: white; transition: .3s; border-radius: 50%;
}
input:checked + .slider { background-color: #22c55e; }
input:checked + .slider:before { transform: translateX(20px); }
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">
        
        {{-- Header Consistente --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center shadow-lg">
                <i class="fas fa-user-pen text-xl text-blue-400"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Editar Usuario</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Actualizando el perfil de <strong>{{ $usuario->strNombreUsuario }}</strong></p>
            </div>
        </div>

        <form id="formEditarUsuario" action="javascript:void(0);">
            <input type="hidden" id="usuarioId" value="{{ $usuario->id }}">

            {{-- BLOQUE 1: IMAGEN --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-camera"></i></div>
                    <div>
                        <h3 class="block-title">Foto de Perfil</h3>
                        <p class="block-subtitle">Personaliza la imagen del colaborador.</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 pl-11">
                    <div class="relative shrink-0">
                        @if($usuario->strImagen)
                            <img id="imgPreview" src="{{ $usuario->strImagen }}-/scale_crop/200x200/center/" class="w-20 h-20 rounded-full object-cover border-2 border-[var(--surface-4)] shadow-md">
                        @else
                            <div id="avatarFallback" class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-3xl font-bold text-white shadow-md">
                                {{ strtoupper(substr($usuario->strNombreUsuario, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <button type="button" id="btnUpload" class="px-4 py-2 bg-[var(--surface-3)] border border-[var(--surface-4)] hover:border-blue-500/50 text-[var(--text-1)] text-xs font-medium rounded-lg transition-all flex items-center gap-2">
                            <i class="fas fa-cloud-arrow-up"></i> Cambiar foto
                        </button>
                        <input type="hidden" role="uploadcare-uploader" name="strImagen" id="strImagen" value="{{ $usuario->strImagen }}" data-crop="1:1" data-images-only class="hidden-widget" />
                    </div>
                </div>
            </div>

            {{-- BLOQUE 2: INFORMACIÓN BÁSICA --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-address-card"></i></div>
                    <h3 class="block-title">Información Básica</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Nombre Completo <span class="text-[var(--neon)]">*</span></label>
                        {{-- 🛡️ Límite HTML de 70 caracteres --}}
                        <input type="text" id="strNombreUsuario" value="{{ $usuario->strNombreUsuario }}" required maxlength="70" class="input-premium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Número celular <span class="text-[var(--neon)]">*</span></label>
                        <input type="text" id="strNumeroCelular" value="{{ $usuario->strNumeroCelular }}" required maxlength="10" class="input-premium" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Correo Electrónico <span class="text-[var(--neon)]">*</span></label>
                        {{-- 🛡️ Límite HTML de 100 caracteres --}}
                        <input type="email" id="strCorreo" value="{{ $usuario->strCorreo }}" required maxlength="100" class="input-premium">
                    </div>
                </div>
            </div>

            {{-- BLOQUE 3: ACCESOS Y ESTADO --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-shield-halved"></i></div>
                    <h3 class="block-title">Configuración de Acceso</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Perfil (Rol)</label>
                        <select id="idPerfil" class="input-premium">
                            @foreach($perfiles as $p)
                                <option value="{{ $p->id }}" {{ $usuario->idPerfil == $p->id ? 'selected' : '' }}>{{ $p->strNombrePerfil }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-between bg-[var(--surface-1)] p-3 rounded-lg border border-[var(--surface-4)]">
                        <div>
                            <p class="text-xs font-bold text-[var(--text-1)]">Estado de Cuenta</p>
                            <p id="estadoLabel" class="text-[10px] font-medium {{ $usuario->idEstadoUsuario ? 'text-green-500' : 'text-red-500' }}">
                                {{ $usuario->idEstadoUsuario ? 'ACTIVO' : 'INACTIVO' }}
                            </p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="idEstadoUsuario" {{ $usuario->idEstadoUsuario ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- BLOQUE 4: SEGURIDAD (PASSWORD) --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-key"></i></div>
                    <h3 class="block-title">Seguridad</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Nueva contraseña (Opcional)</label>
                        {{-- 🛡️ Límite HTML de 60 caracteres --}}
                        <input type="password" id="strPwd" maxlength="60" class="input-premium" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Confirmar contraseña</label>
                        <input type="password" id="pwdConfirmar" maxlength="60" class="input-premium" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6">
                <a href="{{ route('usuarios.index') }}" class="text-xs font-bold text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">
                    <i class="fas fa-chevron-left mr-1"></i> Cancelar y volver
                </a>
                <button type="submit" id="btnActualizar" class="btn-primary flex items-center gap-2 py-3 px-10 rounded-xl font-bold shadow-xl shadow-neon-sm transition-all hover:scale-[1.02]">
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
    // 1. Uploadcare
    const widget = uploadcare.Widget('#strImagen');
    const imgPreview = document.getElementById('imgPreview');
    const fallback = document.getElementById('avatarFallback');
    
    document.getElementById('btnUpload').addEventListener('click', () => widget.openDialog());

    widget.onUploadComplete((fileInfo) => {
        const url = fileInfo.cdnUrl;
        if (imgPreview) {
            imgPreview.src = `${url}-/scale_crop/200x200/center/`;
        } else if (fallback) {
            fallback.outerHTML = `<img id="imgPreview" src="${url}-/scale_crop/200x200/center/" class="w-20 h-20 rounded-full object-cover border-2 border-[var(--surface-4)] shadow-md">`;
        }
    });

    // 2. Toggle Label Estado
    const checkEstado = document.getElementById('idEstadoUsuario');
    const labelEstado = document.getElementById('estadoLabel');
    checkEstado.addEventListener('change', () => {
        labelEstado.textContent = checkEstado.checked ? 'ACTIVO' : 'INACTIVO';
        labelEstado.className = `text-[10px] font-medium ${checkEstado.checked ? 'text-green-500' : 'text-red-500'}`;
    });

    // 3. Envío Fetch con TRADUCCIÓN DE ERRORES
    const form = document.getElementById('formEditarUsuario');
    const btnActualizar = document.getElementById('btnActualizar');

    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // Previene envío por defecto si algo falla

        const pwd = document.getElementById('strPwd').value;
        const confirm = document.getElementById('pwdConfirmar').value;

        if (pwd !== '' && pwd !== confirm) {
            if(window.showToast) window.showToast('Las contraseñas no coinciden', 'error');
            return;
        }

        btnActualizar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Guardando...';
        btnActualizar.disabled = true;

        const payload = {
            strNombreUsuario: document.getElementById('strNombreUsuario').value.trim(),
            strCorreo: document.getElementById('strCorreo').value.trim(),
            strNumeroCelular: document.getElementById('strNumeroCelular').value.trim(),
            idPerfil: document.getElementById('idPerfil').value,
            strImagen: document.getElementById('strImagen').value,
            idEstadoUsuario: checkEstado.checked ? 1 : 0
        };

        if (pwd !== '') payload.strPwd = pwd;

        try {
            const res = await fetch(`/usuarios/${document.getElementById('usuarioId').value}/actualizar`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            const data = await res.json();
            
            if (res.ok && data.success) {
                if(window.showToast) window.showToast('Usuario actualizado', 'success');
                setTimeout(() => window.location.href = "{{ route('usuarios.index') }}", 1000);
            } else {
                // 🛡️ TRADUCTOR DE ERRORES DE LARAVEL A HUMANO
                let errorMsg = data.message || data.mensaje || 'Ocurrió un error al actualizar el usuario.';
                if (data.errors) {
                    const primerError = Object.values(data.errors)[0][0];
                    if (primerError.includes('already been taken') && primerError.includes('nombre')) {
                        errorMsg = 'Este nombre de usuario ya está registrado en el sistema.';
                    } else if (primerError.includes('already been taken') && primerError.includes('correo')) {
                        errorMsg = 'Este correo electrónico ya está en uso por otra cuenta.';
                    } else {
                        errorMsg = primerError; 
                    }
                } else if ((data.message || data.mensaje || '').includes('already been taken')) {
                    if((data.message || data.mensaje || '').includes('correo')) {
                        errorMsg = 'Este correo electrónico ya está en uso por otra cuenta.';
                    } else {
                        errorMsg = 'Este nombre de usuario ya está registrado en el sistema.';
                    }
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