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
.user-row { transition: all 0.2s ease; }
.user-row:hover { background: var(--surface-3); transform: scale(1.001); }

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
    
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-1)] tracking-tight">Gestión de Usuarios</h1>
            <p class="text-sm text-[var(--text-3)] mt-1">Administra el personal, sus accesos y el estado de sus cuentas.</p>
        </div>
        <a href="{{ route('usuarios.crear') }}" id="btnNuevoUsuario" class="btn-primary flex items-center gap-2 px-5 py-2.5 text-sm flex-shrink-0 shadow-lg hover:shadow-neon-sm transition-all duration-300">
            <i class="fas fa-user-plus text-xs"></i> <span>Nuevo Usuario</span>
        </a>
    </div>

    {{-- STATS STRIP --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card px-5 py-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[rgba(96,165,250,0.1)] flex items-center justify-center border border-[rgba(96,165,250,0.2)] flex-shrink-0 shadow-sm group hover:scale-105 transition-transform">
                <i class="fas fa-users text-blue-400 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Total Registrados</p>
                <p id="statTotal" class="text-2xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-5 py-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[rgba(74,222,128,0.1)] flex items-center justify-center border border-[rgba(74,222,128,0.2)] flex-shrink-0 shadow-sm group hover:scale-105 transition-transform">
                <i class="fas fa-user-check text-green-400 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Activos</p>
                <p id="statActivos" class="text-xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
        <div class="card px-5 py-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[rgba(234,179,8,0.1)] flex items-center justify-center border border-[rgba(234,179,8,0.2)] flex-shrink-0 shadow-sm group hover:scale-105 transition-transform">
                <i class="fas fa-user-clock text-yellow-500 text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-0.5">Inactivos</p>
                <p id="statInactivos" class="text-xl font-bold text-[var(--text-1)]">—</p>
            </div>
        </div>
    </div>

    {{-- TABLA CARD --}}
    <div class="card shadow-sm border border-[var(--surface-4)] overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="px-6 py-4 border-b border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-wrap items-center justify-between gap-4">
            <div class="search-wrapper w-full sm:w-80">
                <i class="fas fa-search search-icon text-sm"></i>
                <input type="text" id="buscador" placeholder="Buscar por nombre o correo..." class="input-field w-full pl-10 pr-10 py-2.5 text-sm transition-shadow focus:shadow-md">
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
                        <th class="py-4 px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Usuario</th>
                        <th class="py-4 px-6 text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Contacto</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Perfil</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Seguridad</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-24">Estado</th>
                        <th class="py-4 px-6 text-right text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)] w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody" class="divide-y divide-[var(--surface-4)]">
                    {{-- Skeletons inyectados por JS --}}
                </tbody>
            </table>
        </div>

        {{-- Empty state --}}
        <div id="emptyState" class="hidden py-20 px-4 text-center bg-[var(--surface-1)]">
            <div class="w-16 h-16 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-users-slash text-2xl text-[var(--text-3)]"></i>
            </div>
            <p class="text-[var(--text-1)] font-semibold text-lg mb-1">Sin resultados</p>
            <p class="text-sm text-[var(--text-3)]">No encontramos ningún usuario con esos datos.</p>
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

{{-- MODAL ELIMINAR --}}
<div id="modalEliminar" class="confirm-overlay">
    <div id="eliminarContent" class="bg-[var(--surface-1)] border border-[var(--surface-4)] rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform transition-all scale-95 opacity-0">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-xmark text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-[var(--text-1)] mb-2">¿Eliminar usuario?</h3>
            <p class="text-sm text-[var(--text-3)] mb-6">¿Deseas eliminar a <strong id="nombreEliminar" class="text-[var(--text-1)]"></strong>?<br>Esta acción es permanente.</p>
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

    // ── RBAC (Permisos) ──
    const puedeCrear = window.tienePermiso('Usuarios', 'bitAgregar');
    const puedeEliminar = window.tienePermiso('Usuarios', 'bitEliminar');
    const puedeEditar = window.tienePermiso('Usuarios', 'bitEditar');

    const elements = {
        btnNuevo: document.getElementById('btnNuevoUsuario'),
        buscador: document.getElementById('buscador'),
        btnLimpiar: document.getElementById('btnLimpiarBusqueda'),
        tablaBody: document.getElementById('tablaBody'),
        emptyState: document.getElementById('emptyState'),
        pagWrapper: document.getElementById('paginacionWrapper'),
        pagBotones: document.getElementById('pagBotones'),
        statTotal: document.getElementById('statTotal'),
        statActivos: document.getElementById('statActivos'),
        statInactivos: document.getElementById('statInactivos'),
        modalEliminar: document.getElementById('modalEliminar'),
        eliminarContent: document.getElementById('eliminarContent'),
        tableCount: document.getElementById('tableCount'),
        infoRange: document.getElementById('infoRange'),
        infoTotal: document.getElementById('infoTotal')
    };

    if (elements.btnNuevo && !puedeCrear) elements.btnNuevo.style.display = 'none';

    let idEliminar = null;
    let timeoutBusqueda;
    let paginaActual = 1; // 👈 NUEVO: Rastrear en qué página estamos
    const localCache = new Map();

    const skeletonHTML = Array(5).fill().map(() => `
        <tr>
            <td class="py-4 px-6"><div class="skeleton h-4 w-6 rounded"></div></td>
            <td class="py-4 px-6"><div class="flex items-center gap-3"><div class="skeleton w-10 h-10 rounded-full"></div><div><div class="skeleton h-4 w-32 rounded mb-1"></div><div class="skeleton h-3 w-24 rounded"></div></div></div></td>
            <td class="py-4 px-6"><div class="skeleton h-4 w-28 rounded"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-6 w-20 rounded-full mx-auto"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-5 w-24 rounded mx-auto"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-6 w-16 rounded-full mx-auto"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-8 w-16 rounded-md ml-auto"></div></td>
        </tr>
    `).join('');

    // ── Función Principal de Carga ──
    const cargarUsuarios = async (pagina = 1, busqueda = elements.buscador.value.trim(), silencioso = false) => {
        paginaActual = pagina; // Actualizamos la página actual
        const cacheKey = `${pagina}-${busqueda}`;
        
        // Si no es una carga silenciosa (de fondo) y tenemos cache, lo usamos
        if (!silencioso && localCache.has(cacheKey)) {
            renderFull(localCache.get(cacheKey));
            return;
        }

        // Mostrar skeleton solo si no es una actualización silenciosa de fondo
        if (!silencioso) {
            elements.tablaBody.innerHTML = skeletonHTML;
            elements.pagWrapper.classList.add('hidden');
        }

        try {
            const res = await fetch(`/api/usuarios?page=${pagina}&buscar=${encodeURIComponent(busqueda)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            
            // 👈 MAGIA: Comparamos si los datos nuevos son diferentes a los que ya teníamos
            const cacheActual = localCache.get(cacheKey);
            const datosCambiaron = JSON.stringify(cacheActual?.data) !== JSON.stringify(data.data);

            // Si cambiaron, o si es la primera vez que cargamos, renderizamos
            if (datosCambiaron || !silencioso) {
                localCache.set(cacheKey, data);
                renderFull(data);
            }
        } catch (err) { 
            console.error(err); 
        }
    };

    const renderFull = (data) => {
        renderTabla(data.data, data.from);
        renderPaginacion(data);
        
        elements.statTotal.textContent = data.total || 0;
        elements.tableCount.textContent = `${data.total || 0} REGISTROS`;
        
        const activos = data.data.filter(u => u.idEstadoUsuario == 1).length;
        const inactivos = data.data.filter(u => u.idEstadoUsuario == 0).length;
        elements.statActivos.textContent = activos;
        elements.statInactivos.textContent = inactivos;

        elements.btnLimpiar.style.display = elements.buscador.value.length > 0 ? 'block' : 'none';
    };

    const renderTabla = (usuarios, fromIndex) => {
        if (!usuarios || usuarios.length === 0) {
            elements.tablaBody.innerHTML = '';
            elements.emptyState.classList.remove('hidden');
            elements.pagWrapper.classList.add('hidden');
            return;
        }
        elements.emptyState.classList.add('hidden');
        elements.pagWrapper.classList.remove('hidden');

        let html = '';
        for (let i = 0; i < usuarios.length; i++) {
            const u = usuarios[i];
            const numFila = (fromIndex || 1) + i;
            const esAdminMaestro = u.id === 1;
            
            const avatar = u.strImagen 
                ? `<img src="${u.strImagen}-/scale_crop/100x100/center/" class="w-10 h-10 rounded-full object-cover border border-[var(--surface-4)]" loading="lazy">`
                : `<div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--neon)] to-[var(--neon-dark)] flex items-center justify-center text-xs font-bold text-white shadow-sm">${u.strNombreUsuario.substring(0,2).toUpperCase()}</div>`;

            const btnEditar = (puedeEditar && !esAdminMaestro) 
                ? `<a href="/usuarios/${u.id}/editar" class="action-btn edit tooltip hover:bg-yellow-500/10 hover:text-yellow-500" data-tip="Editar"><i class="fas fa-user-pen"></i></a>` 
                : `<div class="action-btn opacity-20 cursor-not-allowed tooltip" data-tip="Protegido"><i class="fas fa-user-shield"></i></div>`;

            const btnEliminar = (puedeEliminar && !esAdminMaestro) 
                ? `<button data-action="delete" data-id="${u.id}" data-name="${u.strNombreUsuario}" class="action-btn danger tooltip hover:bg-red-500/10 hover:text-red-500" data-tip="Eliminar"><i class="fas fa-trash-can"></i></button>` 
                : '';

            html += `
            <tr class="user-row">
                <td class="py-4 px-6 text-xs text-[var(--text-3)] font-mono">${numFila}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        ${avatar}
                        <div>
                            <p class="font-bold text-[var(--text-1)] text-sm">${u.strNombreUsuario}</p>
                            <span class="text-[10px] ${u.correo_verificado_at ? 'text-green-500' : 'text-[var(--neon)]'}">${u.correo_verificado_at ? 'Verificado' : 'Pendiente'}</span>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <p class="text-sm text-[var(--text-2)]">${u.strCorreo}</p>
                    <p class="text-[10px] text-[var(--text-3)]">${u.strNumeroCelular || '—'}</p>
                </td>
                <td class="py-4 px-6 text-center">
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-[var(--surface-3)] text-[var(--text-2)] border border-[var(--surface-4)]">${u.perfil ? u.perfil.strNombrePerfil : 'Sin perfil'}</span>
                </td>
                <td class="py-4 px-6 text-center">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] ${u.correo_verificado_at ? 'text-green-500' : 'text-[var(--text-3)]'}"><i class="fas fa-envelope mr-1"></i> Email</span>
                        <span class="text-[10px] ${u.celular_verificado_at ? 'text-blue-400' : 'text-[var(--text-3)]'}"><i class="fas fa-shield mr-1"></i> MFA</span>
                    </div>
                </td>
                <td class="py-4 px-6 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold ${u.idEstadoUsuario == 1 ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20'}">
                        ${u.idEstadoUsuario == 1 ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="/usuarios/${u.id}/detalle" class="action-btn view tooltip hover:bg-blue-500/10 hover:text-blue-400" data-tip="Inspeccionar"><i class="fas fa-eye"></i></a>
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
            if (!disabled) btn.onclick = () => cargarUsuarios(page);
            return btn;
        };

        elements.pagBotones.appendChild(createBtn('fas fa-angles-left', 1, current === 1));
        elements.pagBotones.appendChild(createBtn('fas fa-angle-left', current - 1, current === 1));
        elements.pagBotones.appendChild(createBtn('fas fa-angle-right', current + 1, current === last));
        elements.pagBotones.appendChild(createBtn('fas fa-angles-right', last, current === last));
    };

    elements.buscador.oninput = (e) => {
        clearTimeout(timeoutBusqueda);
        elements.btnLimpiar.style.display = e.target.value.length > 0 ? 'block' : 'none';
        timeoutBusqueda = setTimeout(() => cargarUsuarios(1), 350);
    };

    elements.btnLimpiar.onclick = () => {
        elements.buscador.value = '';
        elements.buscador.focus();
        elements.btnLimpiar.style.display = 'none';
        cargarUsuarios(1);
    };

    elements.tablaBody.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action="delete"]');
        if (!btn) return;
        const { id, name } = btn.dataset;
        idEliminar = id;
        document.getElementById('nombreEliminar').textContent = name;
        elements.modalEliminar.classList.add('open');
        setTimeout(() => elements.eliminarContent.classList.remove('scale-95', 'opacity-0'), 10);
    });

    document.getElementById('btnConfirmarEliminar').onclick = async () => {
        try {
            const res = await fetch(`/api/usuarios/${idEliminar}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if ((await res.json()).success) {
                elements.modalEliminar.classList.remove('open');
                localCache.clear();
                cargarUsuarios();
                if(window.showToast) window.showToast('Usuario eliminado', 'success');
            }
        } catch (err) { console.error(err); }
    };

    document.getElementById('btnCancelarEliminar').onclick = () => {
        elements.eliminarContent.classList.remove('scale-100', 'opacity-100');
        elements.eliminarContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => elements.modalEliminar.classList.remove('open'), 200);
    };
    
    // 1. Carga inicial normal
    cargarUsuarios();

    // 2. 🚀 MOTOR DE "TIEMPO REAL" (Silent Polling) 🚀
    // Se ejecuta cada 4 segundos
    setInterval(() => {
        const busquedaActiva = elements.buscador.value.trim() !== '';
        
        // Solo verificamos si estamos en la página 1 y NO hay búsqueda activa
        if (paginaActual === 1 && !busquedaActiva) {
            cargarUsuarios(1, '', true); // true = modo silencioso (sin skeleton)
        }
    }, 4000);

});
</script>
@endsection