<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — Proyecto</title>
    
    {{-- Tailwind y Fuentes --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Script de reCAPTCHA optimizado (async defer evita bloquear la carga) --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        /* ☀️ TEMA CLARO */
        :root {
            --surface-1: #ffffff;
            --surface-2: #fdf8f8;
            --surface-3: #f5ebeb;
            --surface-4: #e8dada;
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
            --text-1: #f0f0f5;
            --text-2: #a0a0b0;
            --text-3: #60607a;
            --neon-border: rgba(230,55,87,0.4);
        }

        html, body {
            height: 100%; margin: 0;
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--surface-1);
            color: var(--text-1);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ── Layout ── */
        .page { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }

        /* ── Left Panel ── */
        .brand-panel {
            position: relative; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: var(--surface-2); border-right: 1px solid var(--surface-4);
            overflow: hidden;
        }
        .brand-panel::before {
            content: ''; position: absolute; inset: 0;
            background-image: linear-gradient(var(--surface-4) 1px, transparent 1px), linear-gradient(90deg, var(--surface-4) 1px, transparent 1px);
            background-size: 40px 40px; opacity: 0.5; pointer-events: none;
        }
        .brand-orb {
            position: absolute; width: 360px; height: 360px; border-radius: 50%;
            background: radial-gradient(circle, var(--neon-muted) 0%, transparent 68%); pointer-events: none;
        }
        .logo-wrap { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 28px; }
        
        .laravel-svg {
            width: 90px; height: 90px;
            filter: drop-shadow(0 0 22px rgba(230,55,87,0.45)) drop-shadow(0 0 48px rgba(230,55,87,0.18));
            animation: logoFloat 6s ease-in-out infinite, logoGlow 4s ease-in-out infinite alternate;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-8px) rotate(0.8deg); }
            50% { transform: translateY(-14px) rotate(0deg); }
            75% { transform: translateY(-8px) rotate(-0.8deg); }
        }
        @keyframes logoGlow {
            0%   { filter: drop-shadow(0 0 18px rgba(230,55,87,0.35)) drop-shadow(0 0 40px rgba(230,55,87,0.12)); }
            100% { filter: drop-shadow(0 0 30px rgba(230,55,87,0.60)) drop-shadow(0 0 70px rgba(230,55,87,0.25)); }
        }

        /* Anillos decorativos */
        .orbit-ring { position: absolute; border-radius: 50%; border: 1px solid; animation: orbitSpin linear infinite; pointer-events: none; }
        .orbit-ring-1 { width: 150px; height: 150px; border-color: rgba(230,55,87,0.15); animation-duration: 18s; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .orbit-ring-2 { width: 210px; height: 210px; border-color: rgba(230,55,87,0.08); animation-duration: 28s; animation-direction: reverse; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .orbit-ring-3 { width: 290px; height: 290px; border-color: rgba(230,55,87,0.04); animation-duration: 40s; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        @keyframes orbitSpin { from { transform: translate(-50%, -50%) rotate(0deg); } to { transform: translate(-50%, -50%) rotate(360deg); } }

        .orbit-dot { position: absolute; width: 5px; height: 5px; border-radius: 50%; background: var(--neon); box-shadow: 0 0 8px rgba(230,55,87,0.8); top: -2.5px; left: 50%; transform: translateX(-50%); }

        .brand-wordmark { text-align: center; animation: fadeUp 1s ease 0.2s both; }
        .brand-name { font-family: 'Space Mono', monospace; font-size: 13px; letter-spacing: 0.25em; text-transform: uppercase; color: var(--text-2); }
        .brand-tagline { font-size: 11px; color: var(--text-3); margin-top: 5px; letter-spacing: 0.05em; }
        .brand-footer { position: absolute; bottom: 24px; font-family: 'Space Mono', monospace; font-size: 9px; color: var(--text-3); letter-spacing: 0.08em; text-align: center; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Right Panel ── */
        .form-panel { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 52px 48px; background: var(--surface-1); transition: background-color 0.3s ease; }
        .form-box { width: 100%; max-width: 380px; }

        .form-eyebrow { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.22em; text-transform: uppercase; color: var(--neon); margin-bottom: 8px; animation: fadeUp 0.7s ease 0.05s both; }
        .form-title { font-size: 22px; font-weight: 600; color: var(--text-1); line-height: 1.2; letter-spacing: -0.015em; animation: fadeUp 0.7s ease 0.1s both; }
        .form-subtitle { font-size: 13px; color: var(--text-3); margin-top: 5px; animation: fadeUp 0.7s ease 0.15s both; }
        .form-divider { height: 1px; background: linear-gradient(90deg, transparent, var(--surface-4) 40%, var(--surface-4) 60%, transparent); margin: 28px 0; animation: fadeUp 0.7s ease 0.2s both; }

        /* Formularios */
        .field { margin-bottom: 16px; }
        .field-label { display: flex; align-items: center; justify-content: space-between; font-size: 12px; font-weight: 500; color: var(--text-2); margin-bottom: 7px; letter-spacing: 0.01em; }
        .field-label a { font-size: 11.5px; color: var(--text-3); text-decoration: none; transition: color 0.15s; }
        .field-label a:hover { color: var(--neon); }

        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: 12px; pointer-events: none; transition: color 0.15s; }
        .input-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: 12px; cursor: pointer; background: none; border: none; padding: 0; transition: color 0.15s; }
        .input-toggle:hover { color: var(--text-2); }
        .field-input { width: 100%; background: var(--surface-3); border: 1px solid var(--surface-4); border-radius: 9px; color: var(--text-1); font-family: 'DM Sans', sans-serif; font-size: 14px; padding: 11px 14px 11px 38px; transition: border-color 0.15s, box-shadow 0.15s; outline: none; }
        .field-input::placeholder { color: var(--text-3); }
        .field-input:focus { background: var(--surface-1); border-color: var(--neon-border); box-shadow: 0 0 0 3px var(--neon-muted); }
        .input-wrap:focus-within .input-icon { color: var(--neon); }

        .remember-row { display: flex; align-items: center; gap: 9px; margin-bottom: 20px; }
        .checkbox-custom { width: 17px; height: 17px; flex-shrink: 0; appearance: none; background: var(--surface-3); border: 1px solid var(--surface-4); border-radius: 5px; cursor: pointer; transition: all 0.15s; position: relative; }
        .checkbox-custom:checked { background: var(--neon); border-color: var(--neon); box-shadow: 0 0 8px rgba(230,55,87,0.4); }
        .checkbox-custom:checked::after { content: ''; position: absolute; left: 4px; top: 2px; width: 6px; height: 9px; border: 2px solid #fff; border-top: none; border-left: none; transform: rotate(42deg); }
        .remember-label { font-size: 13px; color: var(--text-2); cursor: pointer; }

        /* Alertas Optimizadas */
        .alert-error { display: flex; align-items: center; gap: 10px; background: rgba(230,55,87,0.06); border: 1px solid var(--neon-border); border-left: 3px solid var(--neon); border-radius: 9px; padding: 11px 14px; margin-bottom: 18px; font-size: 13px; color: var(--neon); font-weight: 500; }

        .btn-submit { width: 100%; background: var(--neon); color: #fff; font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600; padding: 12px; border: none; border-radius: 9px; cursor: pointer; box-shadow: 0 0 20px rgba(230,55,87,0.30), 0 2px 8px rgba(0,0,0,0.1); transition: box-shadow 0.2s, transform 0.15s, background 0.15s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-submit:hover { box-shadow: 0 0 30px rgba(230,55,87,0.50), 0 4px 12px rgba(0,0,0,0.2); transform: translateY(-1px); background: var(--neon-dark); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }

        .form-footer { margin-top: 22px; text-align: center; font-size: 11.5px; color: var(--text-3); }
        .form-footer a { color: var(--text-2); text-decoration: none; transition: color 0.15s; }
        .form-footer a:hover { color: var(--neon); }

        .status-bar { position: absolute; bottom: 20px; display: flex; align-items: center; gap: 7px; font-size: 11px; color: var(--text-3); }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 6px rgba(34,197,94,0.6); animation: blink 2.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* Toast Global */
        .toast { position: fixed; bottom: 24px; right: 24px; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 10px; background: var(--surface-3); border: 1px solid var(--surface-4); box-shadow: 0 8px 32px rgba(0,0,0,0.2); z-index: 1000; transform: translateY(80px); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); max-width: 360px; pointer-events: none; }
        .toast.show { transform: translateY(0); opacity: 1; pointer-events: auto; }

        @media (max-width: 768px) { .page { grid-template-columns: 1fr; } .brand-panel { display: none; } .form-panel { min-height: 100vh; padding: 40px 24px; } }
    </style>
</head>
<body>

<div class="page">
    <section class="brand-panel" aria-hidden="true">
        <div class="brand-orb"></div>
        <div class="orbit-ring orbit-ring-1"><div class="orbit-dot"></div></div>
        <div class="orbit-ring orbit-ring-2"></div>
        <div class="orbit-ring orbit-ring-3"></div>

        <div class="logo-wrap">
            {{-- SVG Logo Minificado --}}
            <svg class="laravel-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 133">
                <path fill="var(--neon)" d="M26.027.137c-.203.078-5.941 3.347-12.746 7.257-8.246 4.742-12.48 7.238-12.707 7.473-.18.203-.383.554-.445.777-.172.574-.184 84.703-.012 85.309.062.234.265.578.449.77 .445.468 49.672 28.773 50.27 28.91.277.066.59.054.898-.031.672-.168 49.77-28.41 50.207-28.871.18-.2.383-.543.449-.777.086-.278.117-4.676.117-13.938V73.48l11.969-6.875c11.285-6.488 11.976-6.895 12.265-7.34.297-.48.297-15.058 0-30.93-.168-.148-5.918-3.504-12.789-7.461L101.172 14.016l-1.387.015-12.21 7.012c-6.723 3.867-12.438 7.172-12.715 7.351-.277.184-.609.524-.746.77l-.246.426-.055 13.734-.05 13.738-10.082 5.809c-5.547 3.187-10.164 5.828-10.262 5.851-.18.05-.191-1.258-.191-26.504V15.633l-.266-.457c-.332-.555 1.16-1.559-13.824-10.172C26.57-.332 26.871-.18 26.027.137zm11.551 10.52c5.258 3.015 9.559 5.511 9.559 5.542 0 .032-4.609 2.696-10.242 5.934L26.645 28.016 16.414 22.133C10.793 18.895 6.188 16.23 6.188 16.2c0-.031 4.597-2.695 10.218-5.926l10.207-5.87 .703.382c.395.215 5.016 2.856 10.262 5.87zm73.152 13.535c5.535 3.187 10.113 5.82 10.156 5.863.117.106-20.184 11.774-20.461 11.762-.277-.008-20.328-11.57-20.32-11.71.012-.16 20.184-11.766 20.387-11.735.094.024 4.703 2.645 10.238 5.82zm-95.902 1.683l9.758 5.617.055 27.813.05 27.816.238.375c.125.2.36.47.531.594.16.117 5.59 3.21 12.063 6.863l11.758 6.64v11.766c0 6.457-.043 11.754-.098 11.754-.043 0-10.207-5.816-22.582-12.937l-22.496-12.938-.031-39.773L4.055 19.703l.5.277c.289.152 4.906 2.805 10.273 5.895zm34.453 19.578v25.629l-.394.258c-.535.34-20.074 11.57-20.141 11.57-.031 0-.054-11.57-.054-25.715l.011-25.703 10.207-5.871c5.613-3.23 10.242-5.852 10.297-5.832.039.023.074 11.574.074 25.664zm38.941-5.894l10.23 5.882v11.66c0 11.063-.012 11.657-.18 11.594-.109-.043-4.738-2.696-10.293-5.895l-10.113-5.808V45.336c0-6.418.031-11.66.062-11.66.043 0 4.672 2.644 10.294 5.882zm34.871 5.703c0 6.383-.043 11.649-.086 11.7-.074.117-20.309 11.777-20.437 11.777-.031 0-.063-5.242-.063-11.66V45.422l10.207-5.875c5.621-3.227 10.25-5.871 10.293-5.871.055 0 .086 5.21.086 11.586zm-36.863 21.207c8.605 4.953 10.09 5.84 9.941 5.957-.097.062-3.359 1.937-7.242 4.156-3.883 2.215-13.941 7.95-22.36 12.746l-15.296 8.727-.489-.266c-2.922-1.598-19.875-11.242-19.875-11.316-.008-.16 44.855-25.942 45.035-25.875.086.031 4.715 2.672 10.286 5.871zm12.203 21.09l-.035 11.68L75.914 112.176c-12.371 7.12-22.54 12.937-22.59 12.937-.055 0-.098-4.754-.098-11.754V101.594l22.54-12.852c12.382-7.066 22.558-12.851 22.613-12.863.043 0 .062 5.254.054 11.68z"/>
            </svg>
            <div class="brand-wordmark">
                <div class="brand-name">Proyecto</div>
                <div class="brand-tagline">Sistema Corporativo · v2.4.1</div>
            </div>
        </div>
        <div class="brand-footer">© <script>document.write(new Date().getFullYear())</script> Proyecto &nbsp;·&nbsp; Uso Interno</div>
    </section>

    <section class="form-panel">
        <div class="form-box">
            <div class="form-eyebrow">Acceso al sistema</div>
            <h2 class="form-title">Bienvenido de nuevo</h2>
            <p class="form-subtitle">Ingresa tus credenciales para continuar.</p>
            <div class="form-divider"></div>

            {{-- Contenedor dinámico de Errores --}}
            <div id="errorBox" class="alert-error" role="alert" style="display:none;">
                <i class="fas fa-triangle-exclamation" style="flex-shrink:0;"></i>
                <span id="errorMessage"></span>
            </div>

            @if(session('error'))
            <div class="alert-error" role="alert">
                <i class="fas fa-triangle-exclamation" style="flex-shrink:0;"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form id="loginForm" novalidate>
                <div class="field">
                    <div class="field-label"><label for="usuario">Usuario</label></div>
                    <div class="input-wrap">
                        <i class="fas fa-user input-icon"></i>
                        <input id="usuario" name="usuario" type="text" class="field-input" placeholder="Ej. admin" autocomplete="username" required>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label">
                        <label for="password">Contraseña</label>
                        <a href="#" tabindex="-1">¿Olvidaste la tuya?</a>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" name="password" type="password" class="field-input" style="padding-right:42px;" placeholder="••••••••••" autocomplete="current-password" required>
                        <button type="button" class="input-toggle" aria-label="Mostrar u ocultar contraseña" onclick="const i=document.getElementById('password'),t=this.querySelector('i');if(i.type==='password'){i.type='text';t.classList.replace('fa-eye','fa-eye-slash');}else{i.type='password';t.classList.replace('fa-eye-slash','fa-eye');}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" class="checkbox-custom">
                    <label for="remember" class="remember-label">Mantener sesión iniciada</label>
                </div>

                <div class="flex justify-center mb-5">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fas fa-arrow-right-to-bracket text-[13px]"></i> <span>Ingresar al sistema</span>
                </button>
            </form>

            <div class="form-footer">
                ¿Problemas para acceder? Contacta a <a href="mailto:soporte@proyecto.mx">soporte técnico</a>
            </div>
        </div>

        <div class="status-bar">
            <div class="status-dot"></div><span>Todos los sistemas operativos en línea</span>
        </div>
    </section>
</div>

<button onclick="toggleTheme()" class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] shadow-lg flex items-center justify-center text-[var(--neon)] hover:scale-110 transition-transform z-50">
    <i id="themeIcon" class="fas fa-moon text-lg"></i>
</button>

<div id="toast" class="toast">
    <div id="toastIcon" class="flex-shrink-0"></div>
    <div>
        <p id="toastMsg" class="text-sm font-medium text-[var(--text-1)]"></p>
        <p id="toastSub" class="text-xs text-[var(--text-3)] mt-0.5"></p>
    </div>
</div>

<script>
// --- Manejo del Tema ---
const initTheme = () => {
    const saved = localStorage.getItem('theme') || 'light'; 
    document.documentElement.setAttribute('data-theme', saved);
    document.getElementById('themeIcon').className = saved === 'dark' ? 'fas fa-sun text-yellow-400 text-lg' : 'fas fa-moon text-[var(--neon)] text-lg';
};
const toggleTheme = () => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const next = isDark ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    document.getElementById('themeIcon').className = isDark ? 'fas fa-moon text-[var(--neon)] text-lg' : 'fas fa-sun text-yellow-400 text-lg';
};
initTheme();

// --- Sistema de Toast ---
window.showToast = (msg, tipo = 'success', sub = '') => {
    const t = document.getElementById('toast');
    const icons = {
        success: '<i class="fas fa-circle-check text-green-400 text-lg"></i>',
        error:   '<i class="fas fa-circle-xmark text-red-400 text-lg"></i>',
        info:    '<i class="fas fa-circle-info text-blue-400 text-lg"></i>'
    };
    t.className = `toast ${tipo}`;
    document.getElementById('toastIcon').innerHTML = icons[tipo] || icons.info;
    document.getElementById('toastMsg').textContent = msg;
    document.getElementById('toastSub').textContent = sub;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 5000);
};

