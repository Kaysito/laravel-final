@extends('layouts.app')

@section('title', 'Matriz de Permisos')

@section('breadcrumb')
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
   PERMISSION MATRIX
══════════════════════════════════════════════════════════ */

:root {
    --w-mod:  240px;
    --w-perm:  84px;
    --w-act:  108px;
}

@media (max-width: 768px) {
    :root {
        --w-mod:  160px;
        --w-perm:  68px;
        --w-act:   90px;
    }
}

/* Wrapper con scroll — thead sticky vive dentro */
.matrix-wrap {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 340px);
    flex: 1;
    -webkit-overflow-scrolling: touch;
}

@media (max-width: 768px) {
    .matrix-wrap {
        max-height: calc(100vh - 300px);
    }
}

.mx {
    width: 100%;
    min-width: calc(var(--w-mod) + var(--w-perm)*5 + var(--w-act));
    border-collapse: collapse;
    table-layout: fixed;
}

.mx .c-mod  { width: var(--w-mod);  }
.mx .c-perm { width: var(--w-perm); }
.mx .c-act  { width: var(--w-act);  }

/* Ocultar columna "Detalle" en pantallas muy pequeñas */
@media (max-width: 480px) {
    .col-det { display: none; }
    :root { --w-mod: 140px; --w-perm: 64px; --w-act: 80px; }
    .mx { min-width: calc(var(--w-mod) + var(--w-perm)*4 + var(--w-act)); }
}

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
    box-shadow: 0 4px 6px -4px rgba(0,0,0,0.06);
}
.mx thead th.th-mod { text-align: left; padding: 0 12px 0 16px; }
@media (min-width: 640px) {
    .mx thead th.th-mod { padding: 0 16px 0 24px; }
}
.mx thead th.th-act { text-align: right; padding: 0 16px; }
@media (min-width: 640px) {
    .mx thead th.th-act { padding: 0 20px; }
}

/* ── Botón cabecera columna ── */
.col-header-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    cursor: pointer;
    width: 100%;
    height: 50px;
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
    font-size: 9px;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    white-space: nowrap;
}
@media (min-width: 640px) {
    .col-label { font-size: 10px; }
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
    height: 52px;
}
.mx tbody td:first-child { text-align: left; }
.mx tbody td:last-child  { text-align: right; padding-right: 14px; }
@media (min-width: 640px) {
    .mx tbody td:last-child { padding-right: 20px; }
}

/* Módulo cell */
.module-cell {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 8px 0 12px;
    height: 52px;
}
@media (min-width: 640px) {
    .module-cell { gap: 12px; padding: 0 16px 0 24px; }
}
.module-icon-sm {
    width: 28px; height: 28px;
    border-radius: 7px;
    background: var(--surface-3);
    border: 1px solid var(--surface-4);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; color: var(--text-3); flex-shrink: 0;
}
@media (min-width: 640px) {
    .module-icon-sm { width: 32px; height: 32px; }
}
.module-name {
    font-size: 12px; font-weight: 600; color: var(--text-1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
@media (min-width: 640px) {
    .module-name { font-size: 13px; }
}
.module-desc {
    font-size: 10px; color: var(--text-3); margin-top: 1px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ── Checkbox ── */
.perm-check {
    width: 22px; height: 22px;
    border-radius: 6px;
    border: 1.5px solid var(--surface-5);
    background: var(--surface-1);
    cursor: pointer; transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: inline-flex; align-items: center; justify-content: center;
    outline: none;
}
@media (min-width: 640px) {
    .perm-check { width: 24px; height: 24px; }
}
.perm-check:hover { border-color: var(--text-3); background: var(--surface-2); transform: translateY(-1px); }
.perm-check:focus-visible { outline: 2px solid var(--neon); outline-offset: 2px; }
.perm-check .ck {
    opacity: 0; transform: scale(0.2);
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); font-size: 10px; font-weight: 900;
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
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 6px;
    font-size: 10px; font-weight: 700;
    background: rgba(230,55,87,.10); border: 1px solid rgba(230,55,87,.30);
    color: var(--neon); cursor: pointer; transition: all .2s;
    white-space: nowrap; opacity: 0; pointer-events: none;
    transform: translateX(8px);
}
@media (min-width: 640px) {
    .btn-row-save { padding: 6px 12px; font-size: 11px; }
}
.btn-row-save.dirty { opacity: 1; pointer-events: auto; transform: translateX(0); }
.btn-row-save:hover { background: var(--neon); color: white; border-color: var(--neon); }
.btn-row-save.busy  { opacity: .5; pointer-events: none; }

/* ── Perfil protegido ── */
.protected-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 6px;
    font-size: 9px; font-weight: 800;
    background: rgba(230,55,87,.15); border: 1px solid rgba(230,55,87,.40);
    color: var(--neon); letter-spacing: 0.4px; white-space: nowrap;
}
@media (min-width: 640px) {
    .protected-badge { padding: 6px 12px; font-size: 10px; }
}
.protected-badge i { font-size: 9px; }

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
    transition: all .2s ease; width: 100%; outline: none;
}
@media (min-width: 640px) {
    .profile-btn { min-width: 220px; width: auto; }
}
.profile-btn:hover { border-color: var(--neon-border); color: var(--text-1); }
.profile-btn.selected { border-color: var(--neon); color: var(--text-1); background: var(--neon-muted); }
.profile-avatar {
    width: 24px; height: 24px; border-radius: 6px; background: var(--surface-4);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 800; color: var(--text-2); flex-shrink: 0;
    font-family: 'JetBrains Mono', monospace;
}
.profile-avatar.active { background: var(--neon-dark); color: white; }

