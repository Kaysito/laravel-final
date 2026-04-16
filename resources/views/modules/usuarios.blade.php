@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Usuarios</span>
@endsection

@section('styles')
<style>
/* ── Skeleton Loader ── */
.skeleton { background: linear-gradient(90deg, var(--surface-3) 25%, var(--surface-4) 50%, var(--surface-3) 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 4px; }
@keyframes shimmer { 0%{background-position:200% 0}100%{background-position:-200% 0} }

/* ── Table Row Transitions ── */
.user-row { transition: background 0.15s ease; }
.user-row:hover { background: var(--surface-3); }

/* ── Search Input Fix ── */
.search-wrapper { position: relative; display: flex; align-items: center; }
.search-icon { position: absolute; left: 14px; color: var(--text-3); pointer-events: none; z-index: 1; }
.search-clear { position: absolute; right: 10px; color: var(--text-3); cursor: pointer; padding: 5px; transition: color 0.2s; display: none; background: none; border: none; line-height: 1; }
.search-clear:hover { color: var(--neon); }

/* ── Confirm dialog ── */
.confirm-overlay { position: fixed; inset: 0; z-index: 300; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; }
.confirm-overlay.open { display: flex; }
.confirm-overlay .modal-box { transform: scale(0.95); opacity: 0; transition: transform 0.2s ease, opacity 0.2s ease; }
.confirm-overlay.open .modal-box { transform: scale(1); opacity: 1; }

/* ── Responsive table: ocultar columnas secundarias ── */
@media (max-width: 768px) {
    .col-contacto, .col-2fa { display: none; }
    .col-perfil { display: none; }
}
@media (max-width: 480px) {
    .col-num { display: none; }
    .action-btn span { display: none; }
}

/* ── Stat strip mobile ── */
@media (max-width: 640px) {
    .stat-icon { width: 2.25rem !important; height: 2.25rem !important; }
    .stat-icon i { font-size: 0.875rem !important; }
    .stat-val { font-size: 1.25rem !important; }
}

/* ── Paginación ── */
.pag-btn { min-width: 2rem; height: 2rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 12px; transition: background 0.15s; cursor: pointer; border: 1px solid var(--surface-4); color: var(--text-2); background: transparent; }
.pag-btn:hover:not(:disabled) { background: var(--surface-3); }
.pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.pag-btn.active { background: var(--neon); color: #000; border-color: var(--neon); font-weight: 700; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 fade-in">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-1)] tracking-tight">Gestión de Usuarios</h1>
            <p class="text-xs sm:text-sm text-[var(--text-3)] mt-0.5">Administra el personal, sus accesos y el estado de sus cuentas.</p>
        </div>
        <a href="{{ route('usuarios.crear') }}" id="btnNuevoUsuario"
           class="btn-primary flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 text-sm flex-shrink-0 shadow-lg hover:shadow-neon-sm transition-all duration-300 self-start sm:self-auto">
            <i class="fas fa-user-plus text-xs"></i>
            <span>Nuevo Usuario</span>
        </a>
    </div>

    {{-- STATS STRIP --}}
    <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="card px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-3 sm:gap-4">
            <div class="stat-icon w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-[rgba(96,165,250,0.1)] flex items-center justify-center border border-[rgba(96,165,250,0.2)] flex-shrink-0">
                <i class="fas fa-users text-blue-400 text-base sm:text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[9px] sm:text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5 truncate">Total</p>
                <p id="statTotal" class="stat-val text-xl sm:text-2xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-3 sm:gap-4">
            <div class="stat-icon w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-[rgba(74,222,128,0.1)] flex items-center justify-center border border-[rgba(74,222,128,0.2)] flex-shrink-0">
                <i class="fas fa-user-check text-green-400 text-base sm:text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[9px] sm:text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5 truncate">Activos</p>
                <p id="statActivos" class="stat-val text-lg sm:text-xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-3 sm:gap-4">
            <div class="stat-icon w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-[rgba(234,179,8,0.1)] flex items-center justify-center border border-[rgba(234,179,8,0.2)] flex-shrink-0">
                <i class="fas fa-user-clock text-yellow-500 text-base sm:text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[9px] sm:text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5 truncate">Inactivos</p>
                <p id="statInactivos" class="stat-val text-lg sm:text-xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
    </div>

    {{-- TABLA CARD --}}
    <div class="card shadow-sm border border-[var(--surface-4)] overflow-hidden">

        {{-- Toolbar --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-wrap items-center justify-between gap-3">
            <div class="search-wrapper w-full sm:w-80">
                <i class="fas fa-search search-icon text-sm"></i>
                <input type="text" id="buscador" placeholder="Buscar por nombre o correo..."
                       class="input-field w-full pl-10 pr-10 py-2.5 text-sm transition-shadow focus:shadow-md"
                       autocomplete="off" spellcheck="false">
                <button class="search-clear" id="btnLimpiarBusqueda" title="Limpiar búsqueda" type="button">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <span class="text-[10px] font-mono uppercase tracking-widest px-3 py-1 rounded-full bg-[var(--surface-3)] text-[var(--text-3)] border border-[var(--surface-4)] whitespace-nowrap" id="tableCount">
                Cargando...
            </span>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-[var(--surface-1)]">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--surface-2)]">
                    <tr>
                        <th class="col-num py-3 px-4 sm:py-4 sm:px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-10">#</th>
                        <th class="py-3 px-4 sm:py-4 sm:px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Usuario</th>
                        <th class="col-contacto py-3 px-4 sm:py-4 sm:px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Contacto</th>
                        <th class="col-perfil py-3 px-4 sm:py-4 sm:px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Perfil</th>
                        <th class="col-2fa py-3 px-4 sm:py-4 sm:px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">2FA</th>
                        <th class="py-3 px-4 sm:py-4 sm:px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-24">Estado</th>
                        <th class="py-3 px-4 sm:py-4 sm:px-6 text-right text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody" class="divide-y divide-[var(--surface-4)]">
                    {{-- Skeletons inyectados por JS --}}
                </tbody>
            </table>
        </div>

        {{-- Empty state --}}
        <div id="emptyState" class="hidden py-16 sm:py-20 px-4 text-center bg-[var(--surface-1)]">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-users-slash text-xl sm:text-2xl text-[var(--text-3)]"></i>
            </div>
            <p class="text-[var(--text-1)] font-semibold text-base sm:text-lg mb-1">Sin resultados</p>
            <p id="emptyMsg" class="text-sm text-[var(--text-3)]">No encontramos ningún usuario con esos datos.</p>
        </div>

        {{-- Paginación --}}
        <div id="paginacionWrapper" class="hidden py-3 sm:py-4 px-4 sm:px-6 border-t border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-col sm:flex-row justify-between items-center gap-3">
            <p class="text-[10px] font-mono uppercase text-[var(--text-3)] tracking-widest">
                Mostrando <span id="infoRange" class="text-[var(--text-1)] font-bold">—</span> de <span id="infoTotal" class="text-[var(--text-1)] font-bold">—</span>
            </p>
            <div id="pagBotones" class="flex items-center gap-1.5 flex-wrap justify-center">
                {{-- Botones inyectados por JS --}}
            </div>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="modalEliminar" class="confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="modalEliminarTitle">
    <div id="eliminarContent" class="modal-box bg-[var(--surface-1)] border border-[var(--surface-4)] rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-xmark text-red-500 text-2xl"></i>
            </div>
            <h3 id="modalEliminarTitle" class="text-lg font-bold text-[var(--text-1)] mb-2">¿Eliminar usuario?</h3>
            <p class="text-sm text-[var(--text-3)] mb-6">
                ¿Deseas eliminar a <strong id="nombreEliminar" class="text-[var(--text-1)]"></strong>?<br>
                Esta acción es permanente.
            </p>
            <div class="flex gap-3 justify-center">
                <button id="btnCancelarEliminar" type="button"
                        class="btn-ghost flex-1 py-2.5 text-sm font-medium border border-[var(--surface-4)]">
                    Cancelar
                </button>
                <button id="btnConfirmarEliminar" type="button"
                        class="flex-1 py-2.5 text-sm font-bold rounded-lg bg-red-500 hover:bg-red-600 active:bg-red-700 text-white shadow-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // 🚀 CORRECCIÓN: Nombre exacto de la base de datos
    const nombreEnBD = 'Usuarios'; 

    // ── RBAC ──
    const puedeCrear    = window.tienePermiso(nombreEnBD, 'bitAgregar');
    const puedeEliminar = window.tienePermiso(nombreEnBD, 'bitEliminar');
    const puedeEditar   = window.tienePermiso(nombreEnBD, 'bitEditar');
    const puedeDetalle  = window.tienePermiso(nombreEnBD, 'bitDetalle'); // 🚀 Agregado

    const el = {
        btnNuevo:       document.getElementById('btnNuevoUsuario'),
        buscador:       document.getElementById('buscador'),
        btnLimpiar:     document.getElementById('btnLimpiarBusqueda'),
        tablaBody:      document.getElementById('tablaBody'),
        emptyState:     document.getElementById('emptyState'),
        emptyMsg:       document.getElementById('emptyMsg'),
        pagWrapper:     document.getElementById('paginacionWrapper'),
        pagBotones:     document.getElementById('pagBotones'),
        statTotal:      document.getElementById('statTotal'),
        statActivos:    document.getElementById('statActivos'),
        statInactivos:  document.getElementById('statInactivos'),
        modal:          document.getElementById('modalEliminar'),
        tableCount:     document.getElementById('tableCount'),
        infoRange:      document.getElementById('infoRange'),
        infoTotal:      document.getElementById('infoTotal'),
        btnConfirmar:   document.getElementById('btnConfirmarEliminar'),
        btnCancelar:    document.getElementById('btnCancelarEliminar'),
    };

    if (el.btnNuevo && !puedeCrear) el.btnNuevo.style.display = 'none';

    let idEliminar      = null;
    let timeoutBusqueda;
    let paginaActual    = 1;
    let totalActivosGlobal   = 0;
    let totalInactivosGlobal = 0;
    const localCache    = new Map();

    // ── Skeletons ──
    const skeletonRow = () => `
        <tr>
            <td class="col-num py-3 px-4 sm:py-4 sm:px-6"><div class="skeleton h-4 w-6 rounded"></div></td>
            <td class="py-3 px-4 sm:py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="skeleton w-9 h-9 sm:w-10 sm:h-10 rounded-full flex-shrink-0"></div>
                    <div class="skeleton h-4 w-28 sm:w-32 rounded"></div>
                </div>
            </td>
            <td class="col-contacto py-3 px-4 sm:py-4 sm:px-6"><div class="skeleton h-4 w-28 rounded"></div></td>
            <td class="col-perfil py-3 px-4 sm:py-4 sm:px-6"><div class="skeleton h-6 w-20 rounded-full mx-auto"></div></td>
            <td class="col-2fa py-3 px-4 sm:py-4 sm:px-6"><div class="skeleton h-6 w-16 rounded-full mx-auto"></div></td>
            <td class="py-3 px-4 sm:py-4 sm:px-6"><div class="skeleton h-6 w-16 rounded-full mx-auto"></div></td>
            <td class="py-3 px-4 sm:py-4 sm:px-6"><div class="skeleton h-8 w-16 rounded-md ml-auto"></div></td>
        </tr>`;
    const skeletonHTML = Array(5).fill().map(skeletonRow).join('');

    // ── Cargar ──
    const cargarUsuarios = async (pagina = 1, busqueda = el.buscador.value.trim(), silencioso = false) => {
        paginaActual = pagina;
        const cacheKey = `${pagina}-${busqueda}`;

        if (!silencioso && localCache.has(cacheKey)) {
            renderFull(localCache.get(cacheKey));
            return;
        }

        if (!silencioso) {
            el.tablaBody.innerHTML = skeletonHTML;
            el.pagWrapper.classList.add('hidden');
            el.emptyState.classList.add('hidden');
        }

        try {
            const res = await fetch(`/api/usuarios?page=${pagina}&buscar=${encodeURIComponent(busqueda)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            const datosCambiaron = JSON.stringify(localCache.get(cacheKey)?.data) !== JSON.stringify(data.data);
            if (datosCambiaron || !silencioso) {
                // Prevenir fugas de memoria limitando la caché a 20 entradas
                if (localCache.size > 20) {
                    localCache.delete(localCache.keys().next().value);
                }
                localCache.set(cacheKey, data);
                
                requestAnimationFrame(() => renderFull(data));
            }
        } catch (err) {
            console.error('Error cargando usuarios:', err);
            if (!silencioso) {
                el.tablaBody.innerHTML = '';
                el.emptyMsg.textContent = 'Error al cargar. Intenta de nuevo.';
                el.emptyState.classList.remove('hidden');
            }
        }
    };

    const renderFull = (data) => {
        renderTabla(data.data || [], data.from);
        renderPaginacion(data);

        const busqueda = el.buscador.value.trim();
        if (paginaActual === 1 && !busqueda) {
            el.statTotal.textContent    = data.total ?? 0;
            el.tableCount.textContent   = `${data.total ?? 0} REGISTROS`;
            
            if (data.meta_activos !== undefined) {
                totalActivosGlobal   = data.meta_activos;
                totalInactivosGlobal = data.meta_inactivos ?? 0;
            } else {
                totalActivosGlobal   = (data.data || []).filter(u => u.idEstadoUsuario == 1).length;
                totalInactivosGlobal = (data.data || []).filter(u => u.idEstadoUsuario == 0).length;
            }
            el.statActivos.textContent   = totalActivosGlobal;
            el.statInactivos.textContent = totalInactivosGlobal;
        } else {
            const total = data.total ?? (data.data?.length ?? 0);
            el.statTotal.textContent  = total;
            el.tableCount.textContent = `${total} RESULTADO${total !== 1 ? 'S' : ''}`;
        }

        el.btnLimpiar.style.display = el.buscador.value.length > 0 ? 'block' : 'none';
    };

    const renderTabla = (usuarios, fromIndex) => {
        if (!usuarios || usuarios.length === 0) {
            el.tablaBody.innerHTML = '';
            el.emptyMsg.textContent = el.buscador.value.trim()
                ? 'No encontramos usuarios con esa búsqueda.'
                : 'Aún no hay usuarios registrados.';
            el.emptyState.classList.remove('hidden');
            el.pagWrapper.classList.add('hidden');
            return;
        }
        el.emptyState.classList.add('hidden');

        const htmlRows = [];
        
        for (let i = 0; i < usuarios.length; i++) {
            const u       = usuarios[i];
            const numFila = (fromIndex || 1) + i;
            const esAdmin = u.id === 1;

            const initials = (u.strNombreUsuario || '??').substring(0, 2).toUpperCase();
            const avatar   = u.strImagen
                ? `<img src="${u.strImagen}-/scale_crop/80x80/center/" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border border-[var(--surface-4)] flex-shrink-0" loading="lazy" alt="${initials}">`
                : `<div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center text-xs font-bold text-white shadow-sm flex-shrink-0">${initials}</div>`;

            // 🚀 CORRECCIÓN: Renderizado condicional de los 3 botones basado en permisos
            let btnVer = '', btnEditar = '', btnEliminar = '';

            if (puedeDetalle) {
                btnVer = `<a href="/usuarios/${u.id}/detalle" class="action-btn view tooltip hover:bg-blue-500/10 hover:text-blue-400" data-tip="Ver detalle" aria-label="Ver detalle"><i class="fas fa-eye"></i></a>`;
            }

            if (esAdmin) {
                btnEditar = `<div class="action-btn opacity-20 cursor-not-allowed tooltip" data-tip="Protegido" aria-label="Protegido"><i class="fas fa-user-shield"></i></div>`;
            } else {
                if (puedeEditar) {
                    btnEditar = `<a href="/usuarios/${u.id}/editar" class="action-btn edit tooltip hover:bg-yellow-500/10 hover:text-yellow-500" data-tip="Editar" aria-label="Editar usuario"><i class="fas fa-user-pen"></i></a>`;
                }
                if (puedeEliminar) {
                    btnEliminar = `<button type="button" data-action="delete" data-id="${u.id}" data-name="${u.strNombreUsuario}" class="action-btn danger tooltip hover:bg-red-500/10 hover:text-red-500" data-tip="Eliminar" aria-label="Eliminar usuario"><i class="fas fa-trash-can"></i></button>`;
                }
            }

            htmlRows.push(`
            <tr class="user-row">
                <td class="col-num py-3 px-4 sm:py-4 sm:px-6 text-xs text-[var(--text-3)] font-mono">${numFila}</td>
                <td class="py-3 px-4 sm:py-4 sm:px-6">
                    <div class="flex items-center gap-2 sm:gap-3">
                        ${avatar}
                        <div class="min-w-0">
                            <p class="font-bold text-[var(--text-1)] text-sm truncate max-w-[120px] sm:max-w-none">${u.strNombreUsuario}</p>
                        </div>
                    </div>
                </td>
                <td class="col-contacto py-3 px-4 sm:py-4 sm:px-6">
                    <p class="text-sm text-[var(--text-2)] truncate max-w-[160px]">${u.strCorreo}</p>
                    <p class="text-[10px] text-[var(--text-3)]">${u.strNumeroCelular || '—'}</p>
                </td>
                <td class="col-perfil py-3 px-4 sm:py-4 sm:px-6 text-center">
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-[var(--surface-3)] text-[var(--text-2)] border border-[var(--surface-4)] whitespace-nowrap">
                        ${u.perfil ? u.perfil.strNombrePerfil : 'Sin perfil'}
                    </span>
                </td>
                <td class="col-2fa py-3 px-4 sm:py-4 sm:px-6 text-center">
                    <span class="text-[10px] ${u.google2fa_secret ? 'text-blue-400' : 'text-[var(--text-3)]'} whitespace-nowrap">
                        <i class="fas fa-shield-halved mr-1"></i>${u.google2fa_secret ? 'Activo' : 'Off'}
                    </span>
                </td>
                <td class="py-3 px-4 sm:py-4 sm:px-6 text-center">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold whitespace-nowrap ${u.idEstadoUsuario == 1 ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20'}">
                        ${u.idEstadoUsuario == 1 ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td class="py-3 px-4 sm:py-4 sm:px-6">
                    <div class="flex justify-end gap-1.5 sm:gap-2">
                        ${btnVer}
                        ${btnEditar}
                        ${btnEliminar}
                    </div>
                </td>
            </tr>`);
        }
        el.tablaBody.innerHTML = htmlRows.join('');
    };

    const renderPaginacion = (data) => {
        el.pagBotones.innerHTML = '';
        el.infoRange.textContent = data.from && data.to ? `${data.from}–${data.to}` : '—';
        el.infoTotal.textContent = data.total ?? '—';
        el.pagWrapper.classList.remove('hidden');

        if (!data.last_page || data.last_page <= 1) return;

        const current = data.current_page;
        const last    = data.last_page;
        const frag    = document.createDocumentFragment();

        const mkBtn = (icon, page, disabled, title) => {
            const btn = document.createElement('button');
            btn.type      = 'button';
            btn.className = 'pag-btn';
            btn.innerHTML = `<i class="${icon}"></i>`;
            btn.disabled  = disabled;
            btn.title     = title;
            if (!disabled) btn.onclick = () => cargarUsuarios(page);
            return btn;
        };

        frag.appendChild(mkBtn('fas fa-angles-left',  1,           current === 1,    'Primera'));
        frag.appendChild(mkBtn('fas fa-angle-left',   current - 1, current === 1,    'Anterior'));

        const range = 2;
        const from  = Math.max(1, current - range);
        const to    = Math.min(last, current + range);
        
        if (from > 1) {
            const span = document.createElement('span');
            span.className = 'text-[var(--text-3)] text-xs px-1';
            span.textContent = '…';
            frag.appendChild(span);
        }
        for (let p = from; p <= to; p++) {
            const btn = document.createElement('button');
            btn.type      = 'button';
            btn.className = `pag-btn${p === current ? ' active' : ''}`;
            btn.textContent = p;
            btn.disabled  = p === current;
            btn.title     = `Página ${p}`;
            if (p !== current) btn.onclick = () => cargarUsuarios(p);
            frag.appendChild(btn);
        }
        if (to < last) {
            const span = document.createElement('span');
            span.className = 'text-[var(--text-3)] text-xs px-1';
            span.textContent = '…';
            frag.appendChild(span);
        }

        frag.appendChild(mkBtn('fas fa-angle-right',  current + 1, current === last, 'Siguiente'));
        frag.appendChild(mkBtn('fas fa-angles-right', last,        current === last, 'Última'));
        
        el.pagBotones.appendChild(frag);
    };

    // ── Búsqueda ──
    el.buscador.oninput = (e) => {
        clearTimeout(timeoutBusqueda);
        el.btnLimpiar.style.display = e.target.value.length > 0 ? 'block' : 'none';
        timeoutBusqueda = setTimeout(() => cargarUsuarios(1), 300);
    };

    el.btnLimpiar.onclick = () => {
        el.buscador.value = '';
        el.buscador.focus();
        el.btnLimpiar.style.display = 'none';
        cargarUsuarios(1);
    };

    // ── Delegación click tabla (eliminar) ──
    el.tablaBody.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action="delete"]');
        if (!btn) return;
        idEliminar = btn.dataset.id;
        document.getElementById('nombreEliminar').textContent = btn.dataset.name;
        abrirModal();
    });

    // ── Modal ──
    const abrirModal = () => {
        el.modal.classList.add('open');
        el.btnConfirmar.disabled = false;
        setTimeout(() => el.btnCancelar.focus(), 50);
    };

    const cerrarModal = () => {
        el.modal.classList.remove('open');
        idEliminar = null;
    };

    el.btnConfirmar.onclick = async () => {
        if (!idEliminar) return;
        el.btnConfirmar.disabled = true;
        el.btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Eliminando…';
        try {
            const res = await fetch(`/api/usuarios/${idEliminar}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const json = await res.json();
            if (json.success) {
                cerrarModal();
                localCache.clear();
                cargarUsuarios();
                if (window.showToast) window.showToast('Usuario eliminado', 'success');
            } else {
                el.btnConfirmar.disabled = false;
                el.btnConfirmar.textContent = 'Sí, eliminar';
                if (window.showToast) window.showToast(json.message || 'No se pudo eliminar', 'error');
            }
        } catch (err) {
            console.error(err);
            el.btnConfirmar.disabled = false;
            el.btnConfirmar.textContent = 'Sí, eliminar';
            if (window.showToast) window.showToast('Error de conexión', 'error');
        }
    };

    el.btnCancelar.onclick = cerrarModal;

    el.modal.addEventListener('click', (e) => {
        if (e.target === el.modal) cerrarModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && el.modal.classList.contains('open')) cerrarModal();
    });

    cargarUsuarios();

    // 🚀 Optimización de red: Detener peticiones si el usuario cambia de pestaña
    setInterval(() => {
        if (document.hidden) return; 

        if (paginaActual === 1 && !el.buscador.value.trim() && !el.modal.classList.contains('open')) {
            cargarUsuarios(1, '', true);
        }
    }, 4000);

});
</script>
@endsection