@if(session('success')) document.addEventListener('DOMContentLoaded', () => window.showToast("{{ session('success') }}", 'success')); @endif

// --- Lógica del Login Blindada ---
const form = document.getElementById('loginForm');
const btn = document.getElementById('btnSubmit');
const errorBox = document.getElementById('errorBox');
const errorMessage = document.getElementById('errorMessage');

const showError = (msg) => {
    errorMessage.textContent = msg;
    errorBox.style.display = 'flex';
    btn.innerHTML = '<i class="fas fa-arrow-right-to-bracket text-[13px]"></i> <span>Ingresar al sistema</span>';
    btn.disabled = false;
    if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
};

form.addEventListener('submit', function(e) {
    e.preventDefault();
    errorBox.style.display = 'none';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.usuario = data.usuario?.trim();
    data.remember = document.getElementById('remember').checked;
    
    // Capturar la respuesta de reCAPTCHA
    if (typeof grecaptcha !== 'undefined') {
        data['g-recaptcha-response'] = grecaptcha.getResponse();
    }

    // 1. Validación Frontend
    if (!data.usuario) return showError("Debes ingresar tu usuario para continuar.");
    if (!data.password) return showError("Debes ingresar tu contraseña.");
    if (!data['g-recaptcha-response']) return showError("Por favor, marca la casilla de seguridad 'No soy un robot'.");

    // 2. Cargando
    btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[13px]"></i> <span>Validando...</span>';
    btn.disabled = true;

    // 3. Petición al Backend
    fetch("/login", {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'Accept': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        },
        body: JSON.stringify(data)
    })
    .then(async response => {
        // Manejo de errores de Sesión Caducada o CSRF Mismatch
        if (response.status === 419) {
            window.location.reload(); 
            return null;
        }
        
        // Redirección si la ruta no existe o falla el servidor
        if (response.status >= 500 || response.status === 404) {
            window.location.href = '/404';
            return null;
        }

        const text = await response.text();
        try { return JSON.parse(text); } catch { return { error: "Respuesta no válida del servidor." }; }
    })
    .then(result => {
        if (!result) return;
        
        if (result.error) {
            showError(result.error);
        } 
        else if (result.errors) {
            const firstError = Object.values(result.errors)[0][0];
            showError(firstError);
        } 
        // ÉXITO: Guardamos Token y Redirigimos
        else if (result.token) {
            // Guardar en Cookie con formato correcto para que Laravel lo detecte
            const expires = result.expires_in ? result.expires_in * 60 : 3600;
            document.cookie = `jwt_token=${result.token}; path=/; max-age=${expires}; SameSite=Lax; Secure`;
            
            // Guardar datos en local para el Dashboard
            localStorage.setItem('user_data', JSON.stringify(result.user));
            
            // Redirección
            window.location.replace(result.redirect);
        }
    })
    .catch(err => {
        console.error("Error en Login:", err);
        showError("Error de conexión. Inténtalo de nuevo.");
    });
});
</script>
</body>
</html>