.profile-dropdown {
    position: absolute; top: calc(100% + 8px); left: 0; width: 100%; min-width: 260px;
    background: var(--surface-1); border: 1px solid var(--surface-4); border-radius: 12px;
    box-shadow: 0 20px 40px -10px rgba(0,0,0,.15); z-index: 200; overflow: hidden;
    opacity: 0; transform: translateY(-8px); pointer-events: none; transition: all 0.2s ease;
}
@media (min-width: 640px) {
    .profile-dropdown { width: auto; min-width: 280px; }
}
.profile-dropdown.open { opacity: 1; transform: translateY(0); pointer-events: auto; }

.pd-search {
    display: flex; align-items: center; gap: 10px; padding: 12px 16px;
    border-bottom: 1px solid var(--surface-4); background: var(--surface-2);
}
.pd-search input { flex:1; background:transparent; border:none; outline:none; color:var(--text-1); font-size:13px; font-weight:500; }
.pd-search input::placeholder { color:var(--text-3); font-weight:400; }
.pd-list { max-height: 240px; overflow-y: auto; padding: 6px; }
.pd-item {
    display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px;
    cursor: pointer; color: var(--text-2); font-size: 13px; font-weight: 500; transition: all .15s;
}
.pd-item:hover, .pd-item:focus { background: var(--surface-3); color: var(--text-1); outline: none; }
.pd-item.active { color: var(--neon); background: var(--neon-muted); }
.pd-item .pd-check { margin-left: auto; color: var(--neon); font-size: 12px; }

/* ── Toolbar pills ── */
.toolbar-pill {
    display: inline-flex; align-items: center; gap: 7px; padding: 7px 12px;
    border-radius: 8px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--surface-4); background: var(--surface-1);
    color: var(--text-2); cursor: pointer; transition: all .2s; white-space: nowrap; outline: none;
}
.toolbar-pill:hover:not(:disabled) { border-color: var(--text-3); color: var(--text-1); background: var(--surface-2); transform: translateY(-1px); }
.toolbar-pill.danger:hover:not(:disabled) { border-color: #f87171; color: white; background: #ef4444; }
.toolbar-pill:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; }

/* Toolbar scroll en mobile */
.toolbar-pills-scroll {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: 2px;
}
.toolbar-pills-scroll::-webkit-scrollbar { display: none; }

/* ── Unsaved dot ── */
.unsaved-dot { width:8px; height:8px; border-radius:50%; background:#f59e0b; display:inline-block; animation:pdot 2s ease infinite; }
@keyframes pdot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.8)} }

/* ── Copy modal overlay ── */
.copy-modal-overlay {
    position: fixed; inset: 0; z-index: 300;
    background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center;
}
.copy-modal-overlay.open { display: flex; }
.copy-modal-overlay .modal-box {
    transform: scale(0.95); opacity: 0;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
}
.copy-modal-overlay.open .modal-box { transform: scale(1); opacity: 1; }

/* ── Estado bloqueado Admin ── */
.matrix-wrap.admin-blocked { pointer-events: none; opacity: 0.85; }
.matrix-wrap.admin-blocked .mx tbody tr:hover { background: transparent; }
</style>
@endsection

