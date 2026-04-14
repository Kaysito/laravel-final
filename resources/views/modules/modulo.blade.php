@extends('layouts.app')

@section('title', 'Gestión de Módulos')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Módulos</span>
@endsection

@section('styles')
<style>
/* ── Skeleton Loader ── */
.skeleton { background: linear-gradient(90deg, var(--surface-3) 25%, var(--surface-4) 50%, var(--surface-3) 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 4px; }
@keyframes shimmer { 0%{background-position:200% 0}100%{background-position:-200% 0} }

/* ── Table Transitions ── */
.module-row { transition: background-color 0.2s ease; }
.module-row:hover { background-color: var(--surface-3); }
.child-row { transition: all 0.2s ease-in-out; }

/* ── Search Input Fix ── */
.search-wrapper { position: relative; display: flex; align-items: center; }
.search-icon { position: absolute; left: 14px; color: var(--text-3); pointer-events: none; }
.search-clear { position: absolute; right: 10px; color: var(--text-3); cursor: pointer; padding: 5px; transition: color 0.2s; display: none; }
.search-clear:hover { color: var(--neon); }

/* ── Confirm dialog ── */
.confirm-overlay { position: fixed; inset: 0; z-index: 300; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s ease; }
.confirm-overlay.open { display: flex; opacity: 1; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-1)] tracking-tight">Catálogo de Módulos</h1>
            <p class="text-sm text-[var(--text-3)] mt-1">Define y administra las secciones disponibles en el sistema.</p>
        </div>
        <a href="/modulos/nuevo" id="btnNuevoModulo" class="btn-primary flex items-center gap-2 px-5 py-2.5 text-sm flex-shrink-0 shadow-lg hover:shadow-neon-sm transition-all duration-300">
            <i class="fas fa-plus text-xs"></i> Nuevo Módulo
        </a>
    </div>

    {{-- Stats strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card px-5 py-4 flex items-center gap-4 group hover:scale-105 transition-transform">
            <div class="w-12 h-12 rounded-xl bg-[rgba(230,55,87,0.1)] flex items-center justify-center border border-[rgba(230,55,87,0.2)] flex-shrink-0 shadow-sm">
                <i class="fas fa-layer-group text-[var(--neon)] text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Módulos Padre</p>
                <p id="statTotal" class="text-2xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-5 py-4 flex items-center gap-4 group hover:scale-105 transition-transform">
            <div class="w-12 h-12 rounded-xl bg-[rgba(96,165,250,0.1)] flex items-center justify-center border border-[rgba(96,165,250,0.2)] flex-shrink-0 shadow-sm">
                <i class="fas fa-key text-blue-400 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Permisos</p>
                <p class="text-sm font-bold text-[var(--text-1)]">Estructura RBAC</p>
            </div>
        </div>
        <div class="card px-5 py-4 flex items-center gap-4 group hover:scale-105 transition-transform">
            <div class="w-12 h-12 rounded-xl bg-[rgba(74,222,128,0.1)] flex items-center justify-center border border-[rgba(74,222,128,0.2)] flex-shrink-0 shadow-sm">
                <i class="fas fa-check-double text-green-400 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Estado</p>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-bold text-[var(--text-1)]">Activo</p>
                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)] animate-pulse"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="card shadow-sm border border-[var(--surface-4)] overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="px-6 py-4 border-b border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-wrap items-center justify-between gap-4">
            <div class="search-wrapper w-full sm:w-80">
                <i class="fas fa-search search-icon text-sm"></i>
                <input type="text" id="buscador" placeholder="Buscar módulo..." class="input-field w-full pl-10 pr-10 py-2.5 text-sm transition-shadow focus:shadow-md">
                <button class="search-clear" id="btnLimpiarBusqueda" title="Limpiar búsqueda">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <span class="text-[10px] font-mono uppercase tracking-widest px-3 py-1 rounded-full bg-[var(--surface-3)] text-[var(--text-3)] border border-[var(--surface-4)]" id="tableCount">
                Cargando...
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto bg-[var(--surface-1)]">
            <table class="w-full text-sm whitespace-nowrap">
                <thead class="bg-[var(--surface-2)] border-b border-[var(--surface-4)]">
                    <tr>
                        <th class="py-4 px-4 w-10"></th>
                        <th class="py-4 px-6 text-left text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase">Estructura del Módulo</th>
                        <th class="py-4 px-6 text-right text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody" class="divide-y divide-[var(--surface-4)]">
                    {{-- Skeletons inyectados por JS --}}
                </tbody>
            </table>
        </div>

        <div id="emptyState" class="hidden py-20 px-4 text-center bg-[var(--surface-1)]">
            <div class="w-16 h-16 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-cubes text-2xl text-[var(--text-3)]"></i>
            </div>
            <p class="text-[var(--text-1)] font-semibold text-lg mb-1">Sin resultados</p>
            <p class="text-sm text-[var(--text-3)]">No encontramos módulos con ese criterio.</p>
        </div>

        {{-- 🟢 Paginación --}}
        <div id="paginacionWrapper" class="hidden px-6 py-4 border-t border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[10px] font-mono uppercase text-[var(--text-3)] tracking-widest">
                Mostrando <span id="infoRange" class="text-[var(--text-1)] font-bold">—</span> de <span id="infoTotal" class="text-[var(--text-1)] font-bold">—</span> padres
            </p>
            <div id="pagBotones" class="flex items-center gap-1.5"></div>
        </div>
    </div>
