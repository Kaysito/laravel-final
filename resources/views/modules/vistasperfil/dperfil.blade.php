@extends('layouts.app')

@section('title', 'Inspección de Perfil')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <a href="{{ route('perfil.index') }}" class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors">Perfiles</a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Inspección</span>
@endsection

@section('styles')
<style>
.detail-container { background: var(--surface-2); border: 1px solid var(--surface-4); border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 40px -15px rgba(0,0,0,0.4); }
.info-group { margin-bottom: 2rem; }
.info-label { font-size: 0.65rem; font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-3); margin-bottom: 0.75rem; display: block; opacity: 0.8; }
.info-display { background: var(--surface-3); border: 1px solid var(--surface-4); border-radius: 12px; color: var(--text-1); padding: 0.85rem 1.25rem; width: 100%; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; min-height: 52px; }
.status-pill { padding: 6px 14px; border-radius: 10px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 8px; }
.status-pill.no { background: var(--surface-4); color: var(--text-3); }
.status-pill.yes { background: var(--neon-muted); color: var(--neon); border: 1px solid var(--neon-border); }
.date-box { display: flex; align-items: center; gap: 10px; padding: 0.75rem; background: var(--surface-1); border-radius: 12px; border: 1px solid var(--surface-4); color: var(--text-2); font-size: 0.9rem; font-weight: 500; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">
    <div class="max-w-4xl mx-auto">
        <div class="detail-container">
            <div class="flex items-center justify-between mb-12 pb-6 border-b border-[var(--surface-4)]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[var(--surface-3)] flex items-center justify-center text-[var(--neon)] text-xl border border-[var(--surface-4)]">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-[var(--text-1)] tracking-tight">Ficha del Perfil</h2>
                        <p class="text-[10px] text-[var(--text-3)] font-mono uppercase tracking-[0.2em]">Modo de solo lectura</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                <div class="info-group">
                    <label class="info-label">Nombre del perfil</label>
                    <div class="info-display">{{ $perfil->strNombrePerfil }}</div>
                </div>

                <div class="info-group">
                    <label class="info-label">Privilegios</label>
                    <div class="flex items-center mt-2">
                        <span class="status-pill {{ $perfil->bitAdministrador ? 'yes' : 'no' }}">
                            <i class="fas {{ $perfil->bitAdministrador ? 'fa-bolt' : 'fa-user' }}"></i>
                            {{ $perfil->bitAdministrador ? 'Súper Usuario' : 'Acceso Limitado' }}
                        </span>
                    </div>
                </div>

                <div class="info-group">
                    <label class="info-label">Fecha de registro</label>
                    <div class="date-box">
                        <i class="far fa-calendar-check text-[var(--neon)] opacity-70"></i>
                        {{ $perfil->created_at->format('d/m/Y, H:i:s') }}
                    </div>
                </div>

                <div class="info-group">
                    <label class="info-label">Última actividad</label>
                    <div class="date-box">
                        <i class="far fa-clock text-blue-400 opacity-70"></i>
                        {{ $perfil->updated_at->format('d/m/Y, H:i:s') }}
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-[var(--surface-4)]">
                <a href="{{ route('perfil.index') }}" class="btn-ghost inline-flex items-center gap-2 px-6 py-3 text-sm font-bold border border-[var(--surface-4)] rounded-xl bg-[var(--surface-2)]">
                    <i class="fas fa-chevron-left text-[10px]"></i> Regresar al listado
                </a>
            </div>
        </div>
    </div>
</div>
@endsection