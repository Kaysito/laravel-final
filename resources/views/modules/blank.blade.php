@extends('layouts.app')

@section('title', $title ?? 'Módulo del Sistema')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-3)]">Navegación</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">{{ $title }}</span>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">
    <div class="max-w-7xl mx-auto">

        {{-- Contenedor Principal (Tarjeta) --}}
        <div class="bg-[var(--surface-2)] border border-[var(--surface-4)] rounded-2xl shadow-lg overflow-hidden">
            
            {{-- Encabezado del Módulo --}}
            <div class="px-6 py-5 flex items-center gap-3 border-b border-[var(--surface-4)] bg-[var(--surface-2)]">
                <i class="far fa-file-lines text-blue-400 text-xl"></i>
                <h2 class="text-xl font-bold text-[var(--text-1)]">{{ $title }}</h2>
            </div>

            {{-- Tabla Estática --}}
            <div class="overflow-x-auto bg-[var(--surface-1)]">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-[var(--surface-2)] border-b border-[var(--surface-4)]">
                        <tr>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)]">#</th>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)]">ID</th>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)]">Nombre</th>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)]">Descripción</th>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)]">Fecha</th>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)] text-center">Estado</th>
                            <th class="py-4 px-6 font-bold text-[var(--text-1)] text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--surface-4)] text-[var(--text-2)]">
                        
                        {{-- Fila 1 --}}
                        <tr class="hover:bg-[var(--surface-3)] transition-colors">
                            <td class="py-4 px-6">1</td>
                            <td class="py-4 px-6">1</td>
                            <td class="py-4 px-6 font-bold text-[var(--text-1)]">Elemento Alpha</td>
                            <td class="py-4 px-6">Descripción del elemento alpha con datos de ejemplo</td>
                            <td class="py-4 px-6">2025-01-15</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold border bg-green-500/10 text-green-500 border-green-500/20">Activo</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button class="text-blue-400 hover:text-blue-300 transition-colors tooltip" data-tip="Ver"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>

                        {{-- Fila 2 --}}
                        <tr class="hover:bg-[var(--surface-3)] transition-colors">
                            <td class="py-4 px-6">2</td>
                            <td class="py-4 px-6">2</td>
                            <td class="py-4 px-6 font-bold text-[var(--text-1)]">Elemento Beta</td>
                            <td class="py-4 px-6">Descripción del elemento beta con datos de ejemplo</td>
                            <td class="py-4 px-6">2025-02-20</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold border bg-green-500/10 text-green-500 border-green-500/20">Activo</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button class="text-blue-400 hover:text-blue-300 transition-colors tooltip" data-tip="Ver"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>

                        {{-- Fila 3 --}}
                        <tr class="hover:bg-[var(--surface-3)] transition-colors">
                            <td class="py-4 px-6">3</td>
                            <td class="py-4 px-6">3</td>
                            <td class="py-4 px-6 font-bold text-[var(--text-1)]">Elemento Gamma</td>
                            <td class="py-4 px-6">Descripción del elemento gamma con datos de ejemplo</td>
                            <td class="py-4 px-6">2025-03-10</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold border bg-red-500/10 text-red-500 border-red-500/20">Inactivo</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button class="text-blue-400 hover:text-blue-300 transition-colors tooltip" data-tip="Ver"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>

                        {{-- Fila 4 --}}
                        <tr class="hover:bg-[var(--surface-3)] transition-colors">
                            <td class="py-4 px-6">4</td>
                            <td class="py-4 px-6">4</td>
                            <td class="py-4 px-6 font-bold text-[var(--text-1)]">Elemento Delta</td>
                            <td class="py-4 px-6">Descripción del elemento delta con datos de ejemplo</td>
                            <td class="py-4 px-6">2025-04-05</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold border bg-green-500/10 text-green-500 border-green-500/20">Activo</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button class="text-blue-400 hover:text-blue-300 transition-colors tooltip" data-tip="Ver"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>

                        {{-- Fila 5 --}}
                        <tr class="hover:bg-[var(--surface-3)] transition-colors">
                            <td class="py-4 px-6">5</td>
                            <td class="py-4 px-6">5</td>
                            <td class="py-4 px-6 font-bold text-[var(--text-1)]">Elemento Epsilon</td>
                            <td class="py-4 px-6">Descripción del elemento epsilon con datos de ejemplo</td>
                            <td class="py-4 px-6">2025-05-18</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold border bg-green-500/10 text-green-500 border-green-500/20">Activo</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button class="text-blue-400 hover:text-blue-300 transition-colors tooltip" data-tip="Ver"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- Paginación Estática --}}
            <div class="py-5 flex justify-center items-center gap-2 border-t border-[var(--surface-4)] bg-[var(--surface-2)]">
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-3)] hover:bg-[var(--surface-3)] transition-colors cursor-not-allowed opacity-50">
                    <i class="fas fa-chevron-left text-[10px]"></i>
                </button>
                
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-sm transition-colors">
                    1
                </button>
                
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-2)] hover:bg-[var(--surface-3)] font-medium text-xs transition-colors">
                    2
                </button>
                
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--text-3)] hover:bg-[var(--surface-3)] transition-colors cursor-pointer">
                    <i class="fas fa-chevron-right text-[10px]"></i>
                </button>
            </div>

        </div>

    </div>
</div>
@endsection