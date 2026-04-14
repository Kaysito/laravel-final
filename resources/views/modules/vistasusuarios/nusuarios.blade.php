@extends('layouts.app')

@section('title', 'Alta de Personal - KairosAI')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-3)]">Seguridad</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('usuarios.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Usuarios</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Crear Nuevo</span>
@endsection

@section('styles')
<style>
    /* Ocultar Uploadcare nativo para usar nuestro botón custom */
    .uploadcare--widget_type_hidden .uploadcare--widget__button_type_open { display: none !important; }

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

    /* Reglas de Contraseña Dinámicas */
    .pwd-rules { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 12px; padding: 12px; background: var(--surface-1); border-radius: 8px; border: 1px solid var(--surface-4); }
    .rule-item { font-size: 11px; color: var(--text-3); display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .rule-item.valid { color: #22c55e; }
    .rule-item.valid i { transform: scale(1.1); }

    /* Switch de Estado */
    .switch-container {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.75rem 1rem; background: var(--surface-1);
        border-radius: 10px; border: 1px solid var(--surface-4);
    }
    .toggle-switch { position: relative; display: inline-block; width: 42px; height: 22px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--surface-4); transition: .4s; border-radius: 34px;
    }
    .slider:before {
        position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px;
        background-color: white; transition: .4s; border-radius: 50%;
    }
    input:checked + .slider { background-color: #22c55e; }
    input:checked + .slider:before { transform: translateX(20px); }
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">
        
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center shadow-lg text-white">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Alta de Personal</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">La cuenta se enviará para verificación por correo electrónico automáticamente.</p>
            </div>
        </div>

        <form id="formNuevoUsuario" autocomplete="off">
            @csrf
            
            {{-- BLOQUE 1: FOTO --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-camera"></i></div>
                    <div>
                        <h3 class="block-title">Imagen de Perfil</h3>
                        <p class="block-subtitle">Suba una foto clara para la identificación en el dashboard.</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 pl-11">
                    <div class="relative shrink-0">
                        <div id="avatarFallback" class="w-20 h-20 rounded-full bg-[var(--surface-3)] border-2 border-[var(--surface-4)] flex items-center justify-center text-3xl text-[var(--text-4)] overflow-hidden">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div>
                        <button type="button" id="btnUpload" class="px-4 py-2 bg-[var(--surface-3)] border border-[var(--surface-4)] hover:border-[var(--neon)] text-[var(--text-1)] text-xs font-medium rounded-lg transition-all flex items-center gap-2">
                            <i class="fas fa-cloud-arrow-up"></i> Seleccionar imagen
                        </button>
                        <input type="hidden" role="uploadcare-uploader" name="strImagen" id="strImagen" data-crop="1:1" data-images-only />
                    </div>
                </div>
            </div>

            {{-- BLOQUE 2: INFO --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-address-card"></i></div>
                    <h3 class="block-title">Datos Generales</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Nombre Completo <span class="text-[var(--neon)]">*</span></label>
                        <input type="text" id="strNombreUsuario" name="strNombreUsuario" required maxlength="70" placeholder="Ej. Juan Pérez" class="input-premium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Teléfono Celular <span class="text-[var(--neon)]">*</span></label>
                        <input type="text" id="strNumeroCelular" name="strNumeroCelular" required maxlength="10" placeholder="10 dígitos" class="input-premium" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Correo Institucional / Personal <span class="text-[var(--neon)]">*</span></label>
                        <input type="email" id="strCorreo" name="strCorreo" required maxlength="100" placeholder="usuario@dominio.com" class="input-premium">
                    </div>
                </div>
            </div>

            {{-- BLOQUE 3: PERFIL --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-user-shield"></i></div>
                    <h3 class="block-title">Configuración de Acceso</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Rol Asignado <span class="text-[var(--neon)]">*</span></label>
                        <select id="idPerfil" name="idPerfil" required class="input-premium cursor-pointer">
                            <option value="" disabled selected>Seleccione un nivel de acceso...</option>
                            @foreach($perfiles as $p)
                                <option value="{{ $p->id }}">{{ $p->strNombrePerfil }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5 uppercase">Estado Inicial</label>
                        <div class="switch-container">
                            <span id="statusLabel" class="text-xs font-bold text-red-500">Inactivo (Requiere Verificación)</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="idEstadoUsuario" name="idEstadoUsuario">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BLOQUE 4: SEGURIDAD --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-lock"></i></div>
                    <h3 class="block-title">Credenciales de Seguridad</h3>
                </div>
                <div class="pl-11">
                    <div class="relative">
                        <input type="password" id="strPwd" name="strPwd" required maxlength="60" placeholder="Defina una contraseña robusta" class="input-premium pr-12">
                        <i class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-[var(--text-3)] cursor-pointer hover:text-[var(--neon)] transition-colors" id="btnTogglePwd"></i>
                    </div>
                    
                    <div class="pwd-rules">
                        <div class="rule-item" id="rule-length"><i class="fas fa-circle-xmark"></i> 8 caracteres min.</div>
                        <div class="rule-item" id="rule-upper"><i class="fas fa-circle-xmark"></i> Una Mayúscula</div>
                        <div class="rule-item" id="rule-lower"><i class="fas fa-circle-xmark"></i> Una Minúscula</div>
                        <div class="rule-item" id="rule-number"><i class="fas fa-circle-xmark"></i> Un Número</div>
                        <div class="rule-item" id="rule-special"><i class="fas fa-circle-xmark"></i> Carácter Especial (@$!%*#?&.)</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6">
                <a href="{{ route('usuarios.index') }}" class="text-xs font-bold text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                </a>
                <button type="submit" id="btnGuardar" class="btn-primary flex items-center gap-3 py-3 px-10 rounded-xl font-bold shadow-xl opacity-50 cursor-not-allowed transition-all" disabled>
                    <i class="fas fa-user-check"></i> Finalizar Registro
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formNuevoUsuario');
    const pwdInput = document.getElementById('strPwd');
    const btnGuardar = document.getElementById('btnGuardar');
    const stateSwitch = document.getElementById('idEstadoUsuario');
    const statusLabel = document.getElementById('statusLabel');

    // 1. Uploadcare Integration
    const widget = uploadcare.Widget('#strImagen');
    const fallback = document.getElementById('avatarFallback');
    document.getElementById('btnUpload').addEventListener('click', () => widget.openDialog());

    widget.onUploadComplete((fileInfo) => {
        fallback.innerHTML = `<img src="${fileInfo.cdnUrl}-/scale_crop/200x200/center/" class="w-full h-full rounded-full object-cover">`;
        fallback.classList.add('border-[var(--neon)]');
    });

    // 2. Password Visibility Toggle
    document.getElementById('btnTogglePwd').addEventListener('click', function() {
        const isPwd = pwdInput.type === 'password';
        pwdInput.type = isPwd ? 'text' : 'password';
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // 3. Status Switch Label
    stateSwitch.addEventListener('change', () => {
        const active = stateSwitch.checked;
        statusLabel.textContent = active ? "Activo (Acceso Inmediato)" : "Inactivo (Requiere Verificación)";
        statusLabel.className = active ? "text-xs font-bold text-green-500" : "text-xs font-bold text-red-500";
    });

    // 4. Password Strength & Validation
    const rules = {
        length: { regex: /.{8,}/, el: document.getElementById('rule-length') },
        upper: { regex: /[A-Z]/, el: document.getElementById('rule-upper') },
        lower: { regex: /[a-z]/, el: document.getElementById('rule-lower') },
        number: { regex: /[0-9]/, el: document.getElementById('rule-number') },
        special: { regex: /[@$!%*#?&.]/, el: document.getElementById('rule-special') }
    };

    pwdInput.addEventListener('input', () => {
        const val = pwdInput.value;
        let allValid = true;

        Object.keys(rules).forEach(key => {
            const isValid = rules[key].regex.test(val);
            const icon = rules[key].el.querySelector('i');
            rules[key].el.classList.toggle('valid', isValid);
            icon.className = isValid ? 'fas fa-circle-check' : 'fas fa-circle-xmark';
            if (!isValid) allValid = false;
        });

        btnGuardar.disabled = !allValid;
        btnGuardar.classList.toggle('opacity-50', !allValid);
        btnGuardar.classList.toggle('cursor-not-allowed', !allValid);
        btnGuardar.classList.toggle('cursor-pointer', allValid);
    });

    // 5. AJAX Submission (Zero Trust Logic)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const originalBtnContent = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Procesando...';
        btnGuardar.disabled = true;

        const payload = {
            strNombreUsuario: document.getElementById('strNombreUsuario').value.trim(),
            strCorreo: document.getElementById('strCorreo').value.trim(),
            strNumeroCelular: document.getElementById('strNumeroCelular').value.trim(),
            idPerfil: document.getElementById('idPerfil').value,
            strPwd: pwdInput.value,
            strImagen: document.getElementById('strImagen').value,
            idEstadoUsuario: stateSwitch.checked ? 1 : 0
        };

        try {
            const response = await fetch("{{ route('usuarios.guardar') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                if(window.showToast) window.showToast('¡Registro Exitoso!', 'success', data.mensaje);
                setTimeout(() => window.location.href = "{{ route('usuarios.index') }}", 2000);
            } else {
                // Manejo detallado de errores de validación
                let errorMsg = data.mensaje || 'Error en el servidor.';
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    errorMsg = firstError;
                }
                throw new Error(errorMsg);
            }
        } catch (err) {
            if(window.showToast) window.showToast(err.message, 'error');
            btnGuardar.innerHTML = originalBtnContent;
            btnGuardar.disabled = false;
        }
    });
});
</script>
@endsection