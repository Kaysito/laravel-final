@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-[var(--text-1)] font-medium">Dashboard Central</span>
@endsection

@section('content')
<div class="h-full w-full overflow-y-auto p-4 sm:p-6 fade-in">
    <div class="max-w-7xl mx-auto pb-12">
        
        {{-- HEADER DEL DASHBOARD --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[var(--text-1)] flex items-center gap-3">
                    <i class="fas fa-gauge-high text-[var(--neon)]"></i> Bienvenido al Sistema
                </h1>
                <p class="text-sm text-[var(--text-3)] mt-1">Panel de control y resumen general de métricas.</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Fecha del Sistema</p>
                <p class="text-sm font-bold text-[var(--text-1)]">{{ now()->format('d de F, Y') }}</p>
            </div>
        </div>

        {{-- TARJETAS DE MÉTRICAS (KPIs) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- Total Usuarios --}}
            <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-5 shadow-sm hover:border-blue-500/30 transition-colors group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[var(--text-3)] text-[10px] font-bold font-mono uppercase tracking-widest mb-1">Total Usuarios</p>
                        <h3 class="text-3xl font-bold text-[var(--text-1)]">{{ $totalUsuarios ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Usuarios Activos --}}
            <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-5 shadow-sm hover:border-emerald-500/30 transition-colors group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[var(--text-3)] text-[10px] font-bold font-mono uppercase tracking-widest mb-1">Cuentas Activas</p>
                        <h3 class="text-3xl font-bold text-[var(--text-1)]">{{ $usuariosActivos ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-check text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Cuentas Inactivas (Lógica corregida) --}}
            <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-5 shadow-sm hover:border-yellow-500/30 transition-colors group tooltip" data-tip="Pendientes de verificación o desactivadas">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[var(--text-3)] text-[10px] font-bold font-mono uppercase tracking-widest mb-1">Cuentas Inactivas</p>
                        <h3 class="text-3xl font-bold text-[var(--text-1)]">{{ $usuariosInactivos ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-500/10 text-yellow-500 flex items-center justify-center border border-yellow-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-clock text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Perfiles --}}
            <div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] p-5 shadow-sm hover:border-purple-500/30 transition-colors group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[var(--text-3)] text-[10px] font-bold font-mono uppercase tracking-widest mb-1">Roles / Perfiles</p>
                        <h3 class="text-3xl font-bold text-[var(--text-1)]">{{ $totalPerfiles ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-halved text-lg"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- SECCIÓN DE ACTIVIDAD: ÚLTIMOS USUARIOS --}}
        <div class="bg-[var(--surface-2)] rounded-2xl border border-[var(--surface-4)] shadow-sm overflow-hidden">
            
            <div class="px-6 py-5 border-b border-[var(--surface-4)] bg-[var(--surface-2)] flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-1)] flex items-center gap-2">
                        <i class="fas fa-clock-rotate-left text-blue-400"></i> Registro de Actividad
                    </h3>
                </div>
                <a href="{{ route('usuarios.index') }}" class="text-xs font-medium text-[var(--neon)] hover:text-[var(--neon-dark)] transition-colors">
                    Ver directorio completo <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto bg-[var(--surface-1)]">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-[var(--surface-2)] border-b border-[var(--surface-4)]">
                        <tr>
                            <th class="py-3 px-6 text-[9px] font-bold tracking-widest text-[var(--text-3)] uppercase">Usuario</th>
                            <th class="py-3 px-6 text-[9px] font-bold tracking-widest text-[var(--text-3)] uppercase">Contacto</th>
                            <th class="py-3 px-6 text-[9px] font-bold tracking-widest text-[var(--text-3)] uppercase">Perfil Asignado</th>
                            <th class="py-3 px-6 text-[9px] font-bold tracking-widest text-[var(--text-3)] uppercase text-center">Estado</th>
                            <th class="py-3 px-6 text-[9px] font-bold tracking-widest text-[var(--text-3)] uppercase text-right">Registro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--surface-4)] text-[var(--text-2)]">
                        @forelse($ultimosUsuarios ?? [] as $u)
                        <tr class="hover:bg-[var(--surface-3)] transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($u->strImagen)
                                        <img src="{{ $u->strImagen }}-/scale_crop/60x60/center/" class="w-8 h-8 rounded-full object-cover border border-[var(--surface-4)]">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center text-[10px] font-bold text-white shadow-sm border border-[var(--surface-4)]">
                                            {{ strtoupper(substr($u->strNombreUsuario, 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="font-bold text-[var(--text-1)]">{{ $u->strNombreUsuario }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs">{{ $u->strCorreo }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded text-[10px] font-medium bg-[var(--surface-3)] text-[var(--text-2)] border border-[var(--surface-4)]">
                                    {{ $u->perfil ? $u->perfil->strNombrePerfil : 'Sin Perfil' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($u->idEstadoUsuario)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-500 border border-green-500/20">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right text-xs text-[var(--text-3)] font-mono">
                                {{ $u->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-[var(--text-3)] text-sm bg-[var(--surface-1)]">
                                <div class="w-16 h-16 mx-auto bg-[var(--surface-3)] rounded-full flex items-center justify-center mb-3 border border-[var(--surface-4)]">
                                    <i class="fas fa-ghost text-xl"></i>
                                </div>
                                No hay registros recientes disponibles.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 🟢 PAGINACIÓN AL ESTILO DE TU PROYECTO --}}
            @if(isset($ultimosUsuarios) && method_exists($ultimosUsuarios, 'hasPages') && $ultimosUsuarios->hasPages())
                <div class="py-4 px-6 flex justify-center items-center gap-2 border-t border-[var(--surface-4)] bg-[var(--surface-2)]">
                    
                    {{-- Botón Anterior --}}
                    @if ($ultimosUsuarios->onFirstPage())
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-4)] bg-[var(--surface-3)] cursor-not-allowed opacity-50" disabled>
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                    @else
                        <a href="{{ $ultimosUsuarios->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-2)] hover:bg-[var(--surface-3)] border border-[var(--surface-4)] transition-colors">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </a>
                    @endif
                    
                    {{-- Números de Página --}}
                    <div class="flex items-center gap-1 px-2">
                        @foreach ($ultimosUsuarios->links()->elements[0] as $page => $url)
                            @if ($page == $ultimosUsuarios->currentPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[var(--neon)] text-white font-bold text-xs shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-2)] hover:bg-[var(--surface-3)] font-medium text-xs transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    {{-- Botón Siguiente --}}
                    @if ($ultimosUsuarios->hasMorePages())
                        <a href="{{ $ultimosUsuarios->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-2)] hover:bg-[var(--surface-3)] border border-[var(--surface-4)] transition-colors">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </a>
                    @else
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-4)] bg-[var(--surface-3)] cursor-not-allowed opacity-50" disabled>
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    @endif
                </div>
            @endif

        </div>

    </div>
</div>
@endsection