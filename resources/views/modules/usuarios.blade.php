@extends('layouts.app')

@section('title', $title)

@section('breadcrumb')
    <span class="text-[var(--text-3)]">Seguridad</span>
    <span class="breadcrumb-sep">/</span>
    <span class="text-[var(--text-1)] font-medium">{{ $title }}</span>
@endsection

@section('content')
<div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] shadow-card p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-[var(--text-1)]">Gestión de {{ $title }}</h2>
            <p class="text-sm text-[var(--text-3)] mt-1">Asignación de privilegios específicos por perfil.</p>
        </div>
        <button class="flex items-center gap-2 bg-neon-dark hover:bg-neon text-white font-medium py-2 px-5 rounded-lg transition-all duration-300 shadow-neon-sm hover:shadow-neon-md border border-neon-border">
            <i class="fas fa-plus text-sm"></i> <span>Asignar Permisos</span>
        </button>
    </div>

    <div class="overflow-x-auto rounded-lg border border-[var(--surface-4)]">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[var(--surface-3)] border-b border-[var(--surface-4)]">
                <tr>
                    <th class="py-3 px-5 text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Perfil</th>
                    <th class="py-3 px-5 text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Módulo</th>
                    <th class="py-3 px-5 text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase text-center">Permisos Asignados</th>
                    <th class="py-3 px-5 text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--surface-4)]">
                <tr class="hover:bg-[var(--surface-3)] transition-colors duration-150 group">
                    <td class="py-3 px-5 font-medium text-[var(--text-1)]">Vendedor</td>
                    <td class="py-3 px-5 text-[var(--text-2)]">Principal 1.1</td>
                    <td class="py-3 px-5 text-center space-x-1">
                        <span class="inline-block px-2 py-1 text-[10px] rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" title="Consulta">Ver</span>
                        <span class="inline-block px-2 py-1 text-[10px] rounded bg-blue-500/10 text-blue-400 border border-blue-500/20" title="Agregar">Agr</span>
                        <span class="inline-block px-2 py-1 text-[10px] rounded bg-zinc-700/30 text-zinc-500 border border-zinc-600/30 line-through" title="Editar">Edi</span>
                        <span class="inline-block px-2 py-1 text-[10px] rounded bg-zinc-700/30 text-zinc-500 border border-zinc-600/30 line-through" title="Eliminar">Eli</span>
                    </td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center items-center gap-3 opacity-70 group-hover:opacity-100 transition-opacity">
                            <button class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                            <button class="text-[var(--text-3)] hover:text-blue-400 transition-colors" title="Editar"><i class="fas fa-pen-to-square"></i></button>
                            <button class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors" title="Eliminar"><i class="fas fa-trash-can"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-5 flex flex-col sm:flex-row justify-between items-center text-sm gap-4">
        <span class="text-[var(--text-3)]">Mostrando <span class="text-[var(--text-1)] font-medium">1</span> a <span class="text-[var(--text-1)] font-medium">5</span> de <span class="text-[var(--text-1)] font-medium">20</span> registros</span>
        <div class="flex items-center space-x-1">
            <button class="px-3 py-1.5 rounded-md border border-[var(--surface-4)] bg-[var(--surface-2)] text-[var(--text-3)] hover:text-[var(--text-1)] hover:bg-[var(--surface-3)] transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
            <button class="px-3 py-1.5 rounded-md border border-neon-border bg-neon-muted text-[var(--neon)] font-medium transition-colors">1</button>
            <button class="px-3 py-1.5 rounded-md border border-[var(--surface-4)] bg-[var(--surface-2)] text-[var(--text-3)] hover:text-[var(--text-1)] hover:bg-[var(--surface-3)] transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
        </div>
    </div>
</div>
@endsection