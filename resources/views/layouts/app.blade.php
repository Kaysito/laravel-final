<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin') — Proyecto</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        UPLOADCARE_PUBLIC_KEY = 'b3a3c1bece70d9761e6b';
        UPLOADCARE_LOCALE = 'es';
    </script>
    <script src="https://ucarecdn.com/libs/widget/3.x/uploadcare.full.min.js" charset="utf-8"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Geist:wght@300;400;500;600;700&display=swap');

        /* ☀️ DEFAULT: TEMA CLARO */
        :root {
            --surface-1: #ffffff;
            --surface-2: #fdf8f8;
            --surface-3: #f5ebeb;
            --surface-4: #e8dada;
            --surface-5: #d4c1c1;
            
            --text-1: #2d1b1b;
            --text-2: #5c4343;
            --text-3: #8a6d6d;
            
            --neon: #e63757;
            --neon-dark: #b82943;
            --neon-border: rgba(230,55,87,0.3);
            --neon-muted: rgba(230,55,87,0.08);
        }

        /* 🌙 TEMA OSCURO */
        [data-theme="dark"] {
            --surface-1: #0a0a0f;
            --surface-2: #111118;
            --surface-3: #18181f;
            --surface-4: #22222c;
            --surface-5: #2a2a36;
            
            --text-1: #f0f0f5;
            --text-2: #a0a0b0;
            --text-3: #60607a;
            
            --neon-border: rgba(230,55,87,0.4);
        }

        * { font-family: 'Geist', system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        body { 
            background: var(--surface-1); 
            color: var(--text-1); 
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ── Scrollbar Mejorado ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--surface-4); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--surface-5); }

        /* ── Sidebar & Menús Desplegables ── */
        .sidebar { background: var(--surface-2); border-right: 1px solid var(--surface-4); transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-item { color: var(--text-2); transition: all 0.15s ease; border-radius: 8px; }
        .nav-item:hover { background: var(--surface-3); color: var(--text-1); }
        .nav-item.active { background: var(--neon-muted); color: var(--neon); border: 1px solid var(--neon-border); }
        .nav-item.active .nav-icon { color: var(--neon); }

        /* Botón del Submenú */
        .submenu-btn { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.625rem 0.75rem; color: var(--text-2); font-size: 0.875rem; border-radius: 8px; transition: all 0.15s; cursor: pointer; }
        .submenu-btn:hover { background: var(--surface-3); color: var(--text-1); }
        .submenu-icon { transition: transform 0.3s ease; }
        
        /* Contenedor del Submenú (Efecto Slider) */
        .submenu-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-in-out; }
        .submenu-content.open { max-height: 500px; }
        .submenu-btn.open .submenu-icon { transform: rotate(180deg); }

        /* ── Card & Inputs ── */
        .card { background: var(--surface-2); border: 1px solid var(--surface-4); border-radius: 14px; transition: background-color 0.3s ease, border-color 0.3s ease;}
        .input-field { background: var(--surface-3); border: 1px solid var(--surface-4); color: var(--text-1); border-radius: 8px; transition: all 0.2s ease; outline: none; }
        .input-field:focus { border-color: var(--neon-border); box-shadow: 0 0 0 3px rgba(230,55,87,0.1); }
        .input-field::placeholder { color: var(--text-3); }

        /* ── Action buttons & Tooltips ── */
        .action-btn { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.15s ease; color: var(--text-3); background: transparent; border: none; cursor: pointer; }
        .action-btn:hover { color: var(--text-1); background: var(--surface-4); }
        
        .tooltip { position: relative; }
        .tooltip::after { content: attr(data-tip); position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%); background: var(--surface-5); color: var(--text-1); font-size: 11px; padding: 4px 8px; border-radius: 5px; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.15s ease; z-index: 99; }
        .tooltip:hover::after { opacity: 1; }

        .section-divider { height: 1px; background: linear-gradient(to right, transparent, var(--surface-4), transparent); }

        /* ── Toast Responsivo ── */
        .toast { position: fixed; bottom: 24px; right: 24px; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 10px; background: var(--surface-3); border: 1px solid var(--surface-4); box-shadow: 0 8px 32px rgba(0,0,0,0.4); z-index: 1000; transform: translateY(80px); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); pointer-events: none; max-width: calc(100vw - 3rem); }
        .toast.show { transform: translateY(0); opacity: 1; pointer-events: auto; }
        .toast.success { border-color: rgba(74,222,128,0.3); }
        .toast.error   { border-color: rgba(230,55,87,0.3);  }
        .toast.info    { border-color: rgba(96,165,250,0.3);  }

        @yield('styles')
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    {{-- ─── BACKDROP MÓVIL ─── --}}
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-20 hidden opacity-0 transition-opacity duration-300 md:hidden"></div>

    {{-- ─── SIDEBAR ──────────────────────────────────────────────── --}}
    <aside id="mainSidebar" class="sidebar fixed md:relative w-64 md:w-60 flex-shrink-0 flex flex-col h-full z-30 transform -translate-x-full md:translate-x-0 shadow-2xl md:shadow-none">

        {{-- Logo --}}
        <div class="px-5 py-5 flex items-center justify-between border-b border-[var(--surface-4)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[var(--neon-dark)] flex items-center justify-center">
                    <i class="fas fa-bolt text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-[var(--text-1)] font-semibold text-sm leading-none">Proyecto</p>
                    <p class="text-[var(--text-3)] text-[10px] font-mono mt-0.5">v2.4.1</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden w-8 h-8 flex items-center justify-center text-[var(--text-3)] hover:text-[var(--neon)] transition-colors rounded-lg bg-[var(--surface-3)]">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto" id="sidebarNav">
            
            {{-- DASHBOARD (Fijo) --}}
            <a href="{{ route('home') }}"
               class="nav-item {{ request()->routeIs('home') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-gauge-high w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            <div class="section-divider my-2"></div>
            
            {{-- MAGIA DINÁMICA: Dibujamos los módulos desde la Base de Datos --}}
            @if(isset($modulosMenu))
                @foreach($modulosMenu as $grupo => $modulos)
                    
                    @if(empty($grupo))
                        {{-- Módulos Sueltos (Sin Carpeta) --}}
                        @foreach($modulos as $mod)
                            <a href="{{ $mod->strRuta && Route::has($mod->strRuta) ? route($mod->strRuta) : '#' }}" data-modulo="{{ $mod->strNombreModulo }}"
                               class="nav-item {{ $mod->strRuta && request()->routeIs($mod->strRuta) ? 'active' : '' }} flex items-center gap-3 px-3 py-2 text-sm">
                                <i class="nav-icon {{ $mod->strIcono ?? 'fas fa-cube' }} w-4 text-center"></i>
                                <span>{{ $mod->strNombreModulo }}</span>
                            </a>
                        @endforeach
                    @else
                        {{-- Módulos Agrupados (Carpeta / Slider) --}}
                        @php $folderId = 'submenu-' . Str::slug($grupo); @endphp
                        <div class="menu-group">
                            <button onclick="toggleSubmenu('{{ $folderId }}', this)" class="submenu-btn">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-folder w-4 text-center text-[var(--text-3)]"></i>
                                    <span class="font-medium">{{ $grupo }}</span>
                                </div>
                                <i class="fas fa-chevron-down submenu-icon text-xs"></i>
                            </button>
                            <div id="{{ $folderId }}" class="submenu-content pl-9 mt-1 space-y-1">
                                @foreach($modulos as $mod)
                                    <a href="{{ $mod->strRuta && Route::has($mod->strRuta) ? route($mod->strRuta) : '#' }}" data-modulo="{{ $mod->strNombreModulo }}"
                                       class="nav-item {{ $mod->strRuta && request()->routeIs($mod->strRuta) ? 'active' : '' }} flex items-center gap-3 px-3 py-2 text-sm">
                                        <i class="nav-icon {{ $mod->strIcono ?? 'fas fa-cube' }} w-4 text-center text-[10px]"></i>
                                        <span>{{ $mod->strNombreModulo }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                @endforeach
            @endif

        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-[var(--surface-4)]">
            <a href="{{ route('logout') }}" onclick="localStorage.removeItem('user_data');"
               class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-red-500/10 transition-colors cursor-pointer group">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-red-500 to-[var(--neon)] flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                    <i class="fas fa-power-off"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-[var(--text-1)] truncate group-hover:text-red-400">Cerrar Sesión</p>
                </div>
            </a>
        </div>
    </aside>

    {{-- ─── MAIN ──────────────────────────────────────────────────── --}}
    <main class="flex-1 flex flex-col h-full min-w-0 bg-[var(--surface-1)] relative z-10 transition-colors duration-300">

        {{-- Top bar / breadcrumb --}}
        <header class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-[var(--surface-4)] bg-[var(--surface-1)] flex-shrink-0 z-20 transition-colors duration-300">
            
            <div class="flex items-center gap-2 sm:gap-3 text-sm min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-md text-[var(--text-2)] bg-[var(--surface-3)] hover:text-[var(--neon)] transition-colors">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <div class="flex items-center gap-2 truncate whitespace-nowrap">
                    @yield('breadcrumb')
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0 pl-2">
                <button onclick="toggleTheme()" class="w-8 h-8 flex items-center justify-center rounded-full bg-[var(--surface-2)] border border-[var(--surface-4)] text-[var(--text-3)] hover:text-[var(--neon)] transition-all hover:scale-110 tooltip" data-tip="Cambiar Tema">
                    <i id="themeIcon" class="fas fa-moon text-sm"></i>
                </button>

                <div class="w-px h-5 bg-[var(--surface-4)] mx-1 hidden sm:block"></div>
                
                <a href="{{ route('miperfil') ?? '#' }}" class="flex items-center gap-2 cursor-pointer tooltip transition-transform hover:scale-105" data-tip="Mi Perfil">
                    @php $currentUser = auth()->user(); @endphp
                    @if($currentUser && $currentUser->strImagen)
                        <img src="{{ $currentUser->strImagen }}-/scale_crop/60x60/center/" class="w-8 h-8 rounded-full object-cover shadow-md border border-[var(--surface-4)]">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-[10px] font-bold text-white shadow-md border border-[var(--surface-4)]">
                            {{ $currentUser ? strtoupper(substr($currentUser->strNombreUsuario ?? 'A', 0, 2)) : 'AD' }}
                        </div>
                    @endif
                </a>
            </div>
        </header>

        {{-- ÁREA DE CONTENIDO --}}
        <div class="flex-1 overflow-y-auto min-h-0 relative">
            <div class="absolute inset-0">
                @yield('content')
            </div>
        </div>

    </main>

    {{-- Toast global --}}
    <div id="toast" class="toast">
        <div id="toastIcon" class="flex-shrink-0"></div>
        <div class="min-w-0 flex-1">
            <p id="toastMsg" class="text-sm font-medium text-[var(--text-1)] truncate"></p>
            <p id="toastSub" class="text-xs text-[var(--text-3)] mt-0.5 truncate"></p>
        </div>
        <button onclick="hideToast()" class="ml-2 text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors flex-shrink-0">
            <i class="fas fa-xmark text-xs"></i>
        </button>
    </div>

    @yield('scripts')

    {{-- Scripts Globales --}}
    <script>
        // 0. CONTROL DEL MENÚ MÓVIL Y ACORDEÓN
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            sidebar.classList.toggle('-translate-x-full');
            
            if (backdrop.classList.contains('hidden')) {
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }
        }

        // Lógica del Acordeón (Sliders)
        function toggleSubmenu(id, btnElement) {
            const content = document.getElementById(id);
            content.classList.toggle('open');
            btnElement.classList.toggle('open');
        }

        // Abrir automáticamente el submenú que contenga un enlace activo
        function autoOpenActiveMenu() {
            const activeLink = document.querySelector('.submenu-content .nav-item.active');
            if (activeLink) {
                const parentContent = activeLink.closest('.submenu-content');
                const parentBtn = parentContent.previousElementSibling;
                parentContent.classList.add('open');
                parentBtn.classList.add('open');
            }
        }

        // 1. CEREBRO DE PERMISOS (RBAC)
        window.tienePermiso = function(nombreModulo, accionCrud) {
            try {
                const userData = JSON.parse(localStorage.getItem('user_data'));
                if (!userData) return false; 
                if (userData.perfil === 1) return true; // Super Admin bypass

                if (userData.permisos && userData.permisos[nombreModulo]) {
                    return userData.permisos[nombreModulo][accionCrud] == 1;
                }
                return false;
            } catch (e) { return false; }
        };

        // 2. APLICADOR VISUAL DE PERMISOS
        window.aplicarPermisosVisuales = function() {
            // Oculta/Muestra enlaces individuales
            document.querySelectorAll('#sidebarNav [data-modulo]').forEach(enlace => {
                const modulo = enlace.getAttribute('data-modulo');
                if (!window.tienePermiso(modulo, 'bitConsulta')) {
                    enlace.style.display = 'none';
                } else {
                    enlace.style.display = 'flex'; 
                }
            });

            // Ocultar el grupo entero si no tiene hijos visibles
            document.querySelectorAll('.menu-group').forEach(group => {
                const submenuContent = group.querySelector('.submenu-content');
                if(submenuContent) {
                    const links = Array.from(submenuContent.querySelectorAll('[data-modulo]'));
                    const hasVisibleLinks = links.some(link => link.style.display !== 'none');
                    
                    if(!hasVisibleLinks) {
                        group.style.display = 'none';
                    } else {
                        group.style.display = 'block';
                    }
                }
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            window.aplicarPermisosVisuales();
            autoOpenActiveMenu(); 
        });

        // 3. 🚀 MOTOR GLOBAL DE PERMISOS EN TIEMPO REAL 🚀
        setInterval(async () => {
            try {
                const userDataStr = localStorage.getItem('user_data');
                if (!userDataStr) return;
                
                let userData = JSON.parse(userDataStr);
                if (userData.perfil === 1) return;

                const resCat = await fetch('/api/permisos/catalogos', { headers: { 'Accept': 'application/json' }});
                if (!resCat.ok) return;
                const dataCat = await resCat.json();
                const modulos = dataCat.modulos || [];

                const resPerm = await fetch(`/api/permisos?perfil=${userData.perfil}`, { headers: { 'Accept': 'application/json' }});
                if (!resPerm.ok) return;
                const dataPerm = await resPerm.json();
                const items = Array.isArray(dataPerm) ? dataPerm : (dataPerm.data ?? []);

                let nuevosPermisos = {};
                items.forEach(item => {
                    const mod = modulos.find(m => m.id === item.idModulo);
                    if (mod) {
                        nuevosPermisos[mod.strNombreModulo] = {
                            bitConsulta: item.bitConsulta,
                            bitAgregar: item.bitAgregar,
                            bitEditar: item.bitEditar,
                            bitEliminar: item.bitEliminar,
                            bitDetalle: item.bitDetalle
                        };
                    }
                });

                if (JSON.stringify(userData.permisos) !== JSON.stringify(nuevosPermisos)) {
                    userData.permisos = nuevosPermisos;
                    localStorage.setItem('user_data', JSON.stringify(userData));
                    
                    window.aplicarPermisosVisuales();
                    
                    const activeMenu = document.querySelector('#sidebarNav .nav-item.active');
                    if (activeMenu) {
                        const currentModulo = activeMenu.getAttribute('data-modulo');
                        if (currentModulo && !window.tienePermiso(currentModulo, 'bitConsulta')) {
                            if(window.showToast) window.showToast('Privilegios actualizados. Redirigiendo...', 'info');
                            setTimeout(() => window.location.href = "{{ route('home') }}", 1500);
                        }
                    }
                }
            } catch (e) {
                // Fallo silencioso si hay problemas de red
            }
        }, 5000);

        // 4. LÓGICA DEL TOAST
        let _toastTimer;
        window.showToast = (msg, tipo = 'success', sub = '') => {
            const t   = document.getElementById('toast');
            const map = {
                success: '<i class="fas fa-circle-check text-green-400 text-lg"></i>',
                error:   '<i class="fas fa-circle-xmark text-red-400 text-lg"></i>',
                info:    '<i class="fas fa-circle-info text-blue-400 text-lg"></i>',
            };
            t.className = `toast ${tipo}`;
            document.getElementById('toastIcon').innerHTML = map[tipo] ?? map.info;
            document.getElementById('toastMsg').textContent  = msg;
            document.getElementById('toastSub').textContent  = sub;
            clearTimeout(_toastTimer);
            t.classList.add('show');
            _toastTimer = setTimeout(() => t.classList.remove('show'), 4500);
        };
        window.hideToast = () => document.getElementById('toast').classList.remove('show');

        // 5. TEMA CLARO/OSCURO
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light'; 
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateIcon(savedTheme);
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const nextTheme = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', nextTheme);
            localStorage.setItem('theme', nextTheme);
            updateIcon(nextTheme);
        }

        function updateIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (theme === 'dark') {
                icon.className = 'fas fa-sun text-yellow-400 text-sm';
            } else {
                icon.className = 'fas fa-moon text-[var(--neon)] text-sm';
            }
        }

        initTheme();
    </script>
</body>
</html>