<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — Proyecto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- 🤖 SCRIPT DE GOOGLE RECAPTCHA 🤖 --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }

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

        html, body {
            height: 100%; margin: 0;
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--surface-1);
            color: var(--text-1);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ── Layout ── */
        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .brand-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--surface-2);
            border-right: 1px solid var(--surface-4);
            overflow: hidden;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .brand-panel::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(var(--surface-4) 1px, transparent 1px),
                linear-gradient(90deg, var(--surface-4) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.5;
            pointer-events: none;
        }

        .brand-orb {
            position: absolute;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--neon-muted) 0%, transparent 68%);
            pointer-events: none;
        }

        .logo-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 28px;
        }

        .laravel-svg {
            width: 90px; height: 90px;
            filter: drop-shadow(0 0 22px rgba(230,55,87,0.45))
                    drop-shadow(0 0 48px rgba(230,55,87,0.18));
            animation: logoFloat 6s ease-in-out infinite, logoGlow 4s ease-in-out infinite alternate;
            transform-origin: center center;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25%       { transform: translateY(-8px) rotate(0.8deg); }
            50%       { transform: translateY(-14px) rotate(0deg); }
            75%       { transform: translateY(-8px) rotate(-0.8deg); }
        }

        @keyframes logoGlow {
            0%   { filter: drop-shadow(0 0 18px rgba(230,55,87,0.35)) drop-shadow(0 0 40px rgba(230,55,87,0.12)); }
            100% { filter: drop-shadow(0 0 30px rgba(230,55,87,0.60)) drop-shadow(0 0 70px rgba(230,55,87,0.25)); }
        }

        .orbit-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid;
            animation: orbitSpin linear infinite;
            pointer-events: none;
        }
        .orbit-ring-1 { width: 150px; height: 150px; border-color: rgba(230,55,87,0.15); animation-duration: 18s; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .orbit-ring-2 { width: 210px; height: 210px; border-color: rgba(230,55,87,0.08); animation-duration: 28s; animation-direction: reverse; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .orbit-ring-3 { width: 290px; height: 290px; border-color: rgba(230,55,87,0.04); animation-duration: 40s; top: 50%; left: 50%; transform: translate(-50%, -50%); }

        @keyframes orbitSpin { from { transform: translate(-50%, -50%) rotate(0deg); } to { transform: translate(-50%, -50%) rotate(360deg); } }
        .orbit-dot { position: absolute; width: 5px; height: 5px; border-radius: 50%; background: var(--neon); box-shadow: 0 0 8px rgba(230,55,87,0.8); top: -2.5px; left: 50%; transform: translateX(-50%); }

        .brand-wordmark { text-align: center; animation: fadeUp 1s ease 0.2s both; }
        .brand-name { font-family: 'Space Mono', monospace; font-size: 13px; letter-spacing: 0.25em; text-transform: uppercase; color: var(--text-2); }
        .brand-tagline { font-size: 11px; color: var(--text-3); margin-top: 5px; letter-spacing: 0.05em; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .brand-footer { position: absolute; bottom: 24px; font-family: 'Space Mono', monospace; font-size: 9px; color: var(--text-3); letter-spacing: 0.08em; text-align: center; }

        /* ── RIGHT PANEL ── */
        .form-panel {
            position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 52px 48px; background: var(--surface-1); transition: background-color 0.3s ease;
        }

        .form-box { width: 100%; max-width: 380px; }
        .form-eyebrow { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.22em; text-transform: uppercase; color: var(--neon); margin-bottom: 8px; animation: fadeUp 0.7s ease 0.05s both; }
        .form-title { font-size: 22px; font-weight: 600; color: var(--text-1); line-height: 1.2; letter-spacing: -0.015em; animation: fadeUp 0.7s ease 0.1s both; }
        .form-subtitle { font-size: 13px; color: var(--text-3); margin-top: 5px; animation: fadeUp 0.7s ease 0.15s both; }
        .form-divider { height: 1px; background: linear-gradient(90deg, transparent, var(--surface-4) 40%, var(--surface-4) 60%, transparent); margin: 28px 0; animation: fadeUp 0.7s ease 0.2s both; }

        /* Campos */
        .field { margin-bottom: 16px; }
        .field-label { display: flex; align-items: center; justify-content: space-between; font-size: 12px; font-weight: 500; color: var(--text-2); margin-bottom: 7px; }
        .field-label a { font-size: 11.5px; color: var(--text-3); text-decoration: none; transition: color 0.15s; }
        .field-label a:hover { color: var(--neon); }

        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: 12px; pointer-events: none; }
        .input-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: 12px; cursor: pointer; background: none; border: none; padding: 0; }
        
        .field-input {
            width: 100%; background: var(--surface-3); border: 1px solid var(--surface-4); border-radius: 9px; color: var(--text-1);
            font-family: 'DM Sans', sans-serif; font-size: 14px; padding: 11px 14px 11px 38px; transition: all 0.15s; outline: none;
        }
        .field-input:focus { background: var(--surface-1); border-color: var(--neon-border); box-shadow: 0 0 0 3px var(--neon-muted); }

        .remember-row { display: flex; align-items: center; gap: 9px; margin-bottom: 20px; }
        .checkbox-custom { width: 17px; height: 17px; appearance: none; background: var(--surface-3); border: 1px solid var(--surface-4); border-radius: 5px; cursor: pointer; position: relative; }
        .checkbox-custom:checked { background: var(--neon); border-color: var(--neon); }
        .checkbox-custom:checked::after { content: ''; position: absolute; left: 4px; top: 2px; width: 6px; height: 9px; border: 2px solid #fff; border-top: none; border-left: none; transform: rotate(42deg); }
        .remember-label { font-size: 13px; color: var(--text-2); cursor: pointer; }

        .alert-error { display: flex; align-items: center; gap: 10px; background: rgba(230,55,87,0.06); border: 1px solid var(--neon-border); border-left: 3px solid var(--neon); border-radius: 9px; padding: 11px 14px; margin-bottom: 18px; font-size: 13px; color: var(--neon); font-weight: 500; }

        .btn-submit {
            width: 100%; background: var(--neon); color: #fff; font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600; padding: 12px; border-radius: 9px; cursor: pointer;
            box-shadow: 0 0 20px rgba(230,55,87,0.30); transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover { background: var(--neon-dark); transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }

        .form-footer { margin-top: 22px; text-align: center; font-size: 11.5px; color: var(--text-3); }
        .status-bar { position: absolute; bottom: 20px; display: flex; align-items: center; gap: 7px; font-size: 11px; color: var(--text-3); }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; animation: blink 2.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        .toast { position: fixed; bottom: 24px; right: 24px; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 10px; background: var(--surface-3); border: 1px solid var(--surface-4); box-shadow: 0 8px 32px rgba(0,0,0,0.2); z-index: 1000; transform: translateY(80px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }

        @media (max-width: 768px) { .page { grid-template-columns: 1fr; } .brand-panel { display: none; } }
    </style>
</head>
<body>

<div class="page">
    <section class="brand-panel">
        <div class="brand-orb"></div>
        <div class="orbit-ring orbit-ring-1"><div class="orbit-dot"></div></div>
        <div class="orbit-ring orbit-ring-2"></div>
        <div class="orbit-ring orbit-ring-3"></div>

        <div class="logo-wrap">
            <svg class="laravel-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 133">
                <path fill="var(--neon)" d="M26.027.137c-.203.078-5.941 3.347-12.746 7.257-8.246 4.742-12.48 7.238-12.707 7.473-.18.203-.383.554-.445.777-.172.574-.184 84.703-.012 85.309.062.234.265.578.449.77 .445.468 49.672 28.773 50.27 28.91.277.066.59.054.898-.031.672-.168 49.77-28.41 50.207-28.871.18-.2.383-.543.449-.777.086-.278.117-4.676.117-13.938V73.48l11.969-6.875c11.285-6.488 11.976-6.895 12.265-7.34.297-.48.297-15.058 0-30.93-.168-.148-5.918-3.504-12.789-7.461L101.172 14.016l-1.387.015-12.21 7.012c-6.723 3.867-12.438 7.172-12.715 7.351-.277.184-.609.524-.746.77l-.246.426-.055 13.734-.05 13.738-10.082 5.809c-5.547 3.187-10.164 5.828-10.262 5.851-.18.05-.191-1.258-.191-26.504V15.633l-.266-.457c-.332-.555 1.16-1.559-13.824-10.172C26.57-.332 26.871-.18 26.027.137z"/>
            </svg>
            <div class="brand-wordmark">
                <div class="brand-name">Proyecto</div>
                <div class="brand-tagline">Sistema Corporativo · v2.4.1</div>
            </div>
        </div>
        <div class="brand-footer">© <script>document.write(new Date().getFullYear())</script> Proyecto · Uso Interno</div>
    </section>

    <section class="form-panel">
        <div class="form-box">
            <div class="form-eyebrow">Acceso al sistema</div>
            <h2 class="form-title">Bienvenido de nuevo</h2>
            <p class="form-subtitle">Ingresa tus credenciales para continuar.</p>
            <div class="form-divider"></div>

            <div id="errorBox" class="alert-error" role="alert" style="display:none;">
                <i class="fas fa-triangle-exclamation"></i>
                <span id="errorMessage"></span>
            </div>

            <form id="loginForm" novalidate>
                <div class="field">
                    <div class="field-label"><label for="usuario">Usuario</label></div>
                    <div class="input-wrap">
                        <i class="fas fa-user input-icon"></i>
                        <input id="usuario" name="usuario" type="text" class="field-input" placeholder="Ej. admin" required>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label">
                        <label for="password">Contraseña</label>
                        <a href="#">¿Olvidaste la tuya?</a>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" name="password" type="password" class="field-input" placeholder="••••••••••" required>
                        <button type="button" class="input-toggle" onclick="const i=document.getElementById('password'),t=this.querySelector('i');if(i.type==='password'){i.type='text';t.classList.replace('fa-eye','fa-eye-slash');}else{i.type='password';t.classList.replace('fa-eye-slash','fa-eye');}">
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

                <button type="submit" class="btn-submit">
                    <i class="fas fa-arrow-right-to-bracket" style="font-size:13px;"></i>
                    Ingresar al sistema
                </button>
            </form>
        </div>
        <div class="status-bar"><div class="status-dot"></div><span>Sistemas operativos en línea</span></div>
    </section>
</div>

<button onclick="toggleTheme()" class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] shadow-lg flex items-center justify-center text-[var(--neon)] z-50">
    <i id="themeIcon" class="fas fa-moon text-lg"></i>
</button>

<div id="toast" class="toast">
    <div id="toastIcon"></div>
    <div><p id="toastMsg" class="text-sm font-medium"></p></div>
</div>

<script>
// --- Tu Lógica Original de Tema ---
function initTheme() {
    const saved = localStorage.getItem('theme') || 'light'; 
    document.documentElement.setAttribute('data-theme', saved);
    updateIcon(saved);
}
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateIcon(next);
}
function updateIcon(theme) {
    const icon = document.getElementById('themeIcon');
    icon.className = theme === 'dark' ? 'fas fa-sun text-yellow-400 text-lg' : 'fas fa-moon text-[var(--neon)] text-lg';
}
initTheme();

// --- Tu Lógica de Toast ---
window.showToast = (msg, tipo = 'success') => {
    const t = document.getElementById('toast');
    t.classList.add('show');
    document.getElementById('toastMsg').textContent = msg;
    setTimeout(() => t.classList.remove('show'), 5000);
};

// --- Lógica del Login (Fusionada y Corregida para Render/HTTPS) ---
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.querySelector('.btn-submit');
    const errorBox = document.getElementById('errorBox');
    const errorMessage = document.getElementById('errorMessage');
    const originalText = btn.innerHTML;

    // 1. VALIDACIÓN PREVIA
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const captchaRes = typeof grecaptcha !== 'undefined' ? grecaptcha.getResponse() : '';

    if(!data.usuario.trim()) { showError("Debes ingresar tu usuario."); return; }
    if(!data.password) { showError("Debes ingresar tu contraseña."); return; }
    if(!captchaRes) { showError("Por favor, marca la casilla 'No soy un robot'."); return; }

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validando...';
    btn.disabled = true;
    errorBox.style.display = 'none';

    data['g-recaptcha-response'] = captchaRes;
    data.remember = document.getElementById('remember').checked;

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
        // Manejo de errores de servidor o página no encontrada
        if (response.status >= 500 || response.status === 404) {
             window.location.href = '/404';
             return null;
        }
        const text = await response.text();
        try { return JSON.parse(text); }
        catch { 
            // Si el servidor manda un error HTML en lugar de JSON, lo mostramos para debug
            document.open(); document.write(text); document.close(); 
            return null; 
        }
    })
    .then(result => {
        if (!result) return;
        
        if (result.error) {
            showError(result.error);
        } 
        else if (result.errors) {
            const firstErr = Object.values(result.errors)[0][0];
            showError(firstErr);
        } 
        else if (result.token) {
            // ✨ ÉXITO: Guardado de Cookie Robusto para HTTPS (Render)
            const maxAge = result.expires_in ? (result.expires_in * 60) : 3600;
            
            // IMPORTANTE: Agregamos Secure y cambiamos Strict por Lax para mejorar compatibilidad en Render
            document.cookie = `jwt_token=${result.token}; path=/; max-age=${maxAge}; SameSite=Lax; Secure`;
            
            localStorage.setItem('user_data', JSON.stringify(result.user));
            
            // Usamos replace para evitar que el usuario regrese al login con el botón de atrás
            window.location.replace(result.redirect);
        }
    })
    .catch(error => {
        showError("Error de red o servidor no disponible.");
        console.error(error);
    });

    function showError(msg) {
        errorMessage.textContent = msg;
        errorBox.style.display = 'flex';
        btn.innerHTML = originalText;
        btn.disabled = false;
        if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
    }
});
</script>
</body>
</html>