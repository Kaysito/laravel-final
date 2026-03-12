@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-[var(--text-1)] font-medium">Dashboard Central</span>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-[var(--text-1)]">Bienvenido al Sistema</h1>
    <p class="text-[var(--text-3)] mt-1">Panel de control y resumen de actividades.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-6 shadow-card hover:border-[var(--neon-border)] transition-colors duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[var(--text-3)] text-xs font-mono uppercase tracking-wider mb-1">Usuarios Activos</p>
                <h3 class="text-3xl font-bold text-[var(--text-1)]">12</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-6 shadow-card hover:border-[var(--neon-border)] transition-colors duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[var(--text-3)] text-xs font-mono uppercase tracking-wider mb-1">Perfiles</p>
                <h3 class="text-3xl font-bold text-[var(--text-1)]">5</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20">
                <i class="fas fa-id-badge"></i>
            </div>
        </div>
    </div>

    <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-6 shadow-card hover:border-[var(--neon-border)] transition-colors duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[var(--text-3)] text-xs font-mono uppercase tracking-wider mb-1">Alertas</p>
                <h3 class="text-3xl font-bold text-neon-glow">3</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-neon-muted text-[var(--neon)] flex items-center justify-center border border-neon-border">
                <i class="fas fa-bell"></i>
            </div>
        </div>
    </div>
</div>
@endsection