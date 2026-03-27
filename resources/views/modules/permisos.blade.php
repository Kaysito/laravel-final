@extends('layouts.app')

@section('title', 'Matriz de Permisos')

@section('breadcrumb')
    {{-- Breadcrumb Dinámico --}}
    <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors tooltip" data-tip="Ir al Dashboard">
        <i class="fas fa-home text-xs"></i>
    </a>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-3)]">Seguridad</span>
    <i class="fas fa-chevron-right text-[var(--surface-4)] text-[10px] mx-2"></i>
    <span class="text-[var(--text-1)] font-medium">Permisos-Perfil</span>
@endsection

@section('styles')
<style>
/* ══════════════════════════════════════════════════════════
   PERMISSION MATRIX (Optimizado)
══════════════════════════════════════════════════════════ */

/* Anchos de columna — fuente única de verdad */
:root {
    --w-mod:  260px;   /* Módulo */
    --w-perm:  88px;   /* Cada permiso × 5 */
    --w-act:  108px;   /* Acción */
}

/* Wrapper con scroll — el thead sticky vive dentro */
.matrix-wrap {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 310px);
    flex: 1;
}

/* Una sola tabla */
.mx {
    width: 100%;
    min-width: calc(var(--w-mod) + var(--w-perm)*5 + var(--w-act));
    border-collapse: collapse;
    table-layout: fixed;
}

/* Anchos explícitos — la clave de la alineación */
.mx .c-mod  { width: var(--w-mod);  }
.mx .c-perm { width: var(--w-perm); }
.mx .c-act  { width: var(--w-act);  }

/* ── Thead sticky ── */
.mx thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: var(--surface-2);
    border-bottom: 1px solid var(--surface-4);
    padding: 0;
    text-align: center;
    white-space: nowrap;
    box-shadow: 0 4px 6px -4px rgba(0,0,0,0.05);
}
.mx thead th.th-mod {
    text-align: left;
    padding: 0 16px 0 24px;
}
.mx thead th.th-act {
    text-align: right;
    padding: 0 20px;
}

/* ── Botón de cabecera de columna ── */
.col-header-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    cursor: pointer;
    width: 100%;
    height: 54px;
    padding: 0;
    border: none;
    background: transparent;
    outline: none;
    transition: background 0.15s ease;
}
.col-header-btn:hover  { background: var(--surface-3); }
.col-header-btn:focus-visible { outline: 2px solid var(--neon); outline-offset: -2px; }

