@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Mi Perfil</span>
@endsection

@section('styles')
<style>
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
.input-premium:disabled {
    background-color: var(--surface-2); color: var(--text-3);
    cursor: not-allowed; opacity: 0.7; border-style: dashed;
}
.input-premium::placeholder { color: var(--text-3); opacity: 0.7; }

.stacked-block {
    background: var(--surface-2); border: 1px solid var(--surface-4);
    border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.block-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
.block-title { font-size: 0.9rem; font-weight: 700; color: var(--text-1); display: flex; align-items: center; gap: 0.5rem; }
.block-subtitle { font-size: 0.7rem; color: var(--text-3); margin-top: 0.15rem; }

/* Ajuste para que el SVG del código QR se vea perfecto */
#qrcodeBox svg { width: 100%; height: 100%; border-radius: 8px; }
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">
        
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center shadow-lg">
                <i class="fas fa-id-badge text-xl text-blue-400"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[var(--text-1)]">Configuración de mi cuenta</h2>
                <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Actualiza tu información pública y métodos de contacto.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-sm">
                <p class="font-bold mb-1"><i class="fas fa-triangle-exclamation mr-2"></i> Errores encontrados:</p>
                <ul class="list-disc list-inside opacity-90 font-mono text-xs">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form id="formMiPerfil" action="{{ route('miperfil.guardar') }}" method="POST">
            @csrf @method('PUT')

            {{-- IMAGEN --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-camera"></i></div>
                    <div>
                        <h3 class="block-title">Foto de Perfil</h3>
                        <p class="block-subtitle">Será visible para los administradores y en tu dashboard.</p>
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
                            <i class="fas fa-cloud-arrow-up"></i> Actualizar foto
                        </button>
                        <input type="hidden" role="uploadcare-uploader" name="strImagen" id="strImagen" value="{{ $usuario->strImagen }}" data-crop="1:1" data-images-only class="hidden-widget" />
                    </div>
                </div>
            </div>

            {{-- DATOS PERSONALES --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-user"></i></div>
                    <div>
                        <h3 class="block-title">Información Básica</h3>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5">Nombre en pantalla <span class="text-[var(--neon)]">*</span></label>
                        <input type="text" name="strNombreUsuario" value="{{ old('strNombreUsuario', $usuario->strNombreUsuario) }}" required class="input-premium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5">Número celular <span class="text-[var(--neon)]">*</span></label>
                        <input type="text" name="strNumeroCelular" value="{{ old('strNumeroCelular', $usuario->strNumeroCelular) }}" required class="input-premium">
                    </div>
                </div>
            </div>

            {{-- SEGURIDAD --}}
            <div class="stacked-block">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-lock"></i></div>
                    <div>
                        <h3 class="block-title">Seguridad</h3>
                        <p class="block-subtitle">Cambia tu contraseña si sospechas que ha sido vulnerada.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5">Nueva contraseña</label>
                        <div class="relative">
                            <i class="fas fa-key absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="password" name="strPwd" id="pwdNueva" class="input-premium pl-8" placeholder="••••••••">
                        </div>
                        <p class="text-[9px] text-[var(--text-3)] mt-1">Déjalo en blanco para no cambiarla</p>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-2)] mb-1.5">Confirmar nueva contraseña</label>
                        <div class="relative">
                            <i class="fas fa-check-double absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs"></i>
                            <input type="password" id="pwdConfirmar" class="input-premium pl-8" placeholder="••••••••">
                        </div>
                        <p id="errorPwd" class="text-[9px] text-[var(--neon)] mt-1 hidden">Las contraseñas no coinciden.</p>
                    </div>
                </div>
            </div>

            {{-- DATOS BLOQUEADOS --}}
            <div class="stacked-block bg-[var(--surface-1)] border-dashed border-[var(--surface-4)]">
                <div class="block-header">
                    <div class="w-8 h-8 rounded bg-[var(--surface-2)] border border-[var(--surface-4)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-building-shield"></i></div>
                    <div>
                        <h3 class="block-title">Datos Institucionales</h3>
                        <p class="block-subtitle">Campos bloqueados. Contacta a un administrador para modificarlos.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-3)] mb-1.5">Correo electrónico (Login)</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs opacity-50"></i>
                            <input type="email" value="{{ $usuario->strCorreo }}" disabled readonly class="input-premium pl-8">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--text-3)] mb-1.5">Perfil de Accesos (Rol)</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-xs opacity-50"></i>
                            <input type="text" value="{{ $usuario->perfil ? $usuario->perfil->strNombrePerfil : 'Sin Perfil' }}" disabled readonly class="input-premium pl-8">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ESTADO DE VERIFICACIONES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                
                {{-- Email --}}
                <div class="bg-[var(--surface-2)] p-4 rounded-xl border border-emerald-500/20 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400 text-lg"><i class="fas fa-envelope-circle-check"></i></div>
                    <div>
                        <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Email</p>
                        <p class="text-sm font-bold text-[var(--text-1)]">Verificado</p>
                    </div>
                </div>
                
                {{-- Celular SMS --}}
                <div class="bg-[var(--surface-2)] p-4 rounded-xl border {{ $usuario->celular_verificado_at ? 'border-blue-500/30' : 'border-[var(--surface-4)]' }} flex items-center gap-4 relative overflow-hidden group">
                    <div class="w-10 h-10 rounded-full {{ $usuario->celular_verificado_at ? 'bg-blue-500/10 text-blue-400' : 'bg-[var(--surface-3)] text-[var(--text-3)]' }} flex items-center justify-center text-lg z-10"><i class="fas fa-mobile-screen"></i></div>
                    <div class="flex-1 z-10">
                        <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Celular</p>
                        @if($usuario->celular_verificado_at)
                            <p class="text-sm font-bold text-[var(--text-1)] text-blue-400">Verificado</p>
                        @else
                            <p class="text-sm font-bold text-[var(--text-2)]">Sin verificar</p>
                        @endif
                    </div>
                    
                    @if(!$usuario->celular_verificado_at)
                        <button type="button" onclick="iniciarVerificacionSms(event)" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg transition-colors shadow-[0_0_10px_rgba(37,99,235,0.2)] border border-blue-500/50 z-10 cursor-pointer">
                            Verificar
                        </button>
                    @endif
                </div>

                {{-- MFA (2FA Google Authenticator) --}}
                <div class="bg-[var(--surface-2)] p-4 rounded-xl border {{ $usuario->google2fa_secret ? 'border-[var(--neon)]/30' : 'border-[var(--surface-4)]' }} flex items-center gap-4 group cursor-pointer hover:border-[var(--surface-5)] transition-colors" onclick="abrirModal2FA()">
                    <div class="w-10 h-10 rounded-full {{ $usuario->google2fa_secret ? 'bg-[var(--neon)]/10 text-[var(--neon)]' : 'bg-[var(--surface-3)] text-[var(--text-3)]' }} flex items-center justify-center text-lg"><i class="fas fa-qrcode"></i></div>
                    <div class="flex-1">
                        <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Google Auth (2FA)</p>
                        @if($usuario->google2fa_secret)
                            <p class="text-sm font-bold text-[var(--neon)]">Protegido</p>
                        @else
                            <p class="text-sm font-bold text-[var(--text-2)]">Inactivo</p>
                        @endif
                    </div>
                    <i class="fas fa-chevron-right text-[var(--text-3)] text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" id="btnGuardar" class="flex items-center gap-2 bg-[var(--surface-3)] hover:bg-[var(--surface-4)] text-[var(--text-1)] font-medium py-3 px-8 rounded-lg transition-all duration-300 border border-[var(--surface-4)] hover:border-gray-500/50">
                    <i class="fas fa-floppy-disk"></i> Guardar Mis Datos
                </button>
            </div>

        </form>
    </div>

    {{-- 📱 MODAL SMS --}}
    <div id="modalSms" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden transition-opacity duration-200 ease-in-out" style="opacity: 0;">
        <div id="modalSmsContent" class="bg-[var(--surface-2)] border border-[var(--surface-4)] rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden text-center p-8 transform scale-95 transition-transform duration-200 ease-in-out">
            <div class="w-16 h-16 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center text-2xl mx-auto mb-4 border border-blue-500/20"><i class="fas fa-comment-sms"></i></div>
            <h3 class="text-xl font-bold text-[var(--text-1)] mb-2">Verificación SMS</h3>
            <p class="text-xs text-[var(--text-3)] mb-6">Hemos enviado un código al número terminado en <strong class="text-[var(--text-2)]">{{ $usuario->strNumeroCelular ? substr($usuario->strNumeroCelular, -4) : 'XXXX' }}</strong>.</p>
            <input type="text" id="inputCodigoSms" maxlength="6" autocomplete="off" inputmode="numeric" class="w-full text-center text-2xl tracking-[0.5em] font-mono font-bold px-4 py-3 bg-[var(--surface-1)] border border-[var(--surface-4)] text-[var(--text-1)] rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 mb-6" placeholder="000000">
            <div id="smsError" class="text-red-400 text-xs mb-3 hidden"></div>
            <div class="flex gap-3">
                <button type="button" onclick="cerrarModalSms()" class="flex-1 py-2.5 rounded-lg border border-[var(--surface-4)] text-[var(--text-2)] text-sm font-medium hover:bg-[var(--surface-3)]">Cancelar</button>
                <button type="button" id="btnConfirmarSms" onclick="confirmarSms()" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium">Verificar</button>
            </div>
            <button type="button" onclick="reenviarCodigo()" class="text-xs text-blue-400 hover:text-blue-300 transition-colors mt-4">¿No recibiste el código? Reenviar</button>
        </div>
    </div>

    {{-- 🔐 MODAL GOOGLE AUTHENTICATOR (2FA) --}}
    <div id="modal2FA" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden transition-opacity duration-200 ease-in-out" style="opacity: 0;">
        <div id="modal2FAContent" class="bg-[var(--surface-2)] border border-[var(--surface-4)] rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden text-center p-8 transform scale-95 transition-transform duration-200 ease-in-out">
            <div class="w-16 h-16 rounded-full bg-[var(--neon)]/10 text-[var(--neon)] flex items-center justify-center text-3xl mx-auto mb-4 border border-[var(--neon)]/20"><i class="fas fa-qrcode"></i></div>
            
            <h3 class="text-xl font-bold text-[var(--text-1)] mb-2">Configurar Autenticador</h3>
            <p class="text-xs text-[var(--text-3)] mb-4">Escanea este código con tu app de autenticación.</p>
            
            {{-- Contenedor donde inyectaremos el SVG --}}
            <div id="qrcodeBox" class="bg-white p-3 rounded-xl inline-flex items-center justify-center mb-2 shadow-lg mx-auto w-40 h-40">
                <i class="fas fa-spinner fa-spin text-gray-400 text-3xl"></i>
            </div>

            <p class="text-[10px] text-[var(--text-3)] mb-4 leading-tight">O ingresa esta clave manual: <br><strong id="manualSecret" class="text-[var(--text-1)] tracking-widest font-mono text-sm mt-1 inline-block"></strong></p>
            
            <input type="text" id="inputCodigo2FA" maxlength="6" autocomplete="off" inputmode="numeric" class="w-full text-center text-2xl tracking-[0.5em] font-mono font-bold px-4 py-3 bg-[var(--surface-1)] border border-[var(--surface-4)] text-[var(--text-1)] rounded-lg focus:outline-none focus:border-[var(--neon)] focus:ring-2 focus:ring-[var(--neon)]/20 mb-6" placeholder="000000">
            
            <div id="error2FA" class="text-red-400 text-xs mb-3 hidden"></div>
            
            <div class="flex gap-3">
                <button type="button" onclick="cerrarModal2FA()" class="flex-1 py-2.5 rounded-lg border border-[var(--surface-4)] text-[var(--text-2)] text-sm font-medium hover:bg-[var(--surface-3)]">Cerrar</button>
                <button type="button" id="btnConfirmar2FA" onclick="confirmar2FA()" class="flex-1 py-2.5 rounded-lg btn-primary text-sm font-bold shadow-lg">Vincular</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. UPLOADCARE
    // ==========================================
    if (typeof uploadcare !== 'undefined') {
        const widget = uploadcare.Widget('#strImagen');
        const imgPreview = document.getElementById('imgPreview');
        const fallback = document.getElementById('avatarFallback');
        const btnUpload = document.getElementById('btnUpload');

        if (btnUpload) {
            btnUpload.addEventListener('click', () => { widget.openDialog(); });
        }

        widget.onUploadComplete((fileInfo) => {
            const newUrl = fileInfo.cdnUrl;
            if (imgPreview) {
                imgPreview.src = `${newUrl}-/scale_crop/200x200/center/`;
            } else if (fallback) {
                fallback.outerHTML = `<img id="imgPreview" src="${newUrl}-/scale_crop/200x200/center/" class="w-20 h-20 rounded-full object-cover border-2 border-[var(--surface-4)] shadow-md">`;
            }
        });
    }

    // ==========================================
    // 2. VALIDACIÓN DE CONTRASEÑAS
    // ==========================================
    const form = document.getElementById('formMiPerfil');
    const pwd = document.getElementById('pwdNueva');
    const pwdConfirm = document.getElementById('pwdConfirmar');
    const errorMsg = document.getElementById('errorPwd');

    if (form) {
        form.addEventListener('submit', function(e) {
            if(pwd.value !== '' && pwd.value !== pwdConfirm.value) {
                e.preventDefault(); 
                pwdConfirm.classList.add('border-[var(--neon)]');
                errorMsg.classList.remove('hidden');
                if(typeof window.showToast === 'function') {
                    window.showToast('Las contraseñas no coinciden', 'error');
                }
                return;
            }
            
            pwdConfirm.classList.remove('border-[var(--neon)]');
            errorMsg.classList.add('hidden');
            const btn = document.getElementById('btnGuardar');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
                btn.classList.add('opacity-75', 'cursor-wait');
            }
        });
    }

    // ==========================================
    // 3. MENSAJE DE ÉXITO
    // ==========================================
    @if(session('success'))
        setTimeout(() => { 
            if(typeof window.showToast === 'function') {
                window.showToast("{{ session('success') }}", 'success'); 
            } else {
                alert("{{ session('success') }}");
            }
        }, 500);
    @endif

    // ==========================================
    // 4. VERIFICACIÓN SMS 
    // ==========================================
    const modalSms = document.getElementById('modalSms');
    const contentSms = document.getElementById('modalSmsContent');
    const inputSms = document.getElementById('inputCodigoSms');
    const smsError = document.getElementById('smsError');
    const btnConfirmarSms = document.getElementById('btnConfirmarSms');

    function mostrarErrorSms(mensaje) {
        if (smsError) {
            smsError.textContent = mensaje;
            smsError.classList.remove('hidden');
        }
    }

    function ocultarErrorSms() {
        if (smsError) smsError.classList.add('hidden');
    }

    window.iniciarVerificacionSms = async (event) => {
        if (event) event.preventDefault();
        const btn = event?.target;
        const originalText = btn?.innerHTML || 'Verificar';
        
        try {
            if (btn) { btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btn.disabled = true; }
            
            @if(!$usuario->strNumeroCelular)
                throw new Error('No tienes un número de celular guardado');
            @endif
            
            const response = await fetch('/mi-perfil/enviar-sms', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            const res = await response.json();
            
            if(res.success) {
                if (typeof window.showToast === 'function') window.showToast(res.message, 'success');
                if (inputSms) inputSms.value = '';
                ocultarErrorSms();
                
                if (modalSms) {
                    modalSms.classList.remove('hidden');
                    setTimeout(() => { 
                        modalSms.style.opacity = '1'; 
                        if (contentSms) contentSms.style.transform = 'scale(1)'; 
                    }, 10);
                    if (inputSms) inputSms.focus();
                }
            } else {
                throw new Error(res.message || 'Error al enviar el código');
            }
        } catch(err) {
            if (typeof window.showToast === 'function') window.showToast(err.message, 'error');
        } finally {
            if (btn) { btn.innerHTML = originalText; btn.disabled = false; }
        }
    };

    window.cerrarModalSms = () => {
        if (modalSms) {
            modalSms.style.opacity = '0'; 
            if (contentSms) contentSms.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modalSms.classList.add('hidden');
                ocultarErrorSms();
                if (inputSms) inputSms.value = '';
            }, 200);
        }
    };

    window.confirmarSms = async () => {
        const codigo = inputSms?.value.trim() || '';
        if(codigo.length !== 6) { mostrarErrorSms('El código debe tener 6 dígitos'); return; }

        ocultarErrorSms();
        if (btnConfirmarSms) { btnConfirmarSms.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btnConfirmarSms.disabled = true; }

        try {
            const response = await fetch('/mi-perfil/verificar-sms', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ codigo: codigo })
            });

            const res = await response.json();

            if(res.success) {
                if (typeof window.showToast === 'function') window.showToast(res.message, 'success');
                setTimeout(() => window.location.reload(), 1500); 
            } else {
                mostrarErrorSms(res.message || 'Código incorrecto');
            }
        } catch(err) {
            mostrarErrorSms('Error al verificar. Intenta de nuevo.');
        } finally {
            if (btnConfirmarSms) { btnConfirmarSms.innerHTML = 'Verificar'; btnConfirmarSms.disabled = false; }
        }
    };

    window.reenviarCodigo = async () => {
        try {
            const response = await fetch('/mi-perfil/enviar-sms', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const res = await response.json();
            if(res.success) {
                if (typeof window.showToast === 'function') window.showToast('Código reenviado', 'success');
                if (inputSms) { inputSms.value = ''; inputSms.focus(); }
                ocultarErrorSms();
            } else { throw new Error(res.message); }
        } catch(err) {
            if (typeof window.showToast === 'function') window.showToast('Error al reenviar', 'error');
        }
    };

    if (inputSms) {
        inputSms.addEventListener('input', (e) => { e.target.value = e.target.value.replace(/[^0-9]/g, ''); });
        inputSms.addEventListener('keypress', (e) => { if (e.key === 'Enter') confirmarSms(); });
    }

    // ==========================================
    // 5. LÓGICA 2FA (GOOGLE AUTHENTICATOR - SVG)
    // ==========================================
    const modal2FA = document.getElementById('modal2FA');
    const content2FA = document.getElementById('modal2FAContent');
    const input2FA = document.getElementById('inputCodigo2FA');
    const error2FA = document.getElementById('error2FA');
    const btnConfirmar2FA = document.getElementById('btnConfirmar2FA');
    const qrcodeBox = document.getElementById('qrcodeBox');

    window.abrirModal2FA = async () => {
        try {
            if(window.showToast) window.showToast('Generando código seguro...', 'info');
            
            const response = await fetch('/mi-perfil/2fa/setup', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            
            const res = await response.json();
            
            if (res.success) {
                // Inyectamos el SVG decodificando el Base64 que manda el controlador
                qrcodeBox.innerHTML = atob(res.qr_image);
                document.getElementById('manualSecret').textContent = res.secret;

                // Mostramos el modal
                input2FA.value = '';
                error2FA.classList.add('hidden');
                modal2FA.classList.remove('hidden');
                setTimeout(() => { 
                    modal2FA.style.opacity = '1'; 
                    content2FA.style.transform = 'scale(1)'; 
                    input2FA.focus();
                }, 10);
            } else {
                throw new Error("No se pudo generar el QR");
            }
        } catch(err) {
            console.error(err);
            if(window.showToast) window.showToast('Error al conectar con el servidor', 'error');
        }
    };

    window.cerrarModal2FA = () => {
        modal2FA.style.opacity = '0'; 
        content2FA.style.transform = 'scale(0.95)';
        setTimeout(() => { modal2FA.classList.add('hidden'); }, 200);
    };

    window.confirmar2FA = async () => {
        const codigo = input2FA.value.trim();
        if(codigo.length !== 6) {
            error2FA.textContent = 'El código debe tener 6 dígitos';
            error2FA.classList.remove('hidden');
            return;
        }

        error2FA.classList.add('hidden');
        btnConfirmar2FA.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btnConfirmar2FA.disabled = true;

        try {
            const response = await fetch('/mi-perfil/2fa/verificar', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ code: codigo })
            });

            const res = await response.json();

            if(res.success) {
                if(window.showToast) window.showToast(res.mensaje, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                error2FA.textContent = res.mensaje || 'Código inválido o expirado.';
                error2FA.classList.remove('hidden');
            }
        } catch(err) {
            console.error(err);
            error2FA.textContent = 'Error de conexión.';
            error2FA.classList.remove('hidden');
        } finally {
            btnConfirmar2FA.innerHTML = 'Vincular';
            btnConfirmar2FA.disabled = false;
        }
    };

    if (input2FA) {
        input2FA.addEventListener('input', (e) => { e.target.value = e.target.value.replace(/[^0-9]/g, ''); });
        input2FA.addEventListener('keypress', (e) => { if (e.key === 'Enter') confirmar2FA(); });
    }

    // Tecla ESC cierra los modales
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (modalSms && !modalSms.classList.contains('hidden')) cerrarModalSms();
            if (modal2FA && !modal2FA.classList.contains('hidden')) cerrarModal2FA();
        }
    });
});
</script>
@endsection