@section('content')
<div class="flex-1 overflow-hidden flex flex-col p-4 sm:p-6 lg:p-8">

    {{-- PAGE HEADER --}}
    <div class="flex-shrink-0 mb-4 sm:mb-6">
        <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-1)] tracking-tight">Matriz de Permisos (RBAC)</h1>
                    <p class="text-xs sm:text-sm text-[var(--text-3)] mt-0.5">Selecciona un perfil y configura sus privilegios de acceso por módulo.</p>
                </div>
                {{-- Active profile tag (desktop) --}}
                <div id="activeProfileTag" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--surface-1)] border border-[var(--surface-4)] self-start sm:self-auto">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs font-bold text-[var(--text-1)] uppercase tracking-wider" id="activeProfileName"></span>
                </div>
            </div>

            {{-- Barra de cambios sin guardar --}}
            <div id="unsavedBar" class="hidden items-center gap-3 px-4 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 flex-wrap">
                <span class="unsaved-dot"></span>
                <span class="text-sm text-amber-500 font-bold" id="unsavedLabel">Cambios sin guardar</span>
                <div class="w-px h-4 bg-amber-500/20 hidden sm:block"></div>
                <div class="flex items-center gap-2 ml-auto sm:ml-0">
                    <button onclick="discardAll()" type="button"
                            class="text-xs font-bold text-[var(--text-3)] hover:text-amber-600 transition-colors px-2 py-1">
                        Descartar
                    </button>
                    <button onclick="saveAllPending()" id="btnSaveAll" type="button"
                            class="text-xs font-bold text-white bg-amber-500 border border-amber-600 px-4 py-1.5 rounded-lg hover:bg-amber-600 transition-all disabled:opacity-50">
                        Guardar Todo
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTROL BAR --}}
    <div class="flex-shrink-0 mb-4 sm:mb-6">
        <div class="card px-4 sm:px-5 py-3 sm:py-4 flex flex-col gap-3 shadow-sm">

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                {{-- Selector de perfil --}}
                <div class="profile-selector-wrap z-30 w-full sm:w-auto" id="profileSelectorWrap">
                    <button class="profile-btn" id="profileBtn"
                            aria-haspopup="listbox" aria-expanded="false"
                            onclick="toggleProfileDropdown()"
                            onkeydown="handleProfileBtnKey(event)"
                            type="button">
                        <div class="profile-avatar" id="profileAvatarBtn"><i class="fas fa-search text-[10px]"></i></div>
                        <span id="profileBtnLabel" class="flex-1 text-left truncate">Selecciona un perfil...</span>
                        <i class="fas fa-chevron-down text-[10px] text-[var(--text-3)] flex-shrink-0"></i>
                    </button>

                    <div class="profile-dropdown" id="profileDropdown" role="listbox">
                        <div class="pd-search">
                            <i class="fas fa-magnifying-glass text-[var(--text-3)] text-sm flex-shrink-0"></i>
                            <input type="text" id="pdSearch" placeholder="Buscar perfil..." autocomplete="off"
                                   oninput="filterProfiles(this.value)"
                                   onkeydown="handlePdSearchKey(event)">
                        </div>
                        <div class="pd-list" id="pdList">
                            <div class="pd-item" style="color:var(--text-3);pointer-events:none;font-size:12px;">
                                <i class="fas fa-spinner fa-spin text-xs mr-2"></i> Cargando...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block w-px h-7 bg-[var(--surface-4)]"></div>

                {{-- Toolbar pills con scroll en mobile --}}
                <div class="toolbar-pills-scroll" id="toolbarActions">
                    <button onclick="grantAll()"  id="pillGrant"  type="button" class="toolbar-pill" disabled>
                        <i class="fas fa-check-double text-[var(--neon)]"></i>
                        <span>Todo acceso</span>
                    </button>
                    <button onclick="revokeAll()" id="pillRevoke" type="button" class="toolbar-pill danger" disabled>
                        <i class="fas fa-ban"></i>
                        <span>Sin acceso</span>
                    </button>
                    <button onclick="openCopyModal()" id="pillClone" type="button" class="toolbar-pill" disabled>
                        <i class="fas fa-copy text-blue-500"></i>
                        <span>Clonar de...</span>
                    </button>

                    <div class="w-px h-5 bg-[var(--surface-4)] flex-shrink-0 hidden sm:block"></div>

                    <button onclick="exportToExcel()" id="pillExport" type="button" class="toolbar-pill" disabled>
                        <i class="fas fa-file-excel text-emerald-500"></i>
                        <span>Exportar</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- MATRIX --}}
    <div class="flex-1 overflow-hidden">
        <div class="card h-full flex flex-col overflow-hidden shadow-sm border border-[var(--surface-4)]">

            {{-- Empty state --}}
            <div id="emptyState" class="flex flex-col items-center justify-center py-16 sm:py-20 text-center flex-1 bg-[var(--surface-1)]">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mb-5 shadow-inner">
                    <i class="fas fa-fingerprint text-2xl sm:text-3xl text-[var(--text-3)]"></i>
                </div>
                <p class="text-base sm:text-lg font-bold text-[var(--text-1)] mb-2">Selecciona un Perfil</p>
                <p class="text-xs sm:text-sm text-[var(--text-3)] max-w-xs leading-relaxed px-4">
                    Elige un perfil en el menú superior para visualizar y modificar sus privilegios en la matriz.
                </p>
            </div>

            {{-- Tabla de Permisos --}}
            <div class="matrix-wrap hidden bg-[var(--surface-1)]" id="matrixWrap">
                <table class="mx" id="matrixTable">
                    <colgroup>
                        <col class="c-mod">
                        <col class="c-perm">
                        <col class="c-perm">
                        <col class="c-perm">
                        <col class="c-perm">
                        <col class="c-perm col-det">
                        <col class="c-act">
                    </colgroup>

                    <thead>
                        <tr>
                            <th class="th-mod">
                                <span class="text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase">Módulo</span>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('ver')" type="button">
                                    <div class="col-check-mini" id="colMiniVer"></div>
                                    <span class="col-label" style="color:#10b981">Ver</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('agr')" type="button">
                                    <div class="col-check-mini" id="colMiniAgr"></div>
                                    <span class="col-label" style="color:#3b82f6">Crear</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('edi')" type="button">
                                    <div class="col-check-mini" id="colMiniEdi"></div>
                                    <span class="col-label" style="color:#f59e0b">Editar</span>
                                </button>
                            </th>
                            <th>
                                <button class="col-header-btn" onclick="toggleColumn('eli')" type="button">
                                    <div class="col-check-mini" id="colMiniEli"></div>
                                    <span class="col-label" style="color:#ef4444">Eliminar</span>
                                </button>
                            </th>
                            <th class="col-det">
                                <button class="col-header-btn" onclick="toggleColumn('det')" type="button">
                                    <div class="col-check-mini" id="colMiniDet"></div>
                                    <span class="col-label" style="color:#8b5cf6">Detalle</span>
                                </button>
                            </th>
                            <th class="th-act">
                                <span class="text-[10px] font-bold tracking-widest text-[var(--text-3)] uppercase">Acción</span>
                            </th>
                        </tr>
                    </thead>

                    {{-- Skeleton tbody --}}
                    <tbody id="skeletonBody" class="divide-y divide-[var(--surface-4)]">
                        @for($i = 0; $i < 6; $i++)
                        <tr>
                            <td>
                                <div class="module-cell">
                                    <div class="skeleton flex-shrink-0 rounded-lg" style="width:28px;height:28px"></div>
                                    <div>
                                        <div class="skeleton h-3.5 rounded mb-1.5" style="width:{{ 70 + ($i*20)%60 }}px"></div>
                                        <div class="skeleton rounded" style="height:10px;width:{{ 45+($i*15)%40 }}px"></div>
                                    </div>
                                </div>
                            </td>
                            @for($j=0;$j<5;$j++)
                            <td{{ $j==4 ? ' class="col-det"' : '' }}>
                                <div class="skeleton rounded-md mx-auto" style="width:22px;height:22px"></div>
                            </td>
                            @endfor
                            <td><div class="skeleton rounded-md ml-auto" style="width:60px;height:26px"></div></td>
                        </tr>
                        @endfor
                    </tbody>

                    {{-- Data tbody --}}
                    <tbody id="matrixBody" class="hidden divide-y divide-[var(--surface-4)]"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- COPY MODAL --}}
