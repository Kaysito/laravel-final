@extends('layouts.app')

@section('title', 'Detalle de Usuario')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('usuarios.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Usuarios</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Inspección</span>
@endsection

@section('styles')
<style>
.input-premium {
    width: 100%; padding: 0.65rem 1rem;
    background-color: var(--surface-2); border: 1px solid var(--surface-4);
    color: var(--text-3); border-radius: 8px; font-size: 13px;
    cursor: default; border-style: dashed;
}

.stacked-block {
    background: var(--surface-2); border: 1px solid var(--surface-4);
    border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;
}
.block-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
.block-title { font-size: 0.9rem; font-weight: 700; color: var(--text-1); display: flex; align-items: center; gap: 0.5rem; }
.block-subtitle { font-size: 0.7rem; color: var(--text-3); margin-top: 0.15rem; }
</style>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in relative">
    <div class="max-w-4xl mx-auto pb-12">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-magnifying-glass text-xl text-blue-400"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[var(--text-1)]">Ficha de Inspección</h2>
                    <p class="text-xs text-[var(--text-3)] mt-1 tracking-wide">Visualizando los datos del colaborador registrado.</p>
                </div>
            </div>
            
            <a href="{{ route('usuarios.editar', $usuario->id) }}" class="btn-primary flex items-center gap-2 px-5 py-2 text-sm shadow-neon-sm">
                <i class="fas fa-user-pen text-xs"></i> Editar Usuario
            </a>
        </div>

        {{-- IMAGEN --}}
        <div class="stacked-block">
            <div class="block-header">
                <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-camera"></i></div>
                <div>
                    <h3 class="block-title">Identidad Visual</h3>
                </div>
            </div>
            <div class="flex items-center gap-6 pl-11">
                <div class="relative shrink-0">
                    @if($usuario->strImagen)
                        <img src="{{ $usuario->strImagen }}-/scale_crop/200x200/center/" class="w-20 h-20 rounded-full object-cover border-2 border-[var(--surface-4)] shadow-md grayscale-[30%]">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-3xl font-bold text-white shadow-md">
                            {{ strtoupper(substr($usuario->strNombreUsuario, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-bold text-[var(--text-1)]">{{ $usuario->strNombreUsuario }}</p>
                    <p class="text-xs text-[var(--text-3)]">ID de sistema: #{{ str_pad($usuario->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- DATOS PERSONALES --}}
        <div class="stacked-block">
            <div class="block-header">
                <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-user"></i></div>
                <h3 class="block-title">Información Básica</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                <div>
                    <label class="block text-[11px] font-bold text-[var(--text-3)] mb-1.5 uppercase tracking-wider">Nombre en pantalla</label>
                    <div class="input-premium">{{ $usuario->strNombreUsuario }}</div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-[var(--text-3)] mb-1.5 uppercase tracking-wider">Número celular</label>
                    <div class="input-premium">{{ $usuario->strNumeroCelular ?: 'No registrado' }}</div>
                </div>
            </div>
        </div>

        {{-- DATOS INSTITUCIONALES --}}
        <div class="stacked-block">
            <div class="block-header">
                <div class="w-8 h-8 rounded bg-[var(--surface-3)] flex items-center justify-center text-[var(--text-3)]"><i class="fas fa-building-shield"></i></div>
                <h3 class="block-title">Datos Institucionales</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                <div>
                    <label class="block text-[11px] font-bold text-[var(--text-3)] mb-1.5 uppercase tracking-wider">Correo electrónico</label>
                    <div class="input-premium">{{ $usuario->strCorreo }}</div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-[var(--text-3)] mb-1.5 uppercase tracking-wider">Perfil de Accesos</label>
                    <div class="input-premium">{{ $usuario->perfil ? $usuario->perfil->strNombrePerfil : 'Sin Perfil' }}</div>
                </div>
            </div>
        </div>

        {{-- ESTADO DE SEGURIDAD --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            {{-- Email Status --}}
            <div class="bg-[var(--surface-2)] p-4 rounded-xl border {{ $usuario->correo_verificado_at ? 'border-emerald-500/20' : 'border-red-500/20' }} flex items-center gap-4">
                <div class="w-10 h-10 rounded-full {{ $usuario->correo_verificado_at ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }} flex items-center justify-center text-lg">
                    <i class="fas {{ $usuario->correo_verificado_at ? 'fa-envelope-circle-check' : 'fa-envelope-xmark' }}"></i>
                </div>
                <div>
                    <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Email</p>
                    <p class="text-sm font-bold text-[var(--text-1)]">{{ $usuario->correo_verificado_at ? 'Verificado' : 'Sin Verificar' }}</p>
                </div>
            </div>
            
            {{-- Celular Status --}}
            <div class="bg-[var(--surface-2)] p-4 rounded-xl border {{ $usuario->celular_verificado_at ? 'border-blue-500/20' : 'border-[var(--surface-4)]' }} flex items-center gap-4">
                <div class="w-10 h-10 rounded-full {{ $usuario->celular_verificado_at ? 'bg-blue-500/10 text-blue-400' : 'bg-[var(--surface-3)] text-[var(--text-3)]' }} flex items-center justify-center text-lg">
                    <i class="fas fa-mobile-screen"></i>
                </div>
                <div>
                    <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Celular</p>
                    <p class="text-sm font-bold text-[var(--text-1)]">{{ $usuario->celular_verificado_at ? 'MFA Activo' : 'Sin MFA' }}</p>
                </div>
            </div>

            {{-- 2FA Status --}}
            <div class="bg-[var(--surface-2)] p-4 rounded-xl border {{ $usuario->google2fa_secret ? 'border-[var(--neon)]/20' : 'border-[var(--surface-4)]' }} flex items-center gap-4">
                <div class="w-10 h-10 rounded-full {{ $usuario->google2fa_secret ? 'bg-[var(--neon)]/10 text-[var(--neon)]' : 'bg-[var(--surface-3)] text-[var(--text-3)]' }} flex items-center justify-center text-lg">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Google Auth</p>
                    <p class="text-sm font-bold text-[var(--text-1)]">{{ $usuario->google2fa_secret ? 'Vinculado' : 'Inactivo' }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-[var(--surface-4)] pt-6">
            <p class="text-[10px] text-[var(--text-4)] font-mono uppercase">Fecha de registro: {{ $usuario->created_at->format('d/M/Y H:i') }}</p>
            <a href="{{ route('usuarios.index') }}" class="flex items-center gap-2 bg-[var(--surface-3)] hover:bg-[var(--surface-4)] text-[var(--text-1)] font-medium py-2.5 px-6 rounded-lg transition-all border border-[var(--surface-4)]">
                <i class="fas fa-arrow-left text-xs"></i> Regresar
            </a>
        </div>

    </div>
</div>
@endsection