<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface-2); }
        ::-webkit-scrollbar-thumb { background: var(--surface-5); border-radius: 3px; }

        /* ── Sidebar ── */
        .sidebar { background: var(--surface-2); border-right: 1px solid var(--surface-4); transition: background-color 0.3s ease, border-color 0.3s ease; }
        .nav-item { color: var(--text-2); transition: all 0.15s ease; border-radius: 8px; }
        .nav-item:hover { background: var(--surface-3); color: var(--text-1); }
        .nav-item.active { background: var(--neon-muted); color: var(--neon); border: 1px solid var(--neon-border); }
        .nav-item.active .nav-icon { color: var(--neon); }

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

        /* ── Toast ── */
        .toast { position: fixed; bottom: 24px; right: 24px; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 10px; background: var(--surface-3); border: 1px solid var(--surface-4); box-shadow: 0 8px 32px rgba(0,0,0,0.4); z-index: 1000; transform: translateY(80px); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); pointer-events: none; }
        .toast.show { transform: translateY(0); opacity: 1; pointer-events: auto; }
        .toast.success { border-color: rgba(74,222,128,0.3); }
        .toast.error   { border-color: rgba(230,55,87,0.3);  }
        .toast.info    { border-color: rgba(96,165,250,0.3);  }

        @yield('styles')
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    {{-- ─── SIDEBAR ──────────────────────────────────────────────── --}}
    <aside class="sidebar w-60 flex-shrink-0 flex flex-col h-full z-20">

        {{-- Logo --}}
        <div class="px-5 py-5 flex items-center gap-3 border-b border-[var(--surface-4)]">
            <div class="w-8 h-8 rounded-lg bg-[var(--neon-dark)] flex items-center justify-center">
                <i class="fas fa-bolt text-white text-xs"></i>
            </div>
            <div>
                <p class="text-[var(--text-1)] font-semibold text-sm leading-none">Proyecto</p>
                <p class="text-[var(--text-3)] text-[10px] font-mono mt-0.5">v2.4.1</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" id="sidebarNav">
            <p class="px-2 mb-2 text-[9px] font-mono tracking-widest text-[var(--text-3)] uppercase">Principal</p>

            <a href="{{ route('home') }}"
               class="nav-item {{ request()->routeIs('home') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-gauge-high w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            <div class="section-divider my-3"></div>
            
            {{-- 👇 SECCIÓN DE SEGURIDAD --}}
            <p class="px-2 mb-2 text-[9px] font-mono tracking-widest text-[var(--text-3)] uppercase">Seguridad</p>

            <a href="{{ route('perfil.index') }}" data-modulo="Perfil"
               class="nav-item {{ request()->routeIs('perfil.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-id-badge w-4 text-center"></i>
                <span>Perfiles</span>
            </a>
            
            <a href="{{ route('modulo.index') }}" data-modulo="Modulo"
               class="nav-item {{ request()->routeIs('modulo.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-cubes w-4 text-center"></i>
                <span>Módulos</span>
            </a>
            
            <a href="{{ route('permisos.index') }}" data-modulo="Permisos-Perfil"
               class="nav-item {{ request()->routeIs('permisos.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-key w-4 text-center"></i>
                <span>Permisos-Perfil</span>
            </a>
            
            <a href="{{ route('usuarios.index') }}" data-modulo="Usuarios"
               class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-users w-4 text-center"></i>
                <span>Usuarios</span>
            </a>

            <div class="section-divider my-3"></div>
            
            {{-- 👇 VISTAS ESTÁTICAS --}}
            <p class="px-2 mb-2 text-[9px] font-mono tracking-widest text-[var(--text-3)] uppercase">Módulos</p>

            <a href="{{ route('p1.1.index') }}" data-modulo="Principal1.1" 
               class="nav-item {{ request()->routeIs('p1.1.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-box w-4 text-center"></i>
                <span>Principal 1.1</span>
            </a>
            
            <a href="{{ route('p1.2.index') }}" data-modulo="Principal1.2" 
               class="nav-item {{ request()->routeIs('p1.2.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-layer-group w-4 text-center"></i>
                <span>Principal 1.2</span>
            </a>
            
            <a href="{{ route('p2.1.index') }}" data-modulo="Principal2.1" 
               class="nav-item {{ request()->routeIs('p2.1.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-box-open w-4 text-center"></i>
                <span>Principal 2.1</span>
            </a>
            
            <a href="{{ route('p2.2.index') }}" data-modulo="Principal2.2" 
               class="nav-item {{ request()->routeIs('p2.2.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm">
                <i class="nav-icon fas fa-cubes-stacked w-4 text-center"></i>
                <span>Principal 2.2</span>
            </a>

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
                    <p class="text-[10px] text-[var(--text-3)] truncate">Terminar acceso</p>
                </div>
            </a>
        </div>
    </aside>

    {{-- ─── MAIN ──────────────────────────────────────────────────── --}}
    <main class="flex-1 flex flex-col h-full min-w-0 bg-[var(--surface-1)] relative z-10 transition-colors duration-300">

        {{-- Top bar / breadcrumb --}}
        <header class="flex items-center justify-between px-6 py-4 border-b border-[var(--surface-4)] bg-[var(--surface-1)] flex-shrink-0 z-20 transition-colors duration-300">
            <div class="flex items-center gap-2 text-sm">
                @yield('breadcrumb')
            </div>
            <div class="flex items-center gap-3">
                <div class="w-px h-5 bg-[var(--surface-4)] mx-2"></div>
                
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

    {{-- 🌗 BOTÓN FLOTANTE --}}
    <button onclick="toggleTheme()" class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] shadow-lg flex items-center justify-center text-[var(--neon)] hover:scale-110 transition-transform z-50 tooltip" data-tip="Cambiar Tema">
        <i id="themeIcon" class="fas fa-moon text-lg"></i>
    </button>

    {{-- Toast global --}}
    <div id="toast" class="toast">
        <div id="toastIcon" class="flex-shrink-0"></div>
        <div>
            <p id="toastMsg" class="text-sm font-medium text-[var(--text-1)]"></p>
            <p id="toastSub" class="text-xs text-[var(--text-3)] mt-0.5"></p>
        </div>
        <button onclick="hideToast()" class="ml-auto text-[var(--text-3)] hover:text-[var(--text-1)] transition-colors flex-shrink-0">
            <i class="fas fa-xmark text-xs"></i>
        </button>
    </div>

    @yield('scripts')

    {{-- Scripts Globales --}}
    <script>
        // 1. CEREBRO DE PERMISOS (RBAC)
        window.tienePermiso = function(nombreModulo, accionCrud) {
            try {
                const userData = JSON.parse(localStorage.getItem('user_data'));
                
                if (!userData) return false; 
                if (userData.perfil === 1) return true; // Super Admin siempre tiene acceso

                if (userData.permisos && userData.permisos[nombreModulo]) {
                    return userData.permisos[nombreModulo][accionCrud] == 1;
                }
                
                return false;
            } catch (e) {
                console.error("Error leyendo permisos", e);
                return false; 
            }
        };

        // 2. APLICADOR VISUAL DE PERMISOS (Muestra/Oculta menús en el DOM)
        window.aplicarPermisosVisuales = function() {
            document.querySelectorAll('#sidebarNav [data-modulo]').forEach(enlace => {
                const modulo = enlace.getAttribute('data-modulo');
                
                // Si ya no tiene permiso de Consulta, ocultamos el botón del menú
                if (!window.tienePermiso(modulo, 'bitConsulta')) {
                    enlace.style.display = 'none';
                } else {
                    enlace.style.display = 'flex'; // Lo restaura si le devuelven el permiso
                }
            });
        };

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            window.aplicarPermisosVisuales();
        });

        // 3. 🚀 MOTOR GLOBAL DE PERMISOS EN TIEMPO REAL 🚀
        // Se ejecuta cada 5 segundos para verificar si el superadmin nos cambió los permisos
        setInterval(async () => {
            try {
                const userDataStr = localStorage.getItem('user_data');
                if (!userDataStr) return;
                
                let userData = JSON.parse(userDataStr);
                
                // Si es el Admin Maestro, no necesita estar revisando, él es dios.
                if (userData.perfil === 1) return;

                // Paso A: Obtener la lista de módulos para cruzar los IDs con los Nombres
                const resCat = await fetch('/api/permisos/catalogos', { headers: { 'Accept': 'application/json' }});
                if (!resCat.ok) return;
                const dataCat = await resCat.json();
                const modulos = dataCat.modulos || [];

                // Paso B: Obtener los permisos frescos del usuario en la base de datos
                const resPerm = await fetch(`/api/permisos?perfil=${userData.perfil}`, { headers: { 'Accept': 'application/json' }});
                if (!resPerm.ok) return;
                const dataPerm = await resPerm.json();
                const items = Array.isArray(dataPerm) ? dataPerm : (dataPerm.data ?? []);

                // Paso C: Reconstruir el objeto de permisos en el mismo formato que usa localStorage
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

                // Paso D: Comparar si hubo un cambio en la base de datos
                if (JSON.stringify(userData.permisos) !== JSON.stringify(nuevosPermisos)) {
                    
                    // Si hubo cambio, guardamos los nuevos datos en el navegador
                    userData.permisos = nuevosPermisos;
                    localStorage.setItem('user_data', JSON.stringify(userData));
                    
                    // Actualizamos el menú lateral de inmediato
                    window.aplicarPermisosVisuales();
                    
                    // 🛡️ EL EXPULSOR DE EMERGENCIA:
                    // Verificamos si el usuario está metido en un módulo al que le acaban de quitar acceso
                    const activeMenu = document.querySelector('#sidebarNav .nav-item.active');
                    if (activeMenu) {
                        const currentModulo = activeMenu.getAttribute('data-modulo');
                        if (currentModulo && !window.tienePermiso(currentModulo, 'bitConsulta')) {
                            // Le mostramos un mensaje sutil y lo pateamos al dashboard
                            if(window.showToast) window.showToast('Privilegios actualizados por el Administrador. Redirigiendo...', 'info');
                            setTimeout(() => {
                                window.location.href = "{{ route('home') }}";
                            }, 1500);
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
                icon.className = 'fas fa-sun text-yellow-400 text-lg';
            } else {
                icon.className = 'fas fa-moon text-[var(--neon)] text-lg';
            }
        }

        initTheme();
    </script>
</body>
</html>