.col-check-mini {
    width: 14px; height: 14px;
    border-radius: 4px;
    border: 1px solid var(--surface-5);
    background: var(--surface-1);
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.col-check-mini.all-on { background: var(--surface-3); border-color: var(--text-3); }
.col-check-mini.all-on::after {
    content: '';
    width: 6px; height: 6px;
    border-radius: 2px;
    background: var(--text-2);
}
.col-label {
    font-size: 10px;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

/* ── Filas ── */
.mx tbody tr {
    border-bottom: 1px solid var(--surface-4);
    transition: background 0.15s ease;
}
.mx tbody tr:last-child { border-bottom: none; }
.mx tbody tr:hover { background: var(--surface-3); }
.mx tbody tr.has-changes { background: rgba(230,55,87,0.03); }
.mx tbody tr.has-changes td:first-child { box-shadow: inset 3px 0 0 var(--neon); }
.mx tbody tr:nth-child(even) { background: rgba(0,0,0,0.01); }
.mx tbody tr:nth-child(even):hover { background: var(--surface-3); }

/* ── Celdas ── */
.mx tbody td {
    padding: 0;
    text-align: center;
    vertical-align: middle;
    height: 56px;
}
.mx tbody td:first-child { text-align: left; }
.mx tbody td:last-child  { text-align: right; padding-right: 20px; }

/* Módulo cell */
.module-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 16px 0 24px;
    height: 56px;
}
.module-icon-sm {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: var(--surface-3);
    border: 1px solid var(--surface-4);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: var(--text-3); flex-shrink: 0;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}
.module-name {
    font-size: 13px; font-weight: 600; color: var(--text-1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.module-desc {
    font-size: 11px; color: var(--text-3); margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ── Checkbox Principal ── */
.perm-check {
    width: 24px; height: 24px;
    border-radius: 6px;
    border: 1.5px solid var(--surface-5);
    background: var(--surface-1);
    cursor: pointer; transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: inline-flex; align-items: center; justify-content: center;
    outline: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.perm-check:hover { border-color: var(--text-3); background: var(--surface-2); transform: translateY(-1px); }
.perm-check:focus-visible { outline: 2px solid var(--neon); outline-offset: 2px; }
.perm-check .ck {
    opacity: 0; transform: scale(0.2);
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); font-size: 11px; font-weight: 900;
}
.perm-check.on-ver  { background: rgba(52,211,153,.15);  border-color: #34d399; }
.perm-check.on-agr  { background: rgba(96,165,250,.15);  border-color: #60a5fa; }
.perm-check.on-edi  { background: rgba(251,191,36,.15);  border-color: #fbbf24; }
.perm-check.on-eli  { background: rgba(248,113,113,.15); border-color: #f87171; }
.perm-check.on-det  { background: rgba(167,139,250,.15); border-color: #a78bfa; }

.perm-check.on-ver .ck { opacity:1; transform:scale(1); color:#10b981; }
.perm-check.on-agr .ck { opacity:1; transform:scale(1); color:#3b82f6; }
.perm-check.on-edi .ck { opacity:1; transform:scale(1); color:#f59e0b; }
.perm-check.on-eli .ck { opacity:1; transform:scale(1); color:#ef4444; }
.perm-check.on-det .ck { opacity:1; transform:scale(1); color:#8b5cf6; }

/* ── Botón guardar fila ── */
.btn-row-save {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 6px;
    font-size: 11px; font-weight: 700;
    background: rgba(230,55,87,.10); border: 1px solid rgba(230,55,87,.30);
    color: var(--neon); cursor: pointer; transition: all .2s;
    white-space: nowrap; opacity: 0; pointer-events: none;
    transform: translateX(10px);
}
.btn-row-save.dirty { opacity: 1; pointer-events: auto; transform: translateX(0); }
.btn-row-save:hover { background: var(--neon); color: white; border-color: var(--neon); box-shadow: 0 4px 12px rgba(230,55,87,0.2); }
.btn-row-save.busy  { opacity: .5; pointer-events: none; }

/* ── Etiqueta de perfil protegido ── */
.protected-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 6px;
    font-size: 10px; font-weight: 800;
    background: rgba(230,55,87,.15); border: 1px solid rgba(230,55,87,.40);
    color: var(--neon); letter-spacing: 0.5px;
    white-space: nowrap;
}
.protected-badge i { font-size: 10px; }

/* ── Skeleton ── */
.skeleton {
    background: linear-gradient(90deg, var(--surface-3) 25%, var(--surface-4) 50%, var(--surface-3) 75%);
    background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 4px;
}
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

/* ── Profile selector ── */
.profile-selector-wrap { position: relative; }
.profile-btn {
    display: flex; align-items: center; gap: 10px; padding: 8px 14px;
    background: var(--surface-1); border: 1px solid var(--surface-4); border-radius: 10px;
    cursor: pointer; color: var(--text-2); font-size: 13px; font-weight: 600;
    transition: all .2s ease; min-width: 220px; outline: none; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.profile-btn:hover { border-color: var(--neon-border); color: var(--text-1); }
.profile-btn.selected { border-color: var(--neon); color: var(--text-1); background: var(--neon-muted); }
.profile-avatar {
    width: 24px; height: 24px; border-radius: 6px; background: var(--surface-4);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 800; color: var(--text-2);
    flex-shrink: 0; font-family: 'JetBrains Mono', monospace;
}
.profile-avatar.active { background: var(--neon-dark); color: white; }

.profile-dropdown {
    position: absolute; top: calc(100% + 8px); left: 0; min-width: 280px;
    background: var(--surface-1); border: 1px solid var(--surface-4); border-radius: 12px;
    box-shadow: 0 20px 40px -10px rgba(0,0,0,.15); z-index: 200; overflow: hidden; 
    opacity: 0; transform: translateY(-10px); pointer-events: none; transition: all 0.2s ease;
}
.profile-dropdown.open { opacity: 1; transform: translateY(0); pointer-events: auto; }

.pd-search {
    display: flex; align-items: center; gap: 10px; padding: 12px 16px;
    border-bottom: 1px solid var(--surface-4); background: var(--surface-2);
}
.pd-search input { flex:1; background:transparent; border:none; outline:none; color:var(--text-1); font-size:13px; font-weight: 500;}
.pd-search input::placeholder { color: var(--text-3); font-weight: 400;}
.pd-list { max-height: 260px; overflow-y: auto; padding: 6px; }
.pd-item {
    display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px;
    cursor: pointer; color: var(--text-2); font-size: 13px; font-weight: 500; transition: all .15s;
}
.pd-item:hover, .pd-item:focus { background: var(--surface-3); color: var(--text-1); outline: none; }
.pd-item.active { color: var(--neon); background: var(--neon-muted); }
.pd-item .pd-check { margin-left: auto; color: var(--neon); font-size: 12px; }

/* ── Toolbar pills ── */
.toolbar-pill {
    display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px;
    border-radius: 8px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--surface-4); background: var(--surface-1);
    color: var(--text-2); cursor: pointer; transition: all .2s; white-space: nowrap; outline: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.toolbar-pill:hover { border-color: var(--text-3); color: var(--text-1); background: var(--surface-2); transform: translateY(-1px); }
.toolbar-pill.danger:hover { border-color: #f87171; color: white; background: #ef4444; box-shadow: 0 4px 12px rgba(239,68,68,0.2); }

/* ── Unsaved dot ── */
.unsaved-dot { width:8px; height:8px; border-radius:50%; background:#f59e0b; display:inline-block; animation:pdot 2s ease infinite; box-shadow: 0 0 8px rgba(245,158,11,0.6); }
@keyframes pdot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.8)} }

/* ── Copy modal ── */
.copy-modal-overlay {
    position: fixed; inset: 0; z-index: 300;
    background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.2s ease;
}
.copy-modal-overlay.open { display: flex; opacity: 1; }

/* ── Estado bloqueado para Admin ── */
.matrix-wrap.admin-blocked {
    pointer-events: none;
    opacity: 0.85;
}
.matrix-wrap.admin-blocked .mx tbody tr:hover { background: transparent; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-hidden flex flex-col p-4 sm:p-6 lg:p-8">

    {{-- PAGE HEADER --}}
    <div class="flex-shrink-0 mb-6">
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[var(--text-1)] tracking-tight">Matriz de Permisos (RBAC)</h1>
                <p class="text-sm text-[var(--text-3)] mt-1">Selecciona un perfil y configura sus privilegios de acceso a cada módulo del sistema.</p>
            </div>
            
            {{-- Barra de "Cambios sin guardar" --}}
            <div id="unsavedBar" class="hidden items-center gap-4 px-5 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 shadow-sm transition-all">
                <span class="unsaved-dot"></span>
                <span class="text-sm text-amber-500 font-bold tracking-wide" id="unsavedLabel">Cambios sin guardar</span>
                <div class="w-px h-5 bg-amber-500/20 mx-1"></div>
                <button onclick="discardAll()" class="text-xs font-bold text-[var(--text-3)] hover:text-amber-600 transition-colors">
                    Descartar
                </button>
                <button onclick="saveAllPending()" id="btnSaveAll" class="text-xs font-bold text-white bg-amber-500 border border-amber-600 px-4 py-1.5 rounded-lg hover:bg-amber-600 shadow-md transition-all">
                    Guardar Todo
                </button>
            </div>
        </div>
    </div>

    {{-- CONTROL BAR --}}
    <div class="flex-shrink-0 mb-6">
        <div class="card px-5 py-4 flex flex-col lg:flex-row lg:items-center gap-4 shadow-sm">

            <div class="profile-selector-wrap z-30" id="profileSelectorWrap">
                <button class="profile-btn" id="profileBtn" aria-haspopup="listbox" aria-expanded="false" onclick="toggleProfileDropdown()" onkeydown="handleProfileBtnKey(event)">
                    <div class="profile-avatar" id="profileAvatarBtn"><i class="fas fa-search"></i></div>
                    <span id="profileBtnLabel" class="flex-1 text-left">Selecciona un perfil...</span>
                    <i class="fas fa-chevron-down text-[10px] text-[var(--text-3)]"></i>
                </button>
                
                <div class="profile-dropdown" id="profileDropdown" role="listbox">
                    <div class="pd-search">
                        <i class="fas fa-magnifying-glass text-[var(--text-3)] text-sm"></i>
                        <input type="text" id="pdSearch" placeholder="Buscar perfil por nombre..." autocomplete="off" oninput="filterProfiles(this.value)" onkeydown="handlePdSearchKey(event)">
                    </div>
                    <div class="pd-list" id="pdList">
                        <div class="pd-item" style="color:var(--text-3);pointer-events:none;font-size:12px;">
                            <i class="fas fa-spinner fa-spin text-xs mr-2"></i> Cargando catálogos...
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block w-px h-8 bg-[var(--surface-4)] mx-2"></div>

            <div class="flex items-center gap-3 flex-wrap" id="toolbarActions">
                <button onclick="grantAll()"  class="toolbar-pill" data-tip="Activar todos los permisos">
                    <i class="fas fa-check-double text-[var(--neon)]"></i> Todo acceso
                </button>
                <button onclick="revokeAll()" class="toolbar-pill danger" data-tip="Revocar todos los permisos">
                    <i class="fas fa-ban"></i> Sin acceso
                </button>
                <button onclick="openCopyModal()" class="toolbar-pill" data-tip="Copiar permisos desde otro perfil">
                    <i class="fas fa-copy text-blue-500"></i> Clonar de...
                </button>
            </div>

            <div id="activeProfileTag" class="hidden lg:flex ml-auto items-center gap-2 px-4 py-2 rounded-lg bg-[var(--surface-1)] border border-[var(--surface-4)] shadow-sm">
                <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></div>
                <span class="text-xs font-bold text-[var(--text-1)] uppercase tracking-wider" id="activeProfileName"></span>
            </div>
        </div>
    </div>

    {{-- MATRIX --}}
    <div class="flex-1 overflow-hidden">
        <div class="card h-full flex flex-col overflow-hidden shadow-sm border border-[var(--surface-4)]">

            {{-- Empty state Inicial --}}
            <div id="emptyState" class="flex flex-col items-center justify-center py-20 text-center flex-1 bg-[var(--surface-1)]">
                <div class="w-20 h-20 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mb-6 shadow-inner">
                    <i class="fas fa-fingerprint text-3xl text-[var(--text-3)]"></i>
                </div>
                <p class="text-lg font-bold text-[var(--text-1)] mb-2">Selecciona un Perfil</p>
                <p class="text-sm text-[var(--text-3)] max-w-sm leading-relaxed">Elige un perfil en el menú desplegable superior para visualizar y modificar sus privilegios criptográficos en la matriz.</p>
            </div>

            {{-- Tabla de Permisos --}}
            <div class="matrix-wrap hidden bg-[var(--surface-1)]" id="matrixWrap">
                <table class="mx" id="matrixTable">
                    <colgroup>
                        <col class="c-mod">
                        <col class="c-perm">{{-- Ver --}}
                        <col class="c-perm">{{-- Crear --}}
                        <col class="c-perm">{{-- Editar --}}
                        <col class="c-perm">{{-- Eliminar --}}
                        <col class="c-perm">{{-- Detalle --}}
                        <col class="c-act">
                    </colgroup>

                    <thead>
                         <tr>
                            <th class="th-mod">
                                <span class="text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase">Módulo del Sistema</span>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('ver')" data-tip="Seleccionar/Deseleccionar Ver">
                                    <div class="col-check-mini" id="colMiniVer"></div>
                                    <span class="col-label" style="color:#10b981">Ver</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('agr')" data-tip="Seleccionar/Deseleccionar Crear">
                                    <div class="col-check-mini" id="colMiniAgr"></div>
                                    <span class="col-label" style="color:#3b82f6">Crear</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('edi')" data-tip="Seleccionar/Deseleccionar Editar">
                                    <div class="col-check-mini" id="colMiniEdi"></div>
                                    <span class="col-label" style="color:#f59e0b">Editar</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('eli')" data-tip="Seleccionar/Deseleccionar Eliminar">
                                    <div class="col-check-mini" id="colMiniEli"></div>
                                    <span class="col-label" style="color:#ef4444">Eliminar</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('det')" data-tip="Seleccionar/Deseleccionar Detalle">
                                    <div class="col-check-mini" id="colMiniDet"></div>
                                    <span class="col-label" style="color:#8b5cf6">Detalle</span>
                                </button>
                            </th>
                            <th class="th-act">
                                <span class="text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase">Acción</span>
                            </th>
                         </tr>
                    </thead>

                    {{-- Skeleton tbody (visible mientras carga el perfil) --}}
                    <tbody id="skeletonBody" class="divide-y divide-[var(--surface-4)]">
                        @for($i = 0; $i < 6; $i++)
                         <tr>
                             <td>
                                <div class="module-cell">
                                    <div class="skeleton flex-shrink-0 rounded-lg" style="width:32px;height:32px"></div>
                                    <div>
                                        <div class="skeleton h-4 rounded mb-1.5" style="width:{{ 80 + ($i*20)%60 }}px"></div>
                                        <div class="skeleton rounded" style="height:10px;width:{{ 50+($i*15)%40 }}px"></div>
                                    </div>
                                </div>
                             </td>
                            @for($j=0;$j<5;$j++)
                             <td><div class="skeleton rounded-md mx-auto" style="width:24px;height:24px"></div></td>
                            @endfor
                             <td><div class="skeleton rounded-md ml-auto" style="width:70px;height:28px"></div></td>
                         </tr>
                        @endfor
                    </tbody>

                    {{-- Data tbody (inyectado por JS) --}}
                    <tbody id="matrixBody" class="hidden divide-y divide-[var(--surface-4)]"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- COPY MODAL --}}
<div class="copy-modal-overlay" id="copyModal" aria-modal="true" role="dialog">
    <div class="bg-[var(--surface-1)] border border-[var(--surface-4)] rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden"
         style="transform:scale(0.95);transition:transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);" id="copyModalContent">
        
        <div class="flex items-center justify-between px-6 py-5 border-b border-[var(--surface-4)] bg-[var(--surface-2)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-500"><i class="fas fa-copy"></i></div>
                <div>
                    <h3 class="text-base font-bold text-[var(--text-1)]">Clonar Permisos</h3>
                    <p class="text-xs text-[var(--text-3)] mt-0.5">Sobrescribe la configuración actual.</p>
                </div>
            </div>
            <button onclick="closeCopyModal()" class="action-btn text-lg" aria-label="Cerrar"><i class="fas fa-xmark"></i></button>
        </div>
        
        <div class="p-4 bg-[var(--surface-1)]">
            <p class="text-xs font-bold text-[var(--text-2)] uppercase tracking-wide mb-3 px-2">Selecciona perfil origen:</p>
            <div class="space-y-2 max-h-60 overflow-y-auto px-1" id="copyModalList"></div>
        </div>
        
        <div class="px-6 py-4 border-t border-[var(--surface-4)] bg-[var(--surface-2)] flex justify-end">
            <button onclick="closeCopyModal()" class="btn-ghost px-5 py-2 text-sm font-medium">Cancelar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── State ── */
    let allPerfiles = [], allModulos = [];
    let perfilActivo = null, perfilActivoNombre = '';
    let permisos = {}, original = {}, pendiente = {};
    let perfilActivoEsAdmin = false;

    // Variable global para saber si puede editar
    let puedeEditar = window.tienePermiso('Permisos-Perfil', 'bitEditar');

    // Aplicar capa visual si no puede editar
    if (!puedeEditar) {
        const toolbar = document.getElementById('toolbarActions');
        if (toolbar) toolbar.style.display = 'none';
        const tabla = document.getElementById('matrixTable');
        if (tabla) {
            tabla.style.opacity = '0.5';
            tabla.style.filter = 'grayscale(100%)';
        }
        const wrap = document.getElementById('matrixWrap');
        if (wrap) wrap.style.cursor = 'not-allowed';
    }

    const COLS = [
        { key:'ver', label:'Ver',      color:'#10b981' },
        { key:'agr', label:'Crear',    color:'#3b82f6' },
        { key:'edi', label:'Editar',   color:'#f59e0b' },
        { key:'eli', label:'Eliminar', color:'#ef4444' },
        { key:'det', label:'Detalle',  color:'#8b5cf6' },
    ];

    const ICONS = {
        usuario:'fa-users', perfil:'fa-id-badge', modulo:'fa-cubes',
        permiso:'fa-key', reporte:'fa-chart-pie', config:'fa-gear',
        dashboard:'fa-gauge-high', producto:'fa-box', venta:'fa-receipt',
        inventario:'fa-warehouse', seguridad:'fa-shield-halved',
        cliente:'fa-person', proveedor:'fa-truck', compra:'fa-basket-shopping',
        pago:'fa-credit-card', envio:'fa-paper-plane', default:'fa-cube',
    };

    /* ── Función para bloquear/desbloquear interacción cuando es Admin ── */
    function bloquearInteraccionAdmin(bloquear) {
        const matrixWrap = document.getElementById('matrixWrap');
        const toolbarActions = document.getElementById('toolbarActions');
        const unsavedBar = document.getElementById('unsavedBar');
        
        if (bloquear) {
            matrixWrap.classList.add('admin-blocked');
            if (toolbarActions) toolbarActions.style.display = 'none';
            if (unsavedBar) unsavedBar.classList.add('hidden');
            document.querySelectorAll('.col-header-btn').forEach(btn => {
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.5';
            });
        } else {
            matrixWrap.classList.remove('admin-blocked');
            if (toolbarActions && puedeEditar) toolbarActions.style.display = 'flex';
            document.querySelectorAll('.col-header-btn').forEach(btn => {
                btn.style.pointerEvents = 'auto';
                btn.style.opacity = '1';
            });
        }
    }

    /* ── Init ── */
    fetch('/api/permisos/catalogos')
        .then(r => r.json())
        .then(d => { 
            allPerfiles = d.perfiles; 
            allModulos = d.modulos; 
            renderProfileList(allPerfiles); 
        })
        .catch(e => {
            if(window.showToast) window.showToast('Error cargando catálogos de perfiles', 'error');
        });

    /* ── Profile dropdown ── */
    window.toggleProfileDropdown = () => {
        const dd = document.getElementById('profileDropdown');
        if (dd.classList.contains('open')) { closeProfileDropdown(); return; }
        dd.classList.add('open');
        document.getElementById('profileBtn').setAttribute('aria-expanded','true');
        setTimeout(() => document.getElementById('pdSearch').focus(), 50);
    };

    window.closeProfileDropdown = () => {
        document.getElementById('profileDropdown').classList.remove('open');
        document.getElementById('profileBtn').setAttribute('aria-expanded','false');
        document.getElementById('pdSearch').value = '';
        renderProfileList(allPerfiles);
    };

    window.handleProfileBtnKey = e => {
        if (['Enter',' ','ArrowDown'].includes(e.key)) { e.preventDefault(); toggleProfileDropdown(); }
    };

    window.filterProfiles = q => {
        renderProfileList(q.trim()
            ? allPerfiles.filter(p => p.strNombrePerfil.toLowerCase().includes(q.toLowerCase()))
            : allPerfiles, q.trim());
    };

    window.handlePdSearchKey = e => {
        if (e.key==='Escape')    { closeProfileDropdown(); document.getElementById('profileBtn').focus(); }
        if (e.key==='ArrowDown') { e.preventDefault(); document.querySelector('.pd-item[tabindex="0"]')?.focus(); }
    };

    function renderProfileList(list, hl = '') {
        const el = document.getElementById('pdList');
        if (!list.length) {
            el.innerHTML = `<div class="pd-item justify-center text-center" style="color:var(--text-3);pointer-events:none;font-size:12px"><i class="fas fa-search text-xs mr-2"></i>Sin resultados</div>`;
            return;
        }
        el.innerHTML = list.map(p => {
            const ini = p.strNombrePerfil.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
            const act = p.id === perfilActivo;
            const esAdmin = p.bitAdministrador === 1;
            const name = hl
                ? p.strNombrePerfil.replace(new RegExp(`(${hl.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')})`, 'gi'),
                    '<mark style="background:var(--neon-muted);color:var(--neon);border-radius:4px;padding:0 2px">$1</mark>')
                : p.strNombrePerfil;
            const adminBadge = esAdmin ? '<span class="ml-1 text-[10px] font-bold text-[var(--neon)]">⚡</span>' : '';
            return `<div class="pd-item${act?' active':''}" role="option" aria-selected="${act}" tabindex="0"
                onclick="selectProfile(${p.id},'${p.strNombrePerfil.replace(/'/g,"\\'")}');closeProfileDropdown();"
                onkeydown="handlePdItemKey(event,${p.id},'${p.strNombrePerfil.replace(/'/g,"\\'")}')">
                <div class="profile-avatar${act?' active':''}" style="font-size:10px">${ini}</div>
                <span class="flex-1">${name}${adminBadge}</span>
                ${act?'<i class="fas fa-check pd-check"></i>':''}
            </div>`;
        }).join('');
    }

    window.handlePdItemKey = (e,id,nombre) => {
        if (e.key==='Enter'||e.key===' ') { e.preventDefault(); selectProfile(id,nombre); closeProfileDropdown(); }
        if (e.key==='ArrowDown') { e.preventDefault(); e.target.nextElementSibling?.focus(); }
        if (e.key==='ArrowUp')   { e.preventDefault(); (e.target.previousElementSibling?.matches('.pd-item')?e.target.previousElementSibling:document.getElementById('pdSearch'))?.focus(); }
        if (e.key==='Escape')    { closeProfileDropdown(); document.getElementById('profileBtn').focus(); }
    };

    /* ── Select profile ── */
    window.selectProfile = (id, nombre) => {
        const perfilData = allPerfiles.find(p => p.id === id);
        const esAdmin = perfilData && perfilData.bitAdministrador === 1;
        perfilActivoEsAdmin = esAdmin;

        perfilActivo = id; 
        perfilActivoNombre = nombre; 
        pendiente = {};

        // UI Updates
        const ini = nombre.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
        document.getElementById('profileAvatarBtn').innerHTML = esAdmin ? '<i class="fas fa-bolt text-[10px]"></i>' : ini;
        document.getElementById('profileAvatarBtn').classList.toggle('active', true);
        document.getElementById('profileBtnLabel').textContent = nombre;
        document.getElementById('profileBtn').classList.add('selected');

        const tag = document.getElementById('activeProfileTag');
        tag.classList.remove('hidden'); tag.classList.add('lg:flex');
        document.getElementById('activeProfileName').textContent = nombre + (esAdmin ? ' ⚡' : '');
        
        updateUnsavedBar();

        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('matrixBody').classList.add('hidden');
        document.getElementById('skeletonBody').classList.remove('hidden');
        document.getElementById('matrixWrap').classList.remove('hidden');

        if (esAdmin) {
            permisos = {};
            allModulos.forEach(m => { 
                permisos[m.id] = {ver:true, agr:true, edi:true, eli:true, det:true}; 
            });
            original = JSON.parse(JSON.stringify(permisos));
            
            renderMatrix();
            bloquearInteraccionAdmin(true);
            if(window.showToast) window.showToast('⚡ Perfil Super Administrador: Acceso total protegido', 'info');
        } else {
            bloquearInteraccionAdmin(false);
            
            fetch(`/api/permisos?perfil=${id}`)
                .then(r => r.json())
                .then(data => {
                    permisos = {};
                    allModulos.forEach(m => { 
                        permisos[m.id] = {ver:false, agr:false, edi:false, eli:false, det:false}; 
                    });
                    const items = Array.isArray(data) ? data : (data.data ?? []);
                    items.forEach(item => {
                        if (permisos[item.idModulo] !== undefined)
                            permisos[item.idModulo] = {
                                ver:!!item.bitConsulta, agr:!!item.bitAgregar,
                                edi:!!item.bitEditar,   eli:!!item.bitEliminar, det:!!item.bitDetalle,
                            };
                    });
                    original = JSON.parse(JSON.stringify(permisos));
                    renderMatrix();
                })
                .catch(e => {
                    if(window.showToast) window.showToast('Error cargando permisos del perfil', 'error');
                });
        }
    };

    /* ── Render matrix ── */
    function renderMatrix() {
        const tbody = document.getElementById('matrixBody');
        
        tbody.innerHTML = allModulos.map(mod => {
            const p = permisos[mod.id];
            const iconKey = Object.keys(ICONS).find(k => mod.strNombreModulo.toLowerCase().includes(k)) || 'default';
            const iconFinal = perfilActivoEsAdmin ? 'fa-bolt text-[var(--neon)]' : ICONS[iconKey];
            
            const checks = COLS.map(col => `
                <td>
                    ${perfilActivoEsAdmin ? `
                        <div class="perm-check on-${col.key}" style="opacity:0.8; cursor:default;">
                            <i class="fas fa-check ck" aria-hidden="true"></i>
                        </div>
                    ` : `
                        <button class="perm-check${p[col.key]?' on-'+col.key:''}"
                            data-mod="${mod.id}" data-col="${col.key}"
                            aria-pressed="${p[col.key]}"
                            aria-label="${col.label} — ${mod.strNombreModulo}"
                            data-tip="${col.label}"
                            onclick="toggleCell(this)"
                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleCell(this);}">
                            <i class="fas fa-check ck" aria-hidden="true"></i>
                        </button>
                    `}
                </td>
            `).join('');
            
            const accionCelda = perfilActivoEsAdmin 
                ? `<span class="protected-badge"><i class="fas fa-shield-halved"></i> Protegido</span>`
                : `<button class="btn-row-save" id="saveBtn-${mod.id}"
                    onclick="saveRow(${mod.id})"
                    aria-label="Guardar ${mod.strNombreModulo}">
                    <i class="fas fa-check text-[10px]"></i> Guardar
                </button>`;

            return `<tr id="row-${mod.id}">
                <td>
                    <div class="module-cell">
                        <div class="module-icon-sm"><i class="fas ${iconFinal}"></i></div>
                        <div style="min-width:0">
                            <div class="module-name">${mod.strNombreModulo}</div>
                            ${mod.strDescripcion ? `<div class="module-desc">${mod.strDescripcion}</div>` : ''}
                        </div>
                    </div>
                </td>
                ${checks}
                <td class="text-right pr-5">${accionCelda}</td>
            </tr>`;
        }).join('');

        document.getElementById('skeletonBody').classList.add('hidden');
        tbody.classList.remove('hidden');
        
        if (!perfilActivoEsAdmin) {
            updateColumnHeaders();
        } else {
            COLS.forEach(col => {
                const miniId = `colMini${col.key.charAt(0).toUpperCase() + col.key.slice(1)}`;
                const miniElem = document.getElementById(miniId);
                if (miniElem) miniElem.classList.add('all-on');
            });
        }
    }

    /* ── Dirty state ── */
    function checkIsDirty(modId) {
        if (perfilActivoEsAdmin) return;
        const isDirty = COLS.some(col => permisos[modId][col.key] !== original[modId][col.key]);
        document.getElementById(`row-${modId}`)?.classList.toggle('has-changes', isDirty);
        document.getElementById(`saveBtn-${modId}`)?.classList.toggle('dirty', isDirty);
        if (isDirty) pendiente[modId] = true; else delete pendiente[modId];
        updateUnsavedBar();
    }

    /* ── Toggles ── */
    window.toggleCell = btn => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        const modId = +btn.dataset.mod, col = btn.dataset.col;
        permisos[modId][col] = !permisos[modId][col];
        btn.classList.toggle(`on-${col}`, permisos[modId][col]);
        btn.setAttribute('aria-pressed', permisos[modId][col]);
        checkIsDirty(modId);
        updateColumnHeaders();
    };

    window.toggleColumn = col => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        const allOn = allModulos.every(m => permisos[m.id][col]);
        allModulos.forEach(m => {
            permisos[m.id][col] = !allOn;
            const btn = document.querySelector(`[data-mod="${m.id}"][data-col="${col}"]`);
            if (btn) { btn.classList.toggle(`on-${col}`, !allOn); btn.setAttribute('aria-pressed', String(!allOn)); }
            checkIsDirty(m.id);
        });
        updateColumnHeaders();
    };

    window.grantAll = () => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        allModulos.forEach(m => {
            COLS.forEach(col => {
                permisos[m.id][col.key] = true;
                const btn = document.querySelector(`[data-mod="${m.id}"][data-col="${col.key}"]`);
                if (btn) { btn.classList.add(`on-${col.key}`); btn.setAttribute('aria-pressed','true'); }
            });
            checkIsDirty(m.id);
        });
        updateColumnHeaders();
    };

    window.revokeAll = () => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        allModulos.forEach(m => {
            COLS.forEach(col => {
                permisos[m.id][col.key] = false;
                const btn = document.querySelector(`[data-mod="${m.id}"][data-col="${col.key}"]`);
                if (btn) { btn.classList.remove(`on-${col.key}`); btn.setAttribute('aria-pressed','false'); }
            });
            checkIsDirty(m.id);
        });
        updateColumnHeaders();
    };

    window.discardAll = () => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        permisos = JSON.parse(JSON.stringify(original));
        pendiente = {};
        renderMatrix();
        updateUnsavedBar();
        if(window.showToast) window.showToast('Cambios descartados','info');
    };

    function updateColumnHeaders() {
        if (!perfilActivo || perfilActivoEsAdmin || !allModulos.length) return;
        COLS.forEach(col => {
            const allOn = allModulos.every(m => permisos[m.id]?.[col.key]);
            const miniId = `colMini${col.key.charAt(0).toUpperCase() + col.key.slice(1)}`;
            document.getElementById(miniId)?.classList.toggle('all-on', allOn);
        });
    }

    function updateUnsavedBar() {
        const count = Object.keys(pendiente).length;
        document.getElementById('unsavedLabel').textContent = `${count} ${count===1?'módulo':'módulos'} sin guardar`;
        const bar = document.getElementById('unsavedBar');
        if (count > 0 && !perfilActivoEsAdmin) { 
            bar.classList.remove('hidden'); 
            bar.classList.add('flex'); 
        } else { 
            bar.classList.add('hidden');    
            bar.classList.remove('flex'); 
        }
    }

    /* ── Save ── */
    window.saveRow = (modId, silent=false) => {
        if (!perfilActivo || perfilActivoEsAdmin || !pendiente[modId] || !puedeEditar) return Promise.resolve(false);
        const p = permisos[modId];
        const sb = document.getElementById(`saveBtn-${modId}`);
        if (sb) { sb.classList.add('busy'); sb.innerHTML='<i class="fas fa-spinner fa-spin text-[10px]"></i> Guardando'; }

        return fetch('/api/permisos', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({idPerfil:perfilActivo, modulos:[{
                idModulo:parseInt(modId), bitConsulta:p.ver, bitAgregar:p.agr,
                bitEditar:p.edi, bitEliminar:p.eli, bitDetalle:p.det,
            }]}),
        }).then(r=>r.json()).then(res => {
            if (sb) { sb.classList.remove('busy'); sb.innerHTML='<i class="fas fa-check text-[10px]"></i> Guardar'; }
            if (res.success) { 
                original[modId]={...permisos[modId]}; 
                checkIsDirty(modId); 
                if(!silent && window.showToast) window.showToast('Permisos guardados','success'); 
                return true; 
            }
            return false;
        });
    };

    window.saveAllPending = () => {
        const ids = Object.keys(pendiente);
        if (!ids.length || perfilActivoEsAdmin || !puedeEditar) return;
        const btn = document.getElementById('btnSaveAll');
        btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin text-sm"></i>';
        
        fetch('/api/permisos', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({idPerfil:perfilActivo, modulos:ids.map(id=>({
                idModulo:parseInt(id), bitConsulta:permisos[id].ver, bitAgregar:permisos[id].agr,
                bitEditar:permisos[id].edi, bitEliminar:permisos[id].eli, bitDetalle:permisos[id].det,
            }))}),
        }).then(r=>r.json()).then(res => {
            btn.disabled=false; btn.innerHTML='Guardar Todo';
            if (res.success) {
                ids.forEach(id=>{ original[id]={...permisos[id]}; checkIsDirty(id); });
                if(window.showToast) window.showToast(`Se actualizaron ${ids.length} módulos`,'success');
            } else {
                if(window.showToast) window.showToast('Error al guardar permisos', 'error');
            }
        });
    };

    /* ── Copy modal ── */
    window.openCopyModal = () => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) { 
            if(window.showToast) window.showToast(perfilActivoEsAdmin ? 'El perfil de Super Administrador está protegido' : (puedeEditar ? 'Selecciona un perfil primero' : 'No tienes permisos de edición'), 'info'); 
            return; 
        }
        const others = allPerfiles.filter(p => p.id !== perfilActivo && p.bitAdministrador !== 1);
        document.getElementById('copyModalList').innerHTML = !others.length
            ? `<p class="text-xs text-[var(--text-3)] text-center py-4">No existen otros perfiles disponibles para copiar.</p>`
            : others.map(p=>`
                <button onclick="copyFromProfile(${p.id},'${p.strNombrePerfil.replace(/'/g,"\\'")}');closeCopyModal();"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-[var(--surface-3)] transition-colors text-left group border border-transparent hover:border-[var(--surface-4)]">
                    <div class="profile-avatar" style="font-size:10px">${p.strNombrePerfil.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2)}</div>
                    <span class="text-sm font-bold text-[var(--text-2)] group-hover:text-[var(--text-1)] transition-colors">${p.strNombrePerfil}</span>
                    <i class="fas fa-download text-sm text-[var(--text-3)] ml-auto opacity-0 group-hover:opacity-100 group-hover:text-blue-400 transition-all"></i>
                </button>`).join('');
        
        document.getElementById('copyModal').classList.add('open');
        setTimeout(()=>{ document.getElementById('copyModalContent').style.transform='scale(1)'; },10);
    };

    window.closeCopyModal = () => {
        document.getElementById('copyModalContent').style.transform='scale(0.95)';
        setTimeout(()=>document.getElementById('copyModal').classList.remove('open'),200);
    };

    window.copyFromProfile = (fromId, fromNombre) => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        fetch(`/api/permisos?perfil=${fromId}`).then(r=>r.json()).then(data => {
            allModulos.forEach(m => { permisos[m.id]={ver:false,agr:false,edi:false,eli:false,det:false}; });
            const items = Array.isArray(data)?data:(data.data??[]);
            items.forEach(item => {
                if (permisos[item.idModulo]!==undefined)
                    permisos[item.idModulo]={ver:!!item.bitConsulta,agr:!!item.bitAgregar,edi:!!item.bitEditar,eli:!!item.bitEliminar,det:!!item.bitDetalle};
            });
            renderMatrix();
            allModulos.forEach(m=>checkIsDirty(m.id));
            updateColumnHeaders();
            if(window.showToast) window.showToast(`Permisos copiados de "${fromNombre}"`,'info');
        });
    };

    document.addEventListener('click', e => {
        if (!document.getElementById('profileSelectorWrap')?.contains(e.target))
            closeProfileDropdown();
    });

    // 🚀 MOTOR DE SINCRONIZACIÓN EN TIEMPO REAL (Silent Polling) 🚀
    setInterval(async () => {
        // Solo verificamos la base de datos si hay un perfil activo, NO es admin (el admin siempre es true), 
        // y MUY IMPORTANTE: no hay cambios sin guardar pendientes.
        if (!perfilActivo || perfilActivoEsAdmin || Object.keys(pendiente).length > 0) return;

        try {
            const res = await fetch(`/api/permisos?perfil=${perfilActivo}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            const items = Array.isArray(data) ? data : (data.data ?? []);

            // Armamos un objeto temporal para comparar con lo que tenemos en pantalla
            let serverPermisos = {};
            allModulos.forEach(m => { serverPermisos[m.id] = {ver:false, agr:false, edi:false, eli:false, det:false}; });
            
            items.forEach(item => {
                if (serverPermisos[item.idModulo] !== undefined) {
                    serverPermisos[item.idModulo] = {
                        ver:!!item.bitConsulta, agr:!!item.bitAgregar,
                        edi:!!item.bitEditar, eli:!!item.bitEliminar, det:!!item.bitDetalle
                    };
                }
            });

            // Comparamos el string de ambos objetos. Si alguien más lo modificó, se actualiza la matriz.
            if (JSON.stringify(original) !== JSON.stringify(serverPermisos)) {
                permisos = JSON.parse(JSON.stringify(serverPermisos));
                original = JSON.parse(JSON.stringify(serverPermisos));
                renderMatrix(); // Renderiza suavecito sin flashazos de carga
            }
        } catch (e) {
            // Falla silenciosa de conexión
        }
    }, 5000);

}); // DOMContentLoaded
</script>
@endsection