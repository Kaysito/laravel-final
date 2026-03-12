<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Proyecto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        neon: {
                            red:    '#FF1744',
                            rose:   '#FF4569',
                            pink:   '#FF6B8A',
                            dark:   '#CC0033',
                            deeper: '#8B0000',
                        },
                        zinc: {
                            925: '#131316',
                            950: '#0C0C0F',
                        }
                    },
                    fontFamily: {
                        sans:  ['DM Sans', 'sans-serif'],
                        mono:  ['Space Mono', 'monospace'],
                    },
                    boxShadow: {
                        'neon-sm':  '0 0 8px rgba(255,23,68,0.4)',
                        'neon-md':  '0 0 20px rgba(255,23,68,0.35), 0 0 40px rgba(255,23,68,0.15)',
                        'neon-lg':  '0 0 30px rgba(255,23,68,0.5), 0 0 60px rgba(255,23,68,0.2)',
                        'card':     '0 1px 3px rgba(0,0,0,0.4), 0 4px 16px rgba(0,0,0,0.3)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4,0,0.6,1) infinite',
                    }
                }
            }
        }
    </script>

    <style>
        /* ── Base ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --neon:        #FF1744;
            --neon-muted:  rgba(255, 23, 68, 0.18);
            --neon-border: rgba(255, 23, 68, 0.28);
            --surface-1:   #0C0C0F;
            --surface-2:   #131316;
            --surface-3:   #1A1A1F;
            --surface-4:   #22222A;
            --text-1:      #F2F2F4;
            --text-2:      #A0A0AD;
            --text-3:      #606070;
            --scrollbar-w: 4px;
        }

        html { font-size: 15px; }

        body {
            background-color: var(--surface-1);
            color: var(--text-1);
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Scrollbar ────────────────────────────────────────── */
        ::-webkit-scrollbar { width: var(--scrollbar-w); }
        ::-webkit-scrollbar-track { background: var(--surface-2); }
        ::-webkit-scrollbar-thumb { background: var(--neon-border); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--neon); }

        /* ── Sidebar ──────────────────────────────────────────── */
        #sidebar {
            background: var(--surface-2);
            border-right: 1px solid var(--neon-border);
            width: 256px;
            flex-shrink: 0;
        }

        .sidebar-logo {
            border-bottom: 1px solid var(--neon-border);
            background: linear-gradient(135deg, var(--surface-3) 0%, var(--surface-2) 100%);
        }

        .logo-mark {
            width: 34px; height: 34px;
            background: var(--neon);
            box-shadow: var(--neon-sm, 0 0 8px rgba(255,23,68,0.5));
            clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);
            display: flex; align-items: center; justify-content: center;
        }

        .nav-group-label {
            font-family: 'Space Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.18em;
            color: var(--text-3);
            text-transform: uppercase;
            padding: 20px 20px 6px;
            display: flex; align-items: center; gap: 8px;
        }
        .nav-group-label::after {
            content: '';
            flex: 1; height: 1px;
            background: var(--neon-border);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            font-size: 13.5px;
            font-weight: 400;
            color: var(--text-2);
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all 0.15s ease;
            position: relative;
        }
        .nav-link:hover {
            color: var(--text-1);
            background: var(--neon-muted);
            border-left-color: var(--neon);
        }
        .nav-link.active {
            color: var(--neon);
            background: rgba(255,23,68,0.1);
            border-left-color: var(--neon);
            font-weight: 500;
        }
        .nav-link i {
            width: 16px;
            text-align: center;
            font-size: 13px;
            opacity: 0.75;
        }
        .nav-link:hover i,
        .nav-link.active i { opacity: 1; }

        /* ── Topbar ───────────────────────────────────────────── */
        #topbar {
            background: var(--surface-2);
            border-bottom: 1px solid var(--neon-border);
            height: 60px;
        }

        .breadcrumb-sep { color: var(--text-3); margin: 0 6px; }

        /* ── Avatar ───────────────────────────────────────────── */
        .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--neon), #8B0000);
            box-shadow: 0 0 0 2px var(--neon-border);
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Neon badge ───────────────────────────────────────── */
        .badge-neon {
            font-family: 'Space Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.1em;
            padding: 2px 7px;
            border-radius: 3px;
            border: 1px solid var(--neon);
            color: var(--neon);
            background: rgba(255,23,68,0.08);
            text-transform: uppercase;
        }

        /* ── Status dot ───────────────────────────────────────── */
        .status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 6px rgba(34,197,94,0.6);
            animation: pulse 2.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.45; }
        }

        /* ── Logout ───────────────────────────────────────────── */
        .logout-btn {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 10px 20px;
            font-size: 13px; color: var(--text-3);
            border: none; background: none; cursor: pointer;
            border-top: 1px solid var(--neon-border);
            transition: color 0.15s;
            text-decoration: none;
        }
        .logout-btn:hover { color: var(--neon); }

        /* ── Content area ─────────────────────────────────────── */
        #content {
            background: var(--surface-1);
        }

        /* ── Utility: neon glow text ──────────────────────────── */
        .text-neon { color: var(--neon); }
        .text-neon-glow {
            color: var(--neon);
            text-shadow: 0 0 12px rgba(255,23,68,0.55);
        }

        /* ── Focus ring ───────────────────────────────────────── */
        :focus-visible {
            outline: 2px solid var(--neon);
            outline-offset: 2px;
        }

        /* ── Top search (header) ──────────────────────────────── */
        .search-input {
            background: var(--surface-3);
            border: 1px solid var(--surface-4);
            border-radius: 6px;
            color: var(--text-1);
            font-size: 13px;
            padding: 6px 12px 6px 32px;
            width: 200px;
            transition: border-color 0.15s, width 0.2s;
        }
        .search-input::placeholder { color: var(--text-3); }
        .search-input:focus {
            border-color: var(--neon-border);
            box-shadow: 0 0 0 3px rgba(255,23,68,0.1);
            width: 260px;
            outline: none;
        }

        /* ── Notification bell ────────────────────────────────── */
        .icon-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--surface-3);
            border: 1px solid var(--surface-4);
            color: var(--text-2);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }
        .icon-btn:hover {
            border-color: var(--neon-border);
            color: var(--neon);
            box-shadow: 0 0 10px rgba(255,23,68,0.2);
        }
        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--neon);
            box-shadow: 0 0 6px rgba(255,23,68,0.8);
        }

        /* ── Sidebar collapse animation ───────────────────────── */
        #sidebar { transition: width 0.22s ease; }

        /* ── Version chip ─────────────────────────────────────── */
        .version-chip {
            font-family: 'Space Mono', monospace;
            font-size: 9px; color: var(--text-3);
            padding: 2px 6px;
            border: 1px solid var(--surface-4);
            border-radius: 3px;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden">

    <aside id="sidebar" aria-label="Navegación principal">

        <div class="sidebar-logo h-[60px] flex items-center px-5 gap-3">
            <div class="logo-mark">
                <i class="fas fa-bolt text-white text-xs"></i>
            </div>
            <div class="flex flex-col leading-none">
                <span class="text-sm font-semibold tracking-tight text-white">Proyecto</span>
                <span class="version-chip mt-1">v2.4.1</span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-2" aria-label="Menú principal">

            <div class="nav-group-label">Seguridad</div>

            <a href="{{ route('perfil.index') }}" class="nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}" aria-label="Perfil de usuario">
                <i class="fas fa-id-badge"></i>
                <span>Perfil</span>
            </a>
            <a href="{{ route('modulo.index') }}" class="nav-link {{ request()->routeIs('modulo.*') ? 'active' : '' }}" aria-label="Módulos del sistema">
                <i class="fas fa-cubes"></i>
                <span>Módulos</span>
            </a>
            <a href="{{ route('permisos.index') }}" class="nav-link {{ request()->routeIs('permisos.*') ? 'active' : '' }}" aria-label="Permisos por perfil">
                <i class="fas fa-key"></i>
                <span>Permisos-Perfil</span>
            </a>
            <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" aria-label="Gestión de usuarios">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>

            <div class="nav-group-label">Principal 1</div>

            <a href="{{ route('p1.1.index') }}" class="nav-link {{ request()->routeIs('p1.1.*') ? 'active' : '' }}">
                <i class="fas fa-circle-dot text-[10px]"></i>
                <span>Principal 1.1</span>
            </a>
            <a href="{{ route('p1.2.index') }}" class="nav-link {{ request()->routeIs('p1.2.*') ? 'active' : '' }}">
                <i class="fas fa-circle-dot text-[10px]"></i>
                <span>Principal 1.2</span>
            </a>

            <div class="nav-group-label">Principal 2</div>

            <a href="{{ route('p2.1.index') }}" class="nav-link {{ request()->routeIs('p2.1.*') ? 'active' : '' }}">
                <i class="fas fa-circle-dot text-[10px]"></i>
                <span>Principal 2.1</span>
            </a>
            <a href="{{ route('p2.2.index') }}" class="nav-link {{ request()->routeIs('p2.2.*') ? 'active' : '' }}">
                <i class="fas fa-circle-dot text-[10px]"></i>
                <span>Principal 2.2</span>
            </a>
        </nav>

        <div class="pb-1">
            <div class="flex items-center gap-2 px-5 py-3 border-t border-[var(--neon-border)]">
                <div class="status-dot"></div>
                <span style="font-size:11px; color:var(--text-3);">Sistema operativo</span>
            </div>
            <a href="{{ route('logout') }}" class="logout-btn" aria-label="Cerrar sesión">
                <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </aside>


    <div class="flex flex-col flex-1 min-w-0 h-screen">

        <header id="topbar" class="flex items-center px-6 gap-4 flex-shrink-0" role="banner">

            <nav aria-label="Ubicación" class="flex-1 flex items-center gap-1 text-[13px]">
                <a href="{{ route('home') }}" class="text-[var(--text-3)] hover:text-[var(--neon)] transition-colors" title="Ir al Inicio">
                    <i class="fas fa-house text-[11px]"></i>
                </a>
                <span class="breadcrumb-sep">/</span>
                @yield('breadcrumb', '<span style="color:var(--text-2)">Dashboard</span>')
            </nav>

            <div class="flex items-center gap-3">

                <div class="relative hidden sm:block">
                    <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-3)] text-[11px]"></i>
                    <input
                        type="search"
                        class="search-input"
                        placeholder="Buscar…"
                        aria-label="Buscar en el sistema"
                    >
                </div>

                <button class="icon-btn" aria-label="Notificaciones (3 nuevas)">
                    <i class="fas fa-bell text-xs"></i>
                    <span class="notif-dot" aria-hidden="true"></span>
                </button>

                <div style="width:1px; height:22px; background:var(--surface-4);"></div>

                <button
                    class="flex items-center gap-2 rounded-lg px-2 py-1 transition-colors hover:bg-[var(--surface-3)] focus-visible:outline-neon"
                    aria-label="Menú de usuario: Admin"
                    aria-haspopup="true"
                >
                    <div class="avatar" aria-hidden="true">A</div>
                    <div class="hidden sm:flex flex-col items-start leading-none">
                        <span style="font-size:13px; font-weight:500; color:var(--text-1);">Admin</span>
                        <span style="font-size:10px; color:var(--text-3);">Superadministrador</span>
                    </div>
                    <i class="fas fa-chevron-down text-[9px] ml-1" style="color:var(--text-3);"></i>
                </button>
            </div>
        </header>


        <main
            id="content"
            class="flex-1 overflow-y-auto p-6"
            role="main"
            aria-label="Contenido principal"
        >
            {{-- Flash messages --}}
            @if(session('success'))
                <div role="alert" style="
                    display:flex; align-items:center; gap:10px;
                    background:rgba(34,197,94,0.08);
                    border:1px solid rgba(34,197,94,0.25);
                    border-left:3px solid #22c55e;
                    border-radius:8px; padding:12px 16px;
                    margin-bottom:20px; font-size:13.5px; color:#86efac;
                ">
                    <i class="fas fa-circle-check" style="color:#22c55e;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div role="alert" style="
                    display:flex; align-items:center; gap:10px;
                    background:rgba(255,23,68,0.08);
                    border:1px solid var(--neon-border);
                    border-left:3px solid var(--neon);
                    border-radius:8px; padding:12px 16px;
                    margin-bottom:20px; font-size:13.5px; color:#fca5a5;
                ">
                    <i class="fas fa-triangle-exclamation" style="color:var(--neon);"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>