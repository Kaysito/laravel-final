@extends('layouts.app')

@section('title', 'Gestión de Perfiles')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Perfiles</span>
@endsection

@section('styles')
<style>
/* ── Profile card row ── */
.profile-row { transition: background-color 0.2s ease; }
.profile-row:hover { background-color: var(--surface-3); }

/* ── Role badge ── */
.role-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 500; letter-spacing: 0.02em; }

/* ── Skeleton ── */
.skeleton { background: linear-gradient(90deg, var(--surface-3) 25%, var(--surface-4) 50%, var(--surface-3) 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 4px; }
@keyframes shimmer { 0%{background-position:200% 0}100%{background-position:-200% 0} }

/* ── Search input ── */
.search-wrap { position: relative; display: flex; align-items: center; }
.search-wrap .si { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-3); font-size:13px; pointer-events:none; }
.search-wrap input { padding-left: 38px; padding-right: 38px; }
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
            <h1 class="text-2xl font-bold text-[var(--text-1)] tracking-tight">Perfiles de Acceso</h1>
            <p class="text-sm text-[var(--text-3)] mt-1">Administra los roles y permisos globales del sistema.</p>
        </div>
        <a href="{{ route('perfil.crear') }}" id="btnNuevoPerfil" class="btn-primary flex items-center gap-2 px-5 py-2.5 text-sm flex-shrink-0 shadow-lg hover:shadow-neon-sm transition-all duration-300">
            <i class="fas fa-plus text-xs pointer-events-none"></i> Nuevo perfil
        </a>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" id="statsRow">
        <div class="card px-5 py-4 flex flex-col justify-center group hover:scale-105 transition-transform">
            <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-1.5">Total perfiles</p>
            <p class="text-3xl font-bold text-[var(--text-1)]" id="statTotal">—</p>
        </div>
        <div class="card px-5 py-4 flex flex-col justify-center relative overflow-hidden group hover:scale-105 transition-transform">
            <div class="absolute -right-4 -top-4 text-[var(--neon-muted)] text-6xl opacity-20"><i class="fas fa-shield-halved"></i></div>
            <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-1.5 relative z-10">Administradores</p>
            <p class="text-3xl font-bold text-[var(--neon)]" id="statSuper">—</p>
        </div>
        <div class="card px-5 py-4 flex flex-col justify-center group hover:scale-105 transition-transform">
            <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-1.5">Estándar</p>
            <p class="text-3xl font-bold text-[#60a5fa]" id="statStd">—</p>
        </div>
        <div class="card px-5 py-4 flex flex-col justify-center group hover:scale-105 transition-transform">
            <p class="text-[10px] font-mono text-[var(--text-3)] uppercase tracking-widest mb-1.5">Seguridad</p>
            <div class="flex items-center gap-2">
                <p class="text-xl font-bold text-[var(--text-1)]">JWT</p>
                <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)] animate-pulse"></span>
            </div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="card shadow-sm border border-[var(--surface-4)] overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="px-6 py-4 border-b border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-wrap items-center justify-between gap-4">
            <div class="search-wrap flex-1 min-w-[200px] max-w-sm">
                <i class="fas fa-search si"></i>
                <input type="text" id="buscador" class="input-field w-full py-2.5 text-sm transition-shadow focus:shadow-md" placeholder="Buscar perfil por nombre...">
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
                <thead class="bg-[var(--surface-2)]">
                    <tr>
                        <th class="py-4 px-6 text-left text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Perfil</th>
                        <th class="py-4 px-6 text-left text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border(--surface-4)">Tipo de Cuenta</th>
                        <th class="py-4 px-6 text-right text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase border-b border-[var(--surface-4)]">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-[var(--surface-4)]">
                </tbody>
            </table>
        </div>

        <div id="emptyState" class="hidden py-20 px-4 text-center bg-[var(--surface-1)]">
            <div class="w-16 h-16 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-search text-2xl text-[var(--text-3)]"></i>
            </div>
            <p class="text-[var(--text-1)] font-semibold text-lg mb-1">Sin resultados</p>
            <p class="text-sm text-[var(--text-3)]">No encontramos ningún perfil con ese nombre.</p>
        </div>

        {{-- Paginación --}}
        <div id="paginacionWrapper" class="hidden px-6 py-4 border-t border-[var(--surface-4)] bg-[var(--surface-2)] flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[10px] font-mono uppercase text-[var(--text-3)] tracking-widest">
                Mostrando <span id="infoRange" class="text-[var(--text-1)] font-bold">—</span> de <span id="infoTotal" class="text-[var(--text-1)] font-bold">—</span> perfiles
            </p>
            <div id="pagBotones" class="flex items-center gap-1.5"></div>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div class="confirm-overlay" id="confirmModal">
    <div class="bg-[var(--surface-1)] border border-[var(--surface-4)] rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform transition-all scale-95 opacity-0" id="confirmBox">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-[var(--text-1)] mb-2">¿Eliminar perfil?</h3>
            <p class="text-sm text-[var(--text-3)] mb-6" id="confirmMsg">Esta acción no se puede deshacer.</p>
            <div class="flex gap-3 justify-center">
                <button id="btnCancelarConfirm" class="btn-ghost flex-1 py-2.5 text-sm font-medium border border-[var(--surface-4)]">Cancelar</button>
                <button id="btnConfirmDelete" class="flex-1 py-2.5 text-sm font-bold rounded-lg bg-red-500 hover:bg-red-600 text-white shadow-lg transition-colors">Sí, eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // 🚀 CORRECCIÓN: El nombre debe coincidir EXACTAMENTE con el de la BD y la imagen
    const nombreEnBD = 'Perfiles'; 

    const puedeVerDetalle = window.tienePermiso(nombreEnBD, 'bitDetalle');
    const puedeCrear = window.tienePermiso(nombreEnBD, 'bitAgregar');
    const puedeEditar = window.tienePermiso(nombreEnBD, 'bitEditar');
    const puedeEliminar = window.tienePermiso(nombreEnBD, 'bitEliminar');

    const elements = {
        btnNuevo: document.getElementById('btnNuevoPerfil'),
        buscador: document.getElementById('buscador'),
        btnLimpiar: document.getElementById('btnLimpiarBusqueda'),
        tableBody: document.getElementById('tableBody'),
        emptyState: document.getElementById('emptyState'),
        pagWrapper: document.getElementById('paginacionWrapper'),
        pagBotones: document.getElementById('pagBotones'),
        confirmModal: document.getElementById('confirmModal'),
        confirmBox: document.getElementById('confirmBox'),
        statTotal: document.getElementById('statTotal'),
        statSuper: document.getElementById('statSuper'),
        statStd: document.getElementById('statStd'),
        tableCount: document.getElementById('tableCount'),
        infoRange: document.getElementById('infoRange'),
        infoTotal: document.getElementById('infoTotal')
    };

    if (elements.btnNuevo && !puedeCrear) elements.btnNuevo.style.display = 'none';

    let timeoutBusqueda;
    let deleteId = null;
    let paginaActual = 1;
    const localCache = new Map(); // Optimización de memoria

    const skeletonHTML = Array(4).fill(`
        <tr>
            <td class="py-4 px-6"><div class="flex items-center gap-3"><div class="skeleton w-10 h-10 rounded-lg"></div><div class="skeleton h-4 w-32 rounded"></div></div></td>
            <td class="py-4 px-6"><div class="skeleton h-6 w-24 rounded-full"></div></td>
            <td class="py-4 px-6"><div class="skeleton h-8 w-24 rounded-md ml-auto"></div></td>
        </tr>
    `).join('');

    const cargarPerfiles = async (pagina = 1, busqueda = elements.buscador.value.trim(), silencioso = false) => {
        paginaActual = pagina;
        const cacheKey = `${pagina}-${busqueda}`;
        
        if (!silencioso && localCache.has(cacheKey)) {
            renderFull(localCache.get(cacheKey));
            return;
        }

        if (!silencioso) {
            elements.tableBody.innerHTML = skeletonHTML;
            elements.pagWrapper.classList.add('hidden');
        }

        try {
            const response = await fetch(`/api/perfiles?page=${pagina}&buscar=${encodeURIComponent(busqueda)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            const cacheActual = localCache.get(cacheKey);
            const datosCambiaron = JSON.stringify(cacheActual?.data) !== JSON.stringify(data.data);

            if (datosCambiaron || !silencioso) {
                if (localCache.size > 20) {
                    const firstKey = localCache.keys().next().value;
                    localCache.delete(firstKey);
                }
                localCache.set(cacheKey, data);
                
                // Pintado fluido del DOM
                requestAnimationFrame(() => renderFull(data));
            }
        } catch (err) { 
            console.error('Error cargando perfiles:', err); 
        }
    };

    const renderFull = (data) => {
        renderTabla(data.data || []);
        renderPaginacion(data);

        const total = data.total || 0;
        elements.statTotal.textContent = total;
        elements.tableCount.textContent = `${total} PERFILES`;
        
        let superCount = 0;
        const perfilesArray = data.data || [];
        for(let i=0; i < perfilesArray.length; i++) {
            if(perfilesArray[i].bitAdministrador) superCount++;
        }

        elements.statSuper.textContent = superCount;
        elements.statStd.textContent = total - superCount;
        elements.btnLimpiar.style.display = elements.buscador.value.length > 0 ? 'block' : 'none';
    };

    const renderTabla = (perfiles) => {
        if (!perfiles.length) {
            elements.tableBody.innerHTML = '';
            elements.emptyState.classList.remove('hidden');
            elements.pagWrapper.classList.add('hidden');
            return;
        }
        elements.emptyState.classList.add('hidden');
        elements.pagWrapper.classList.remove('hidden');

        // Optimización: Construcción de DOM rápida con Array
        const htmlRows = [];
        
        for (let i = 0; i < perfiles.length; i++) {
            const p = perfiles[i];
            const nombre = p.strNombrePerfil || 'Sin Nombre';
            const initials = nombre.substring(0, 2).toUpperCase();
            const isSuper = !!p.bitAdministrador;
            const isMaster = p.id === 1;
            const attrName = nombre.replace(/"/g, '&quot;');

            let btnVer = '', btnEditar = '', btnEliminar = '';

            if (puedeVerDetalle) {
                btnVer = `<a href="/perfiles/${p.id}/detalle" class="action-btn tooltip hover:text-blue-500" data-tip="Ver Detalle"><i class="fas fa-eye text-xs"></i></a>`;
            }

            if (isMaster) {
                btnEditar = `<div class="action-btn opacity-20 cursor-not-allowed tooltip" data-tip="Protegido"><i class="fas fa-shield-halved text-xs"></i></div>`;
            } else {
                if (puedeEditar) btnEditar = `<a href="/perfiles/${p.id}/editar" class="action-btn edit tooltip hover:text-yellow-500" data-tip="Editar"><i class="fas fa-pen text-xs"></i></a>`;
                if (puedeEliminar) btnEliminar = `<button data-action="delete" data-id="${p.id}" data-name="${attrName}" class="action-btn danger tooltip hover:text-red-500" data-tip="Eliminar"><i class="fas fa-trash-can text-xs"></i></button>`;
            }

            htmlRows.push(`
            <tr class="profile-row">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold" style="${isSuper ? 'background:rgba(230,55,87,0.12);color:var(--neon);' : 'background:var(--surface-3);color:var(--text-2);'}">
                            ${initials}
                        </div>
                        <p class="font-semibold text-[var(--text-1)] text-sm">${nombre}</p>
                    </div>
                </td>
                <td class="py-4 px-6">
                    ${isSuper ? '<span class="role-badge" style="background:rgba(230,55,87,0.1);color:var(--neon);">Administrador</span>' : '<span class="role-badge" style="background:rgba(96,165,250,0.08);color:#60a5fa;">Estándar</span>'}
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-2">${btnVer} ${btnEditar} ${btnEliminar}</div>
                </td>
            </tr>`);
        }
        
        elements.tableBody.innerHTML = htmlRows.join('');
    };

    const renderPaginacion = (data) => {
        elements.pagBotones.innerHTML = '';
        if (!data.last_page || data.last_page <= 1) return;

        elements.infoRange.textContent = `${data.from}-${data.to}`;
        elements.infoTotal.textContent = data.total;

        const current = data.current_page;
        const last = data.last_page;
        const frag = document.createDocumentFragment();

        const createBtn = (icon, page, disabled) => {
            const btn = document.createElement('button');
            btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] transition-colors ${disabled ? 'text-[var(--text-4)] bg-[var(--surface-3)] cursor-not-allowed opacity-50' : 'text-[var(--text-2)] hover:bg-[var(--surface-3)] border border-[var(--surface-4)]'}`;
            btn.innerHTML = `<i class="${icon}"></i>`;
            btn.disabled = disabled;
            if (!disabled) btn.onclick = () => cargarPerfiles(page);
            return btn;
        };

        frag.appendChild(createBtn('fas fa-angles-left', 1, current === 1));
        frag.appendChild(createBtn('fas fa-angle-left', current - 1, current === 1));
        frag.appendChild(createBtn('fas fa-angle-right', current + 1, current === last));
        frag.appendChild(createBtn('fas fa-angles-right', last, current === last));
        
        elements.pagBotones.appendChild(frag);
    };

    elements.buscador.oninput = (e) => {
        clearTimeout(timeoutBusqueda);
        elements.btnLimpiar.style.display = e.target.value.length > 0 ? 'block' : 'none';
        timeoutBusqueda = setTimeout(() => cargarPerfiles(1), 300);
    };

    elements.btnLimpiar.onclick = () => {
        elements.buscador.value = '';
        elements.buscador.focus();
        elements.btnLimpiar.style.display = 'none';
        cargarPerfiles(1);
    };

    // Delegación de eventos optimizada
    elements.tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action="delete"]');
        if (!btn) return;
        
        deleteId = btn.dataset.id;
        document.getElementById('confirmMsg').innerHTML = `¿Deseas eliminar el perfil <strong>${btn.dataset.name}</strong>?`;
        elements.confirmModal.classList.add('open');
        setTimeout(() => elements.confirmBox.classList.remove('scale-95', 'opacity-0'), 10);
    });

    document.getElementById('btnConfirmDelete').addEventListener('click', async () => {
        try {
            const res = await fetch(`/api/perfiles/${deleteId}`, { 
                method: 'DELETE', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
            });
            const data = await res.json();
            if (data.success) {
                elements.confirmModal.classList.remove('open');
                localCache.clear();
                cargarPerfiles(paginaActual);
                if(window.showToast) window.showToast('Perfil eliminado', 'success');
            }
        } catch (err) { console.error('Error al eliminar:', err); }
    });

    // Corrección visual del cierre del modal
    document.getElementById('btnCancelarConfirm').onclick = () => {
        elements.confirmBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => elements.confirmModal.classList.remove('open'), 200);
    };
    
    cargarPerfiles();
});
</script>
@endsection