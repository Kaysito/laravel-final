@extends('layouts.app')

@section('title', $title)
@section('breadcrumb', $breadcrumb)

@section('content')
<div class="bg-[var(--surface-2)] rounded-xl border border-[var(--surface-4)] shadow-card p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-[var(--text-1)]">Vista Estática: {{ $title }}</h2>
            <p class="text-sm text-[var(--text-3)] mt-1">Esta pantalla muestra la interfaz CRUD sin conexión a base de datos.</p>
        </div>
        <button class="flex items-center gap-2 bg-neon-dark hover:bg-neon text-white font-medium py-2 px-5 rounded-lg transition-all duration-300 shadow-neon-sm hover:shadow-neon-md border border-neon-border">
            <i class="fas fa-plus text-sm"></i> <span>Crear Registro</span>
        </button>
    </div>

    <div class="mb-6 flex space-x-3">
        <div class="relative w-full sm:w-1/3">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-sm"></i>
            <input type="text" placeholder="Búsqueda estática..." class="w-full pl-10 pr-4 py-2.5 bg-[var(--surface-3)] border border-[var(--surface-4)] text-[var(--text-1)] placeholder-[var(--text-3)] rounded-lg focus:outline-none focus:border-[var(--neon-border)] focus:shadow-[0_0_0_3px_rgba(255,23,68,0.1)] transition-all text-sm">
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-[var(--surface-4)]">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[var(--surface-3)] border-b border-[var(--surface-4)]">
                <tr>
                    <th class="py-3 px-5 text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase">Dato de Ejemplo</th>
                    <th class="py-3 px-5 text-[10px] font-mono tracking-widest text-[var(--text-3)] uppercase text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--surface-4)]">
                <tr class="hover:bg-[var(--surface-3)] transition-colors duration-150 group">
                    <td class="py-3 px-5 text-[var(--text-2)]">Información de prueba 1</td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center items-center gap-3 opacity-70 group-hover:opacity-100 transition-opacity">
                            <button class="text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors" title="Consultar Detalle"><i class="fas fa-eye"></i></button>
                            <button class="text-[var(--text-3)] hover:text-blue-400 transition-colors" title="Editar"><i class="fas fa-pen-to-square"></i></button>
                            <button class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors" title="Eliminar"><i class="fas fa-trash-can"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-5 flex flex-col sm:flex-row justify-between items-center text-sm gap-4">
        <span class="text-[var(--text-3)]">Mostrando <span class="text-[var(--text-1)] font-medium">1</span> a <span class="text-[var(--text-1)] font-medium">5</span> de <span class="text-[var(--text-1)] font-medium">5</span> registros estáticos</span>
        <div class="flex items-center space-x-1">
            <button class="px-3 py-1.5 rounded-md border border-[var(--surface-4)] bg-[var(--surface-2)] text-[var(--text-3)] hover:text-[var(--text-1)] hover:bg-[var(--surface-3)] transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
            <button class="px-3 py-1.5 rounded-md border border-neon-border bg-neon-muted text-[var(--neon)] font-medium transition-colors">1</button>
            <button class="px-3 py-1.5 rounded-md border border-[var(--surface-4)] bg-[var(--surface-2)] text-[var(--text-3)] hover:text-[var(--text-1)] hover:bg-[var(--surface-3)] transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
        </div>
    </div>
</div>
@endsection