</div>

{{-- CONFIRMAR ELIMINACIÓN --}}
<div id="modalEliminar" class="confirm-overlay">
    <div id="eliminarContent" class="bg-[var(--surface-1)] border border-[var(--surface-4)] rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform transition-all scale-95 opacity-0">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-[var(--text-1)] mb-2">¿Eliminar módulo?</h3>
            <p class="text-sm text-[var(--text-3)] mb-6">¿Deseas eliminar el módulo <strong id="nombreEliminar" class="text-[var(--text-1)]"></strong>?<br>Esta acción es irreversible.</p>
            <div class="flex gap-3 justify-center">
                <button id="btnCancelarEliminar" class="btn-ghost flex-1 py-2.5 text-sm font-medium border border-[var(--surface-4)]">Cancelar</button>
                <button id="btnConfirmarEliminar" class="flex-1 py-2.5 text-sm font-bold rounded-lg bg-red-500 hover:bg-red-600 text-white shadow-lg transition-colors">Sí, eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const puedeCrear = window.tienePermiso('Modulo', 'bitAgregar');
    const puedeEditar = window.tienePermiso('Modulo', 'bitEditar');
    const puedeEliminar = window.tienePermiso('Modulo', 'bitEliminar');
    const puedeDetalle = window.tienePermiso('Modulo', 'bitDetalle');

    const elements = {
        btnNuevo: document.getElementById('btnNuevoModulo'),
        buscador: document.getElementById('buscador'),
        btnLimpiar: document.getElementById('btnLimpiarBusqueda'),
        tablaBody: document.getElementById('tablaBody'),
        emptyState: document.getElementById('emptyState'),
        pagWrapper: document.getElementById('paginacionWrapper'),
        pagBotones: document.getElementById('pagBotones'),
        modalEliminar: document.getElementById('modalEliminar'),
        eliminarContent: document.getElementById('eliminarContent'),
        statTotal: document.getElementById('statTotal'),
        tableCount: document.getElementById('tableCount'),
        infoRange: document.getElementById('infoRange'),
        infoTotal: document.getElementById('infoTotal')
    };

    if (elements.btnNuevo && !puedeCrear) elements.btnNuevo.style.display = 'none';

    let idEliminar = null;
    let timeoutBusqueda;
    let paginaActual = 1;
    const localCache = new Map(); 

    const skeletonHTML = Array(5).fill().map(() => `
        <tr>
            <td class="py-4 px-4"><div class="skeleton h-4 w-4 rounded"></div></td>
            <td class="py-4 px-6"><div class="flex items-center gap-3"><div class="skeleton w-8 h-8 rounded-lg"></div><div class="skeleton h-4 w-48 rounded"></div></div></td>
            <td class="py-4 px-6"><div class="skeleton h-8 w-24 rounded-md ml-auto"></div></td>
        </tr>
    `).join('');

    const cargarModulos = async (pagina = 1, busqueda = elements.buscador.value.trim(), silencioso = false) => {
        paginaActual = pagina;
        const cacheKey = `${pagina}-${busqueda}`;
        
        if (!silencioso && localCache.has(cacheKey)) {
            renderFull(localCache.get(cacheKey));
            return;
        }

        if (!silencioso) {
            elements.tablaBody.innerHTML = skeletonHTML;
            elements.pagWrapper.classList.add('hidden');
        }

        try {
            const res = await fetch(`/api/modulos?page=${pagina}&buscar=${encodeURIComponent(busqueda)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            
            const cacheActual = localCache.get(cacheKey);
            const datosCambiaron = JSON.stringify(cacheActual?.data) !== JSON.stringify(data.data);

            if (datosCambiaron || !silencioso) {
                localCache.set(cacheKey, data);
                renderFull(data);
            }
        } catch (err) { 
            console.error(err); 
        }
    };

    const renderFull = (data) => {
        renderTabla(data.data || []);
        renderPaginacion(data);
        
        elements.statTotal.textContent = data.total || 0;
        elements.tableCount.textContent = `${data.total || 0} PADRES`;
        elements.btnLimpiar.style.display = elements.buscador.value.length > 0 ? 'block' : 'none';
    };

    // 🚀 LÓGICA DE ACORDEÓN (PADRE E HIJOS) REPARADA 🚀
    const renderTabla = (modulos) => {
        if (modulos.length === 0) {
            elements.tablaBody.innerHTML = '';
            elements.emptyState.classList.remove('hidden');
            elements.pagWrapper.classList.add('hidden');
            return;
        }
        elements.emptyState.classList.add('hidden');
        elements.pagWrapper.classList.remove('hidden');

        // 1. Agrupar la data actual de forma inteligente
        const grupos = {};
        
        modulos.forEach(m => {
            // Un módulo es "Padre" SI y SÓLO SI:
            // 1. Su strGrupo está vacío (null o "")
            // 2. O su strGrupo es exactamente igual a su strNombreModulo
            const isPadre = !m.strGrupo || m.strGrupo.trim() === '' || m.strGrupo.trim().toLowerCase() === m.strNombreModulo.trim().toLowerCase();
            
            // El nombre de la "Carpeta" donde debe ir:
            const grupoName = isPadre ? m.strNombreModulo.trim() : m.strGrupo.trim();

            if (!grupos[grupoName]) {
                grupos[grupoName] = { parent: null, children: [] };
            }

            if (isPadre) {
                // Si encontramos al Padre real de esta familia, lo asignamos.
                grupos[grupoName].parent = m;
            } else {
                // Si no es padre, forzosamente es un hijo que va dentro de esa carpeta.
                grupos[grupoName].children.push(m);
            }
        });

        // 2. Generar HTML
        let html = '';
        Object.keys(grupos).forEach(grupoName => {
            const group = grupos[grupoName];
            const hasParent = group.parent !== null;
            
            // Si no nos llegó el objeto Padre desde la DB (por la paginación), creamos uno "Virtual"
            const parentModulo = group.parent || { id: '-', strNombreModulo: grupoName, strIcono: 'fas fa-folder-open' };
            const groupId = `grp-${grupoName.replace(/[^a-zA-Z0-9]/g, '-')}`;

            // Botones de Acción para el Padre (Solo si es un registro real de la BD)
            let btnVerP = '', btnEditarP = '', btnEliminarP = '';
            if (hasParent) {
                btnVerP = puedeDetalle ? `<a href="/modulos/${parentModulo.id}/detalle" class="action-btn view tooltip hover:text-blue-400" data-tip="Ver"><i class="fas fa-eye text-xs"></i></a>` : '';
                btnEditarP = puedeEditar ? `<a href="/modulos/${parentModulo.id}/editar" class="action-btn edit tooltip hover:text-yellow-500" data-tip="Editar"><i class="fas fa-pen text-xs"></i></a>` : '';
                btnEliminarP = puedeEliminar ? `<button data-action="delete" data-id="${parentModulo.id}" data-name="${parentModulo.strNombreModulo}" class="action-btn danger tooltip hover:text-red-500" data-tip="Eliminar"><i class="fas fa-trash-can text-xs"></i></button>` : '';
            }

            // DIBUJAR FILA PADRE
            html += `
            <tr class="module-row cursor-pointer bg-[var(--surface-1)] hover:bg-[var(--surface-2)]" data-toggle="${groupId}">
                <td class="py-4 px-4 w-10 text-center select-none">
                    ${group.children.length > 0 ? `<i class="fas fa-chevron-right text-[var(--text-3)] transition-transform duration-200" id="icon-${groupId}"></i>` : '<i class="fas fa-minus text-[var(--surface-4)] text-[10px]"></i>'}
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[var(--surface-3)] flex items-center justify-center border border-[var(--surface-4)] text-[var(--neon)]">
                            <i class="${parentModulo.strIcono || 'fas fa-cube'} text-sm"></i>
                        </div>
                        <span class="font-bold text-[var(--text-1)] text-sm select-none">${parentModulo.strNombreModulo}</span>
                        ${!hasParent ? '<span class="text-[9px] bg-yellow-500/10 text-yellow-500 px-2 py-0.5 rounded-full ml-2">Agrupación Virtual</span>' : ''}
                    </div>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-2">${btnVerP} ${btnEditarP} ${btnEliminarP}</div>
                </td>
            </tr>`;

            // DIBUJAR FILAS HIJOS
            group.children.forEach(child => {
                const btnVerC = puedeDetalle ? `<a href="/modulos/${child.id}/detalle" class="action-btn view tooltip hover:text-blue-400" data-tip="Ver"><i class="fas fa-eye text-xs"></i></a>` : '';
                const btnEditarC = puedeEditar ? `<a href="/modulos/${child.id}/editar" class="action-btn edit tooltip hover:text-yellow-500" data-tip="Editar"><i class="fas fa-pen text-xs"></i></a>` : '';
                const btnEliminarC = puedeEliminar ? `<button data-action="delete" data-id="${child.id}" data-name="${child.strNombreModulo}" class="action-btn danger tooltip hover:text-red-500" data-tip="Eliminar"><i class="fas fa-trash-can text-xs"></i></button>` : '';

                html += `
                <tr class="child-row hidden child-${groupId} bg-[var(--surface-2)]">
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-6 pl-12">
                        <div class="flex items-center gap-3 relative">
                            <div class="absolute -left-6 top-1/2 w-4 h-[1px] bg-[var(--surface-4)]"></div> <i class="${child.strIcono || 'fas fa-cube'} text-[var(--text-3)] text-xs"></i>
                            <span class="text-[var(--text-2)] text-sm">${child.strNombreModulo}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">${btnVerC} ${btnEditarC} ${btnEliminarC}</div>
                    </td>
                </tr>`;
            });
        });
        
        elements.tablaBody.innerHTML = html;
    };

    // ── Lógica de Eventos ──
    elements.tablaBody.addEventListener('click', (e) => {
        const toggleRow = e.target.closest('tr[data-toggle]');
        if (toggleRow && !e.target.closest('button') && !e.target.closest('a')) {
            const groupId = toggleRow.dataset.toggle;
            const children = document.querySelectorAll(`.child-${groupId}`);
            const icon = document.getElementById(`icon-${groupId}`);

            if (children.length > 0) {
                const isHidden = children[0].classList.contains('hidden');
                children.forEach(child => {
                    if (isHidden) {
                        child.classList.remove('hidden');
                        child.classList.add('table-row');
                    } else {
                        child.classList.add('hidden');
                        child.classList.remove('table-row');
                    }
                });
                if (icon) icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
            }
        }

        const btnDelete = e.target.closest('button[data-action="delete"]');
        if (btnDelete) {
            const { id, name } = btnDelete.dataset;
            idEliminar = id;
            document.getElementById('nombreEliminar').textContent = name;
            elements.modalEliminar.classList.add('open');
            setTimeout(() => elements.eliminarContent.classList.remove('scale-95', 'opacity-0'), 10);
        }
    });

    const renderPaginacion = (data) => {
        elements.pagBotones.innerHTML = '';
        if (!data.last_page || data.last_page <= 1) return;

        elements.infoRange.textContent = `${data.from}-${data.to}`;
        elements.infoTotal.textContent = data.total;

        const current = data.current_page;
        const last = data.last_page;

        const createBtn = (icon, page, disabled) => {
            const btn = document.createElement('button');
            btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] transition-colors ${disabled ? 'text-[var(--text-4)] bg-[var(--surface-3)] cursor-not-allowed opacity-50' : 'text-[var(--text-2)] hover:bg-[var(--surface-3)] border border-[var(--surface-4)]'}`;
            btn.innerHTML = `<i class="${icon}"></i>`;
            btn.disabled = disabled;
            if (!disabled) btn.onclick = () => cargarModulos(page);
            return btn;
        };

        elements.pagBotones.appendChild(createBtn('fas fa-angles-left', 1, current === 1));
        elements.pagBotones.appendChild(createBtn('fas fa-angle-left', current - 1, current === 1));
        elements.pagBotones.appendChild(createBtn('fas fa-angle-right', current + 1, current === last));
        elements.pagBotones.appendChild(createBtn('fas fa-angles-right', last, current === last));
    };

    document.getElementById('btnConfirmarEliminar').onclick = async () => {
        try {
            const res = await fetch(`/api/modulos/${idEliminar}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (data.success) {
                elements.modalEliminar.classList.remove('open');
                localCache.clear();
                cargarModulos();
                if(window.showToast) window.showToast('Módulo eliminado', 'success');
            }
        } catch (err) { console.error(err); }
    };

    document.getElementById('btnCancelarEliminar').onclick = () => {
        elements.eliminarContent.classList.remove('scale-100', 'opacity-100');
        elements.eliminarContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => elements.modalEliminar.classList.remove('open'), 200);
    };
    
    elements.buscador.oninput = (e) => {
        clearTimeout(timeoutBusqueda);
        elements.btnLimpiar.style.display = e.target.value.length > 0 ? 'block' : 'none';
        timeoutBusqueda = setTimeout(() => cargarModulos(1), 350);
    };

    elements.btnLimpiar.onclick = () => {
        elements.buscador.value = '';
        elements.buscador.focus();
        elements.btnLimpiar.style.display = 'none';
        cargarModulos(1);
    };

    cargarModulos();

    setInterval(() => {
        const busquedaActiva = elements.buscador.value.trim() !== '';
        if (paginaActual === 1 && !busquedaActiva) {
            cargarModulos(1, '', true); 
        }
    }, 4000);

});
</script>
@endsection