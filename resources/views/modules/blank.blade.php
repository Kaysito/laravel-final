@extends('layouts.app')

@section('title', $title ?? 'Gestión de Estáticos')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-3)]">Módulos</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">{{ $title ?? 'Principal 1.1' }}</span>
@endsection

@section('styles')
<style>
/* ── Skeleton Loader ── */
.skeleton { background: linear-gradient(90deg, var(--surface-3) 25%, var(--surface-4) 50%, var(--surface-3) 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 4px; }
@keyframes shimmer { 0%{background-position:200% 0}100%{background-position:-200% 0} }

/* ── Table Row Transitions ── */
.data-row { transition: all 0.2s ease; }
.data-row:hover { background: var(--surface-3); transform: scale(1.001); }

/* ── Search Input Fix ── */
.search-wrapper { position: relative; display: flex; align-items: center; }
.search-icon { position: absolute; left: 14px; color: var(--text-3); pointer-events: none; }
.search-clear { position: absolute; right: 10px; color: var(--text-3); cursor: pointer; padding: 5px; transition: color 0.2s; display: none; }
.search-clear:hover { color: var(--neon); }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">
    
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-1)] tracking-tight">Gestión de Datos Estáticos</h1>
            <p class="text-sm text-[var(--text-3)] mt-1">Vista de demostración con datos de relleno (Mockup).</p>
        </div>
        <button type="button" id="btnNuevoEstatico" class="btn-primary flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm flex-shrink-0 shadow-lg transition-all duration-300 hover:scale-105 cursor-default">
            <i class="fas fa-plus text-xs"></i> <span>Nuevo Elemento</span>
        </button>
    </div>

    {{-- STATS STRIP --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card px-5 py-4 flex items-center gap-4 bg-[var(--surface-2)] border border-[var(--surface-4)] rounded-2xl">
            <div class="w-12 h-12 rounded-xl bg-[rgba(96,165,250,0.1)] flex items-center justify-center border border-[rgba(96,165,250,0.2)] flex-shrink-0 shadow-sm group hover:scale-105 transition-transform">
                <i class="fas fa-database text-blue-400 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Total Registrados</p>
                <p id="statTotal" class="text-2xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-5 py-4 flex items-center gap-4 bg-[var(--surface-2)] border border-[var(--surface-4)] rounded-2xl">
            <div class="w-12 h-12 rounded-xl bg-[rgba(74,222,128,0.1)] flex items-center justify-center border border-[rgba(74,222,128,0.2)] flex-shrink-0 shadow-sm group hover:scale-105 transition-transform">
                <i class="fas fa-check-circle text-green-400 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Activos</p>
                <p id="statActivos" class="text-xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-5 py-4 flex items-center gap-4 bg-[var(--surface-2)] border border-[var(--surface-4)] rounded-2xl">
            <div class="w-12 h-12 rounded-xl bg-[rgba(234,179,8,0.1)] flex items-center justify-center border border-[rgba(234,179,8,0.2)] flex-shrink-0 shadow-sm group hover:scale-105 transition-transform">
                <i class="fas fa-pause-circle text-yellow-500 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Inactivos</p>
                <p id="statInactivos" class="text-xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
    </div>

    {{-- TABLA CARD --}}
    <div class="card shadow-sm border border-[var(--surface-4)] bg-[var(--surface-2)] rounded-2xl overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="px-6 py-4 border-b border-[var(--surface-4)] flex flex-wrap items-center justify-between gap-4">
            <div class="search-wrapper w-full sm:w-80">
                <i class="fas fa-search search-icon text-sm"></i>
                <input type="text" id="buscador" placeholder="Buscar por nombre o descripción..." class="w-full bg-[var(--surface-3)] border border-[var(--surface-4)] rounded-xl pl-10 pr-10 py-2.5 text-sm text-[var(--text-1)] outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                <button class="search-clear" id="btnLimpiarBusqueda" title="Limpiar búsqueda">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <span class="text-[10px] font-mono uppercase tracking-widest px-3 py-1 rounded-full bg-[var(--surface-3)] text-[var(--text-3)] border border-[var(--surface-4)]" id="tableCount">
                Cargando...
            </span>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-[var(--surface-1)]">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-[var(--surface-2)]">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-10">#</th>
                        <th class="py-4 px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Nombre</th>
                        <th class="py-4 px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Descripción</th>
                        <th class="py-4 px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Fecha</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-24">Estado</th>
                        <th class="py-4 px-6 text-right text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody" class="divide-y divide-[var(--surface-4)] text-[var(--text-2)]">
                    {{-- Skeletons inyectados por JS --}}
                </tbody>
            </table>
        </div>

        {{-- Empty state --}}
        <div id="emptyState" class="hidden py-20 px-4 text-center bg-[var(--surface-1)]">
            <div class="w-16 h-16 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-folder-open text-2xl text-[var(--text-3)]"></i>
            </div>
            <p class="text-[var(--text-1)] font-semibold text-lg mb-1">Sin resultados</p>
            <p class="text-sm text-[var(--text-3)]">No encontramos ningún registro estático con esos datos.</p>
        </div>

        {{-- 🟢 Paginación Minimalista (<< < > >>) --}}
        <div id="paginacionWrapper" class="hidden py-4 px-6 border-t border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-mono uppercase text-[var(--text-3)] tracking-widest">
                Mostrando <span id="infoRange" class="text-[var(--text-1)] font-bold">—</span> de <span id="infoTotal" class="text-[var(--text-1)] font-bold">—</span>
            </p>
            <div id="pagBotones" class="flex items-center gap-1.5">
                {{-- Botones inyectados por JS --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── CONFIGURACIÓN (RBAC Visual) ──
    const MODULO_NAME = 'Principal1.1';
    const puedeCrear = window.tienePermiso(MODULO_NAME, 'bitAgregar');
    const puedeEliminar = window.tienePermiso(MODULO_NAME, 'bitEliminar');
    const puedeEditar = window.tienePermiso(MODULO_NAME, 'bitEditar');
    const puedeVer = window.tienePermiso(MODULO_NAME, 'bitDetalle');

    const elements = {
        btnNuevo: document.getElementById('btnNuevoEstatico'),
        buscador: document.getElementById('buscador'),
        btnLimpiar: document.getElementById('btnLimpiarBusqueda'),
        tablaBody: document.getElementById('tablaBody'),
        emptyState: document.getElementById('emptyState'),
        pagWrapper: document.getElementById('paginacionWrapper'),
        pagBotones: document.getElementById('pagBotones'),
        statTotal: document.getElementById('statTotal'),
        statActivos: document.getElementById('statActivos'),
        statInactivos: document.getElementById('statInactivos'),
        tableCount: document.getElementById('tableCount'),
        infoRange: document.getElementById('infoRange'),
        infoTotal: document.getElementById('infoTotal')
    };

    if (elements.btnNuevo && !puedeCrear) elements.btnNuevo.style.display = 'none';

    // Generar datos Lorem Ipsum aleatorios
    const dbEstaticos = Array(20).fill().map((_, i) => ({
        id: 101 + i, 
        nombre: `Lorem Ipsum Dolor ${i+1}`, 
        desc: `Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.`, 
        fecha: `2025-0${Math.floor(Math.random() * 9) + 1}-1${Math.floor(Math.random() * 9)}`, 
        estado: Math.random() > 0.3 ? 1 : 0
    }));

    let timeoutBusqueda;
    let paginaActual = 1; 
    const itemsPorPagina = 5;

    // Template para el skeleton loading (6 columnas)
    const skeletonHTML = Array(itemsPorPagina).fill().map(() => `
        <tr>
            <td class="py-4 px-6"><div class="skeleton h-4 w-6 rounded"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-4 w-32 rounded"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-4 w-48 rounded"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-4 w-20 rounded mx-auto"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-6 w-16 rounded-full mx-auto"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-8 w-20 rounded-md ml-auto"></div></td>
        </tr>
    `).join('');

    // ── Función Principal de Carga ──
    const cargarEstaticos = (pagina = 1) => {
        paginaActual = pagina;
        const busqueda = elements.buscador.value.trim().toLowerCase();

        elements.tablaBody.innerHTML = skeletonHTML;
        elements.pagWrapper.classList.add('hidden');
        elements.emptyState.classList.add('hidden');

        setTimeout(() => {
            const filtrados = dbEstaticos.filter(e => 
                (e.nombre && e.nombre.toLowerCase().includes(busqueda)) || 
                (e.desc && e.desc.toLowerCase().includes(busqueda))
            );

            const total = filtrados.length;
            const lastPage = Math.ceil(total / itemsPorPagina);
            if (paginaActual > lastPage && lastPage > 0) paginaActual = lastPage;
            
            const from = (paginaActual - 1) * itemsPorPagina;
            const to = Math.min(from + itemsPorPagina, total);
            const datosPaginados = filtrados.slice(from, to);

            const dataObj = {
                data: datosPaginados,
                total: total,
                current_page: paginaActual,
                last_page: lastPage,
                from: total === 0 ? 0 : from + 1,
                to: to
            };

            renderFull(dataObj);
        }, 300);
    };

    const renderFull = (data) => {
        renderTabla(data.data, data.from);
        renderPaginacion(data);
        
        elements.statTotal.textContent = dbEstaticos.length;
        elements.tableCount.textContent = `${data.total} REGISTROS`;
        
        const activos = dbEstaticos.filter(e => e.estado == 1).length;
        const inactivos = dbEstaticos.filter(e => e.estado == 0).length;
        elements.statActivos.textContent = activos;
        elements.statInactivos.textContent = inactivos;

        elements.btnLimpiar.style.display = elements.buscador.value.length > 0 ? 'block' : 'none';
    };

    const renderTabla = (items, fromIndex) => {
        if (!items || items.length === 0) {
            elements.tablaBody.innerHTML = '';
            elements.emptyState.classList.remove('hidden');
            elements.pagWrapper.classList.add('hidden');
            return;
        }
        elements.emptyState.classList.add('hidden');
        elements.pagWrapper.classList.remove('hidden');

        let html = '';
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            const numFila = (fromIndex || 1) + i;
            
            // Botones visuales sin acción real
            const btnVer = puedeVer 
                ? `<button type="button" class="action-btn view inline-flex items-center justify-center tooltip hover:bg-blue-500/10 hover:text-blue-400 cursor-default" data-tip="Ver detalle"><i class="fas fa-eye"></i></button>` 
                : '';

            const btnEditar = puedeEditar 
                ? `<button type="button" class="action-btn edit inline-flex items-center justify-center tooltip hover:bg-yellow-500/10 hover:text-yellow-500 cursor-default" data-tip="Editar"><i class="fas fa-pen"></i></button>` 
                : `<div class="action-btn inline-flex items-center justify-center opacity-20 cursor-not-allowed tooltip" data-tip="Protegido"><i class="fas fa-lock"></i></div>`;

            const btnEliminar = puedeEliminar 
                ? `<button type="button" class="action-btn danger inline-flex items-center justify-center tooltip hover:bg-red-500/10 hover:text-red-500 cursor-default" data-tip="Eliminar"><i class="fas fa-trash-can"></i></button>` 
                : '';

            html += `
            <tr class="data-row hover:bg-[var(--surface-3)] transition-colors">
                <td class="py-4 px-6 text-xs text-[var(--text-3)] font-mono">${numFila}</td>
                <td class="py-4 px-6 font-bold text-[var(--text-1)] text-sm">${item.nombre}</td>
                <td class="py-4 px-6">
                    <p class="text-sm text-[var(--text-2)] max-w-[200px] truncate" title="${item.desc}">${item.desc}</p>
                </td>
                <td class="py-4 px-6 text-[var(--text-3)]">${item.fecha}</td>
                <td class="py-4 px-6 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold ${item.estado == 1 ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20'}">
                        ${item.estado == 1 ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex justify-end gap-1.5">
                        ${btnVer}
                        ${btnEditar}
                        ${btnEliminar}
                    </div>
                </td>
            </tr>`;
        }
        elements.tablaBody.innerHTML = html;
    };

    const renderPaginacion = (data) => {
        elements.pagBotones.innerHTML = '';
        if (!data.last_page || data.last_page <= 1) {
            elements.pagWrapper.classList.add('hidden');
            return;
        }

        elements.infoRange.textContent = `${data.from}-${data.to}`;
        elements.infoTotal.textContent = data.total;

        const current = data.current_page;
        const last = data.last_page;

        const createBtn = (icon, page, disabled) => {
            const btn = document.createElement('button');
            btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] transition-colors ${disabled ? 'text-[var(--text-4)] bg-[var(--surface-3)] cursor-not-allowed opacity-50' : 'text-[var(--text-2)] hover:bg-[var(--surface-3)] border border-[var(--surface-4)]'}`;
            btn.innerHTML = `<i class="${icon}"></i>`;
            btn.disabled = disabled;
            if (!disabled) btn.onclick = () => cargarEstaticos(page);
            return btn;
        };

        elements.pagBotones.appendChild(createBtn('fas fa-angles-left', 1, current === 1));
        elements.pagBotones.appendChild(createBtn('fas fa-angle-left', current - 1, current === 1));
        elements.pagBotones.appendChild(createBtn('fas fa-angle-right', current + 1, current === last));
        elements.pagBotones.appendChild(createBtn('fas fa-angles-right', last, current === last));
    };

    // ── BUSCADOR ──
    elements.buscador.oninput = (e) => {
        clearTimeout(timeoutBusqueda);
        elements.btnLimpiar.style.display = e.target.value.length > 0 ? 'block' : 'none';
        timeoutBusqueda = setTimeout(() => cargarEstaticos(1), 350);
    };

    elements.btnLimpiar.onclick = () => {
        elements.buscador.value = '';
        elements.buscador.focus();
        elements.btnLimpiar.style.display = 'none';
        cargarEstaticos(1);
    };
    
    // Iniciar carga
    cargarEstaticos(1);
});
</script>
@endsection