<div class="copy-modal-overlay" id="copyModal" aria-modal="true" role="dialog" aria-labelledby="copyModalTitle">
    <div class="modal-box bg-[var(--surface-1)] border border-[var(--surface-4)] rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden" id="copyModalContent">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--surface-4)] bg-[var(--surface-2)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-500 flex-shrink-0">
                    <i class="fas fa-copy text-sm"></i>
                </div>
                <div>
                    <h3 id="copyModalTitle" class="text-sm sm:text-base font-bold text-[var(--text-1)]">Clonar Permisos</h3>
                    <p class="text-xs text-[var(--text-3)]">Sobrescribe la configuración actual.</p>
                </div>
            </div>
            <button onclick="closeCopyModal()" type="button" class="action-btn text-lg" aria-label="Cerrar modal">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="p-4 bg-[var(--surface-1)]">
            <p class="text-xs font-bold text-[var(--text-2)] uppercase tracking-wide mb-3 px-2">Selecciona perfil origen:</p>
            <div class="space-y-1.5 max-h-56 overflow-y-auto px-1" id="copyModalList"></div>
        </div>

        <div class="px-5 py-3.5 border-t border-[var(--surface-4)] bg-[var(--surface-2)] flex justify-end">
            <button onclick="closeCopyModal()" type="button" class="btn-ghost px-5 py-2 text-sm font-medium">Cancelar</button>
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
    let puedeEditar = window.tienePermiso('Permisos-Perfil', 'bitEditar');

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

    /* ── Helpers de UI ── */
    function setToolbarEnabled(enabled) {
        ['pillGrant', 'pillRevoke', 'pillClone', 'pillExport'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.disabled = !enabled;
        });
    }

    /* ── Export a Excel ── */
    window.exportToExcel = () => {
        if (!perfilActivo || !allModulos.length) return;

        const btn = document.getElementById('pillExport');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-emerald-500"></i><span>Exportando…</span>';

        const loadXLSX = () => new Promise((resolve, reject) => {
            if (window.XLSX) { resolve(window.XLSX); return; }
            const s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
            s.onload  = () => resolve(window.XLSX);
            s.onerror = () => reject(new Error('No se pudo cargar SheetJS'));
            document.head.appendChild(s);
        });

        loadXLSX().then(XLSX => {
            const now     = new Date();
            const fecha   = now.toLocaleDateString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric' });
            const hora    = now.toLocaleTimeString('es-MX', { hour:'2-digit', minute:'2-digit' });
            const esAdmin = perfilActivoEsAdmin;

            // ── Construir filas ──
            const HEADER = ['Módulo', 'Descripción', 'Ver', 'Crear', 'Editar', 'Eliminar', 'Detalle'];
            const rows   = allModulos.map(mod => {
                const p = permisos[mod.id] || {};
                return [
                    mod.strNombreModulo,
                    mod.strDescripcion || '',
                    p.ver ? '✓' : '—',
                    p.agr ? '✓' : '—',
                    p.edi ? '✓' : '—',
                    p.eli ? '✓' : '—',
                    p.det ? '✓' : '—',
                ];
            });

            // Totales
            const total   = allModulos.length;
            const countFn = col => allModulos.filter(m => permisos[m.id]?.[col]).length;
            const totRow  = [
                'TOTAL HABILITADOS', '',
                countFn('ver'), countFn('agr'), countFn('edi'), countFn('eli'), countFn('det')
            ];

            // ── Workbook ──
            const wb = XLSX.utils.book_new();
            const wsData = [
                [`Matriz de Permisos — ${perfilActivoNombre}${esAdmin ? ' ⚡' : ''}`],
                [`Exportado el ${fecha} a las ${hora}`],
                [],
                HEADER,
                ...rows,
                [],
                totRow,
            ];

            const ws = XLSX.utils.aoa_to_sheet(wsData);

            // Anchos de columna
            ws['!cols'] = [
                { wch: 28 }, { wch: 32 },
                { wch: 9 }, { wch: 9 }, { wch: 9 }, { wch: 11 }, { wch: 10 },
            ];

            // Merge para el título (fila 1, cols A-G)
            ws['!merges'] = [
                { s:{r:0,c:0}, e:{r:0,c:6} },
                { s:{r:1,c:0}, e:{r:1,c:6} },
            ];

            XLSX.utils.book_append_sheet(wb, ws, 'Permisos');

            // Nombre de archivo
            const safeName = perfilActivoNombre.replace(/[^a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑ ]/g, '').trim().replace(/ /g, '_');
            const nowStr   = `${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}${String(now.getDate()).padStart(2,'0')}`;
            XLSX.writeFile(wb, `Permisos_${safeName}_${nowStr}.xlsx`);

            if (window.showToast) window.showToast('Exportación completada', 'success');
        }).catch(err => {
            console.error(err);
            if (window.showToast) window.showToast('Error al exportar', 'error');
        }).finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-file-excel text-emerald-500"></i><span>Exportar</span>';
        });
    };

    function bloquearInteraccionAdmin(bloquear) {
        const matrixWrap = document.getElementById('matrixWrap');
        document.querySelectorAll('.col-header-btn').forEach(btn => {
            btn.style.pointerEvents = bloquear ? 'none' : 'auto';
            btn.style.opacity       = bloquear ? '0.5' : '1';
        });
        if (bloquear) {
            matrixWrap.classList.add('admin-blocked');
            setToolbarEnabled(false);
            // Export sigue disponible aunque sea admin
            const expBtn = document.getElementById('pillExport');
            if (expBtn) expBtn.disabled = false;
            document.getElementById('unsavedBar').classList.add('hidden');
            document.getElementById('unsavedBar').classList.remove('flex');
        } else {
            matrixWrap.classList.remove('admin-blocked');
            if (puedeEditar) setToolbarEnabled(true);
            else {
                // Sin permiso de edición: solo exportar habilitado
                const expBtn = document.getElementById('pillExport');
                if (expBtn) expBtn.disabled = false;
            }
        }
    }

    /* ── Init ── */
    if (!puedeEditar) {
        setToolbarEnabled(false);
        // Export disponible aunque no tenga permiso de edición
        // (se habilitará al cargar un perfil en bloquearInteraccionAdmin)
    }

    fetch('/api/permisos/catalogos')
        .then(r => r.json())
        .then(d => {
            allPerfiles = d.perfiles;
            allModulos  = d.modulos;
            renderProfileList(allPerfiles);
        })
        .catch(() => {
            if (window.showToast) window.showToast('Error cargando catálogos', 'error');
        });

    /* ── Profile dropdown ── */
    window.toggleProfileDropdown = () => {
        const dd = document.getElementById('profileDropdown');
        dd.classList.contains('open') ? closeProfileDropdown() : openProfileDropdown();
    };

    function openProfileDropdown() {
        document.getElementById('profileDropdown').classList.add('open');
        document.getElementById('profileBtn').setAttribute('aria-expanded', 'true');
        setTimeout(() => document.getElementById('pdSearch').focus(), 50);
    }

    window.closeProfileDropdown = () => {
        document.getElementById('profileDropdown').classList.remove('open');
        document.getElementById('profileBtn').setAttribute('aria-expanded', 'false');
        document.getElementById('pdSearch').value = '';
        renderProfileList(allPerfiles);
    };

    window.handleProfileBtnKey = e => {
        if (['Enter', ' ', 'ArrowDown'].includes(e.key)) { e.preventDefault(); toggleProfileDropdown(); }
    };

    window.filterProfiles = q => {
        renderProfileList(q.trim()
            ? allPerfiles.filter(p => p.strNombrePerfil.toLowerCase().includes(q.toLowerCase()))
            : allPerfiles, q.trim());
    };

    window.handlePdSearchKey = e => {
        if (e.key === 'Escape')    { closeProfileDropdown(); document.getElementById('profileBtn').focus(); }
        if (e.key === 'ArrowDown') { e.preventDefault(); document.querySelector('.pd-item[tabindex="0"]')?.focus(); }
    };

    function renderProfileList(list, hl = '') {
        const el = document.getElementById('pdList');
        if (!list.length) {
            el.innerHTML = `<div class="pd-item justify-center text-center" style="color:var(--text-3);pointer-events:none;font-size:12px"><i class="fas fa-search text-xs mr-2"></i>Sin resultados</div>`;
            return;
        }
        el.innerHTML = list.map(p => {
            const ini     = p.strNombrePerfil.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
            const act     = p.id === perfilActivo;
            const esAdmin = p.bitAdministrador === 1;
            const safe    = p.strNombrePerfil.replace(/'/g, "\\'");
            const name    = hl
                ? p.strNombrePerfil.replace(new RegExp(`(${hl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi'),
                    '<mark style="background:var(--neon-muted);color:var(--neon);border-radius:3px;padding:0 2px">$1</mark>')
                : p.strNombrePerfil;
            return `<div class="pd-item${act ? ' active' : ''}" role="option" aria-selected="${act}" tabindex="0"
                onclick="selectProfile(${p.id},'${safe}');closeProfileDropdown();"
                onkeydown="handlePdItemKey(event,${p.id},'${safe}')">
                <div class="profile-avatar${act ? ' active' : ''}" style="font-size:10px">${ini}</div>
                <span class="flex-1">${name}${esAdmin ? '<span class="ml-1 text-[10px] font-bold text-[var(--neon)]">⚡</span>' : ''}</span>
                ${act ? '<i class="fas fa-check pd-check"></i>' : ''}
            </div>`;
        }).join('');
    }

    window.handlePdItemKey = (e, id, nombre) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectProfile(id, nombre); closeProfileDropdown(); }
        if (e.key === 'ArrowDown') { e.preventDefault(); e.target.nextElementSibling?.focus(); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); (e.target.previousElementSibling?.matches('.pd-item') ? e.target.previousElementSibling : document.getElementById('pdSearch'))?.focus(); }
        if (e.key === 'Escape')    { closeProfileDropdown(); document.getElementById('profileBtn').focus(); }
    };

    /* ── Seleccionar perfil ── */
    window.selectProfile = (id, nombre) => {
        const perfilData   = allPerfiles.find(p => p.id === id);
        const esAdmin      = perfilData?.bitAdministrador === 1;
        perfilActivoEsAdmin = esAdmin;
        perfilActivo        = id;
        perfilActivoNombre  = nombre;
        pendiente           = {};

        const ini = nombre.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
        document.getElementById('profileAvatarBtn').innerHTML = esAdmin ? '<i class="fas fa-bolt text-[10px]"></i>' : ini;
        document.getElementById('profileAvatarBtn').classList.add('active');
        document.getElementById('profileBtnLabel').textContent = nombre;
        document.getElementById('profileBtn').classList.add('selected');

        const tag = document.getElementById('activeProfileTag');
        tag.classList.remove('hidden');
        document.getElementById('activeProfileName').textContent = nombre + (esAdmin ? ' ⚡' : '');

        updateUnsavedBar();
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('matrixBody').classList.add('hidden');
        document.getElementById('skeletonBody').classList.remove('hidden');
        document.getElementById('matrixWrap').classList.remove('hidden');

        if (esAdmin) {
            permisos = {};
            allModulos.forEach(m => { permisos[m.id] = {ver:true, agr:true, edi:true, eli:true, det:true}; });
            original = JSON.parse(JSON.stringify(permisos));
            renderMatrix();
            bloquearInteraccionAdmin(true);
            if (window.showToast) window.showToast('⚡ Perfil Super Administrador: acceso total protegido', 'info');
        } else {
            bloquearInteraccionAdmin(false);
            fetch(`/api/permisos?perfil=${id}`)
                .then(r => r.json())
                .then(data => {
                    permisos = {};
                    allModulos.forEach(m => { permisos[m.id] = {ver:false, agr:false, edi:false, eli:false, det:false}; });
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
                .catch(() => {
                    if (window.showToast) window.showToast('Error cargando permisos del perfil', 'error');
                });
        }
    };

    /* ── Render matrix ── */
    function renderMatrix() {
        const tbody = document.getElementById('matrixBody');

        tbody.innerHTML = allModulos.map(mod => {
            const p        = permisos[mod.id];
            const iconKey  = Object.keys(ICONS).find(k => mod.strNombreModulo.toLowerCase().includes(k)) || 'default';
            const iconFinal = perfilActivoEsAdmin ? 'fa-bolt text-[var(--neon)]' : ICONS[iconKey];

            const checks = COLS.map((col, idx) => `
                <td${idx === 4 ? ' class="col-det"' : ''}>
                    ${perfilActivoEsAdmin ? `
                        <div class="perm-check on-${col.key}" style="opacity:0.8;cursor:default;">
                            <i class="fas fa-check ck" aria-hidden="true"></i>
                        </div>
                    ` : `
                        <button type="button" class="perm-check${p[col.key] ? ' on-' + col.key : ''}"
                            data-mod="${mod.id}" data-col="${col.key}"
                            aria-pressed="${p[col.key]}"
                            aria-label="${col.label} — ${mod.strNombreModulo}"
                            onclick="toggleCell(this)"
                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleCell(this);}">
                            <i class="fas fa-check ck" aria-hidden="true"></i>
                        </button>
                    `}
                </td>
            `).join('');

            const accionCelda = perfilActivoEsAdmin
                ? `<span class="protected-badge"><i class="fas fa-shield-halved"></i> Protegido</span>`
                : `<button type="button" class="btn-row-save" id="saveBtn-${mod.id}"
                       onclick="saveRow(${mod.id})"
                       aria-label="Guardar permisos de ${mod.strNombreModulo}">
                       <i class="fas fa-check text-[10px]"></i> Guardar
                   </button>`;

            return `<tr id="row-${mod.id}">
                <td>
                    <div class="module-cell">
                        <div class="module-icon-sm flex-shrink-0"><i class="fas ${iconFinal}"></i></div>
                        <div style="min-width:0">
                            <div class="module-name">${mod.strNombreModulo}</div>
                            ${mod.strDescripcion ? `<div class="module-desc">${mod.strDescripcion}</div>` : ''}
                        </div>
                    </div>
                </td>
                ${checks}
                <td>${accionCelda}</td>
            </tr>`;
        }).join('');

        document.getElementById('skeletonBody').classList.add('hidden');
        tbody.classList.remove('hidden');

        if (!perfilActivoEsAdmin) {
            updateColumnHeaders();
        } else {
            COLS.forEach(col => {
                const mini = document.getElementById(`colMini${col.key.charAt(0).toUpperCase() + col.key.slice(1)}`);
                if (mini) mini.classList.add('all-on');
            });
        }
    }

    /* ── Dirty state ── */
    function checkIsDirty(modId) {
        if (perfilActivoEsAdmin) return;
        const isDirty = COLS.some(col => permisos[modId]?.[col.key] !== original[modId]?.[col.key]);
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
        btn.setAttribute('aria-pressed', String(permisos[modId][col]));
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
                if (btn) { btn.classList.add(`on-${col.key}`); btn.setAttribute('aria-pressed', 'true'); }
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
                if (btn) { btn.classList.remove(`on-${col.key}`); btn.setAttribute('aria-pressed', 'false'); }
            });
            checkIsDirty(m.id);
        });
        updateColumnHeaders();
    };

    window.discardAll = () => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        const count = Object.keys(pendiente).length;
        if (count === 0) return;
        if (!confirm(`¿Descartar ${count} módulo${count !== 1 ? 's' : ''} sin guardar?`)) return;
        permisos  = JSON.parse(JSON.stringify(original));
        pendiente = {};
        renderMatrix();
        updateUnsavedBar();
        if (window.showToast) window.showToast('Cambios descartados', 'info');
    };

    function updateColumnHeaders() {
        if (!perfilActivo || perfilActivoEsAdmin || !allModulos.length) return;
        COLS.forEach(col => {
            const allOn = allModulos.every(m => permisos[m.id]?.[col.key]);
            const mini  = document.getElementById(`colMini${col.key.charAt(0).toUpperCase() + col.key.slice(1)}`);
            mini?.classList.toggle('all-on', allOn);
        });
    }

    function updateUnsavedBar() {
        const count = Object.keys(pendiente).length;
        document.getElementById('unsavedLabel').textContent = `${count} módulo${count !== 1 ? 's' : ''} sin guardar`;
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
    window.saveRow = (modId, silent = false) => {
        if (!perfilActivo || perfilActivoEsAdmin || !pendiente[modId] || !puedeEditar) return Promise.resolve(false);
        const p  = permisos[modId];
        const sb = document.getElementById(`saveBtn-${modId}`);
        if (sb) { sb.classList.add('busy'); sb.innerHTML = '<i class="fas fa-spinner fa-spin text-[10px]"></i> Guardando'; }

        return fetch('/api/permisos', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ idPerfil: perfilActivo, modulos: [{
                idModulo: parseInt(modId), bitConsulta: p.ver, bitAgregar: p.agr,
                bitEditar: p.edi, bitEliminar: p.eli, bitDetalle: p.det,
            }]}),
        })
        .then(r => r.json())
        .then(res => {
            if (sb) { sb.classList.remove('busy'); sb.innerHTML = '<i class="fas fa-check text-[10px]"></i> Guardar'; }
            if (res.success) {
                original[modId] = { ...permisos[modId] };
                checkIsDirty(modId);
                if (!silent && window.showToast) window.showToast('Permisos guardados', 'success');
                return true;
            }
            if (!silent && window.showToast) window.showToast(res.message || 'Error al guardar', 'error');
            return false;
        })
        .catch(() => {
            if (sb) { sb.classList.remove('busy'); sb.innerHTML = '<i class="fas fa-check text-[10px]"></i> Guardar'; }
            if (!silent && window.showToast) window.showToast('Error de conexión', 'error');
            return false;
        });
    };

    window.saveAllPending = () => {
        const ids = Object.keys(pendiente);
        if (!ids.length || perfilActivoEsAdmin || !puedeEditar) return;
        const btn = document.getElementById('btnSaveAll');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';

        fetch('/api/permisos', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ idPerfil: perfilActivo, modulos: ids.map(id => ({
                idModulo: parseInt(id), bitConsulta: permisos[id].ver, bitAgregar: permisos[id].agr,
                bitEditar: permisos[id].edi, bitEliminar: permisos[id].eli, bitDetalle: permisos[id].det,
            }))}),
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = 'Guardar Todo';
            if (res.success) {
                ids.forEach(id => { original[id] = { ...permisos[id] }; checkIsDirty(id); });
                if (window.showToast) window.showToast(`${ids.length} módulo${ids.length !== 1 ? 's' : ''} actualizados`, 'success');
            } else {
                if (window.showToast) window.showToast(res.message || 'Error al guardar permisos', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = 'Guardar Todo';
            if (window.showToast) window.showToast('Error de conexión', 'error');
        });
    };

    /* ── Copy modal ── */
    window.openCopyModal = () => {
        if (!perfilActivo) {
            if (window.showToast) window.showToast('Selecciona un perfil primero', 'info');
            return;
        }
        if (perfilActivoEsAdmin) {
            if (window.showToast) window.showToast('El perfil de Super Administrador está protegido', 'info');
            return;
        }
        if (!puedeEditar) {
            if (window.showToast) window.showToast('No tienes permisos de edición', 'info');
            return;
        }

        const others = allPerfiles.filter(p => p.id !== perfilActivo && p.bitAdministrador !== 1);
        document.getElementById('copyModalList').innerHTML = !others.length
            ? `<p class="text-xs text-[var(--text-3)] text-center py-4">No existen otros perfiles disponibles.</p>`
            : others.map(p => {
                const ini  = p.strNombrePerfil.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
                const safe = p.strNombrePerfil.replace(/'/g, "\\'");
                return `
                <button type="button"
                    onclick="copyFromProfile(${p.id},'${safe}');closeCopyModal();"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[var(--surface-3)] transition-colors text-left group border border-transparent hover:border-[var(--surface-4)]">
                    <div class="profile-avatar" style="font-size:10px">${ini}</div>
                    <span class="text-sm font-bold text-[var(--text-2)] group-hover:text-[var(--text-1)] transition-colors flex-1">${p.strNombrePerfil}</span>
                    <i class="fas fa-download text-sm text-[var(--text-3)] opacity-0 group-hover:opacity-100 group-hover:text-blue-400 transition-all"></i>
                </button>`;
            }).join('');

        document.getElementById('copyModal').classList.add('open');
    };

    window.closeCopyModal = () => {
        document.getElementById('copyModal').classList.remove('open');
    };

    // Cerrar copy modal con backdrop o Escape
    document.getElementById('copyModal').addEventListener('click', e => {
        if (e.target === document.getElementById('copyModal')) closeCopyModal();
    });

    window.copyFromProfile = (fromId, fromNombre) => {
        if (!perfilActivo || perfilActivoEsAdmin || !puedeEditar) return;
        fetch(`/api/permisos?perfil=${fromId}`)
            .then(r => r.json())
            .then(data => {
                allModulos.forEach(m => { permisos[m.id] = {ver:false, agr:false, edi:false, eli:false, det:false}; });
                const items = Array.isArray(data) ? data : (data.data ?? []);
                items.forEach(item => {
                    if (permisos[item.idModulo] !== undefined)
                        permisos[item.idModulo] = { ver:!!item.bitConsulta, agr:!!item.bitAgregar, edi:!!item.bitEditar, eli:!!item.bitEliminar, det:!!item.bitDetalle };
                });
                renderMatrix();
                allModulos.forEach(m => checkIsDirty(m.id));
                updateColumnHeaders();
                if (window.showToast) window.showToast(`Permisos copiados de "${fromNombre}"`, 'info');
            })
            .catch(() => {
                if (window.showToast) window.showToast('Error al copiar permisos', 'error');
            });
    };

    /* ── Cerrar profile dropdown al click externo ── */
    document.addEventListener('click', e => {
        if (!document.getElementById('profileSelectorWrap')?.contains(e.target))
            closeProfileDropdown();
    });

    /* ── Escape global ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (document.getElementById('copyModal').classList.contains('open')) closeCopyModal();
            else if (document.getElementById('profileDropdown').classList.contains('open')) {
                closeProfileDropdown();
                document.getElementById('profileBtn').focus();
            }
        }
    });

    /* ── Polling silencioso ── */
    setInterval(async () => {
        if (!perfilActivo || perfilActivoEsAdmin || Object.keys(pendiente).length > 0) return;
        if (document.visibilityState === 'hidden') return;

        try {
            const res  = await fetch(`/api/permisos?perfil=${perfilActivo}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data  = await res.json();
            const items = Array.isArray(data) ? data : (data.data ?? []);

            let serverPermisos = {};
            allModulos.forEach(m => { serverPermisos[m.id] = {ver:false, agr:false, edi:false, eli:false, det:false}; });
            items.forEach(item => {
                if (serverPermisos[item.idModulo] !== undefined)
                    serverPermisos[item.idModulo] = { ver:!!item.bitConsulta, agr:!!item.bitAgregar, edi:!!item.bitEditar, eli:!!item.bitEliminar, det:!!item.bitDetalle };
            });

            if (JSON.stringify(original) !== JSON.stringify(serverPermisos)) {
                permisos  = JSON.parse(JSON.stringify(serverPermisos));
                original  = JSON.parse(JSON.stringify(serverPermisos));
                renderMatrix();
            }
        } catch (_) {
            // falla silenciosa
        }
    }, 5000);

}); // DOMContentLoaded
</script>
@endsection
