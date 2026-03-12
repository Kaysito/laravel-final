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

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --neon:          #FF1744;
            --neon-dim:      rgba(255, 23, 68, 0.22);
            --neon-border:   rgba(255, 23, 68, 0.30);
            --neon-glow-sm:  0 0 10px rgba(255,23,68,0.35);
            --neon-glow-md:  0 0 24px rgba(255,23,68,0.30), 0 0 48px rgba(255,23,68,0.12);

            --bg:         #111114;
            --panel:      #18181C;
            --input-bg:   #1F1F24;
            --input-bg-f: #232328;
            --border:     #2A2A32;
            --border-f:   rgba(255,23,68,0.35);
            --divider:    #22222A;

            --text-1:     #EEEEF2;
            --text-2:     #9898A8;
            --text-3:     #52525E;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: var(--bg);
            color: var(--text-1);
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(255,23,68,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,23,68,0.03) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
            z-index: 0;
        }

        .glow-orb {
            position: fixed;
            width: 520px; height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,23,68,0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .brand-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 44px 52px;
            border-right: 1px solid var(--divider);
            background: linear-gradient(160deg, #16161A 0%, var(--bg) 100%);
        }

        .logo-mark {
            width: 38px; height: 38px;
            background: var(--neon);
            clip-path: polygon(20% 0%,80% 0%,100% 20%,100% 80%,80% 100%,20% 100%,0% 80%,0% 20%);
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--neon-glow-sm);
            flex-shrink: 0;
        }

        .version-chip {
            font-family: 'Space Mono', monospace;
            font-size: 9px; color: var(--text-3);
            padding: 2px 7px;
            border: 1px solid var(--border);
            border-radius: 3px;
            letter-spacing: 0.05em;
        }

        .brand-headline {
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 600;
            line-height: 1.18;
            color: var(--text-1);
            letter-spacing: -0.02em;
        }

        .brand-headline em {
            font-style: normal;
            color: var(--neon);
            text-shadow: 0 0 28px rgba(255,23,68,0.4);
        }

        .brand-sub {
            font-size: 14px;
            color: var(--text-2);
            line-height: 1.65;
            margin-top: 16px;
            max-width: 340px;
        }

        .feature-item {
            display: flex; align-items: flex-start; gap: 12px;
            margin-top: 20px;
        }

        .feature-icon {
            width: 32px; height: 32px; flex-shrink: 0;
            border-radius: 8px;
            background: var(--panel);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--neon);
            font-size: 12px;
        }

        .feature-label {
            font-size: 13px; color: var(--text-2);
            line-height: 1.5;
        }

        .feature-label strong { color: var(--text-1); font-weight: 500; display: block; }

        .brand-footer {
            font-family: 'Space Mono', monospace;
            font-size: 10px; color: var(--text-3);
            letter-spacing: 0.06em;
        }

        .form-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 52px 48px;
            background: var(--panel);
        }

        .form-box {
            width: 100%;
            max-width: 380px;
        }

        .form-eyebrow {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--neon);
            margin-bottom: 8px;
        }

        .form-title {
            font-size: 22px; font-weight: 600;
            color: var(--text-1);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .form-subtitle {
            font-size: 13px; color: var(--text-3);
            margin-top: 5px;
        }

        .form-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--divider), transparent);
            margin: 28px 0;
        }

        .field { margin-bottom: 18px; }

        .field-label {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 12px; font-weight: 500;
            color: var(--text-2);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .field-label a {
            font-size: 11.5px; color: var(--text-3);
            text-decoration: none;
            transition: color 0.15s;
        }
        .field-label a:hover { color: var(--neon); }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: var(--text-3); font-size: 12px;
            pointer-events: none;
            transition: color 0.15s;
        }

        .input-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-3); font-size: 12px;
            cursor: pointer; background: none; border: none; padding: 0;
            transition: color 0.15s;
        }
        .input-toggle:hover { color: var(--text-2); }

        .field-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--text-1);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            padding: 11px 14px 11px 38px;
            transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
            outline: none;
        }

        .field-input::placeholder { color: var(--text-3); }

        .field-input:focus {
            background: var(--input-bg-f);
            border-color: var(--border-f);
            box-shadow: 0 0 0 3px rgba(255,23,68,0.09);
        }

        .field-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--neon); }

        .captcha-box {
            width: 100%;
            background: var(--input-bg);
            border: 1px dashed var(--border);
            border-radius: 9px;
            height: 68px;
            display: flex; align-items: center; justify-content: center;
            gap: 10px;
            color: var(--text-3);
            font-size: 12.5px;
            margin-bottom: 22px;
            transition: border-color 0.15s;
        }
        .captcha-box:hover { border-color: rgba(255,23,68,0.2); }

        .btn-submit {
            width: 100%;
            background: var(--neon);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 12px;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(255,23,68,0.35), 0 2px 8px rgba(0,0,0,0.3);
            transition: box-shadow 0.2s, transform 0.15s, background 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            letter-spacing: 0.01em;
        }

        .btn-submit:hover {
            box-shadow: 0 0 32px rgba(255,23,68,0.55), 0 4px 16px rgba(0,0,0,0.35);
            transform: translateY(-1px);
            background: #FF3358;
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: var(--neon-glow-sm);
        }

        .remember-row {
            display: flex; align-items: center; gap: 9px;
            margin-bottom: 22px;
        }

        .checkbox-custom {
            width: 17px; height: 17px; flex-shrink: 0;
            appearance: none;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }

        .checkbox-custom:checked {
            background: var(--neon);
            border-color: var(--neon);
            box-shadow: var(--neon-glow-sm);
        }

        .checkbox-custom:checked::after {
            content: '';
            position: absolute; left: 4px; top: 2px;
            width: 6px; height: 9px;
            border: 2px solid #fff;
            border-top: none; border-left: none;
            transform: rotate(42deg);
        }

        .remember-label {
            font-size: 13px; color: var(--text-2); cursor: pointer;
        }

        .alert-error {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,23,68,0.07);
            border: 1px solid rgba(255,23,68,0.25);
            border-left: 3px solid var(--neon);
            border-radius: 9px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: 13px; color: #fca5a5;
        }

        .form-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 11.5px; color: var(--text-3);
        }

        .form-footer a { color: var(--text-2); text-decoration: none; transition: color 0.15s; }
        .form-footer a:hover { color: var(--neon); }

        .status-bar {
            position: absolute; bottom: 20px;
            display: flex; align-items: center; gap: 7px;
            font-size: 11px; color: var(--text-3);
        }

        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 6px rgba(34,197,94,0.6);
            animation: pulse 2.5s infinite;
        }

        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

        @media (max-width: 768px) {
            .page { grid-template-columns: 1fr; }
            .brand-panel { display: none; }
            .form-panel { min-height: 100vh; padding: 40px 24px; }
        }
    </style>
</head>
<body>

    <div class="glow-orb" style="top:-120px; left:-120px;"></div>
    <div class="glow-orb" style="bottom:-120px; right:-120px;"></div>

    <div class="page">

        <section class="brand-panel" aria-hidden="true">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="logo-mark">
                    <i class="fas fa-bolt" style="color:#fff; font-size:13px;"></i>
                </div>
                <div style="display:flex; flex-direction:column; line-height:1; gap:4px;">
                    <span style="font-size:15px; font-weight:600; color:var(--text-1);">Proyecto</span>
                    <span class="version-chip">v2.4.1</span>
                </div>
            </div>

            <div>
                <h1 class="brand-headline">
                    Control total<br>sobre tu <em>plataforma</em>
                </h1>
                <p class="brand-sub">
                    Gestión centralizada de usuarios, permisos y módulos con trazabilidad completa de cada operación.
                </p>

                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                    <div class="feature-label">
                        <strong>Autenticación segura</strong>
                        Control de acceso basado en roles con auditoría en tiempo real.
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="feature-label">
                        <strong>Métricas en vivo</strong>
                        Monitoreo del sistema con indicadores actualizados al instante.
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-layer-group"></i></div>
                    <div class="feature-label">
                        <strong>Módulos configurables</strong>
                        Activa, ajusta o deshabilita funcionalidades sin interrupciones.
                    </div>
                </div>
            </div>

            <div class="brand-footer">
                © <script>document.write(new Date().getFullYear())</script> PROYECTO &nbsp;·&nbsp; SISTEMA CORPORATIVO &nbsp;·&nbsp; USO INTERNO
            </div>
        </section>

        <section class="form-panel" style="position:relative;">
            <div class="form-box">
                <div class="form-eyebrow">Acceso al sistema</div>
                <h2 class="form-title">Bienvenido de nuevo</h2>
                <p class="form-subtitle">Ingresa tus credenciales para continuar.</p>

                <div class="form-divider"></div>

                <div id="errorBox" class="alert-error" role="alert" style="display: none;">
                    <i class="fas fa-triangle-exclamation" style="color:var(--neon); flex-shrink:0;"></i>
                    <span id="errorMessage"></span>
                </div>

                @if(session('error'))
                <div class="alert-error" role="alert">
                    <i class="fas fa-triangle-exclamation" style="color:var(--neon); flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <form id="loginForm" action="javascript:void(0);" novalidate>
                    <div class="field">
                        <div class="field-label">
                            <label for="usuario">Usuario</label>
                        </div>
                        <div class="input-wrap">
                            <i class="fas fa-user input-icon"></i>
                            <input
                                id="usuario"
                                name="usuario"
                                type="text"
                                class="field-input"
                                placeholder="Ej. admin"
                                autocomplete="username"
                                required
                            >
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label">
                            <label for="password">Contraseña</label>
                            <a href="#" tabindex="-1">¿Olvidaste la tuya?</a>
                        </div>
                        <div class="input-wrap">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="field-input"
                                style="padding-right:42px;"
                                placeholder="••••••••••"
                                autocomplete="current-password"
                                required
                            >
                            <button
                                type="button"
                                class="input-toggle"
                                aria-label="Mostrar u ocultar contraseña"
                                onclick="
                                    const i = document.getElementById('password');
                                    const t = this.querySelector('i');
                                    if(i.type === 'password') {
                                        i.type = 'text';
                                        t.classList.replace('fa-eye', 'fa-eye-slash');
                                    } else {
                                        i.type = 'password';
                                        t.classList.replace('fa-eye-slash', 'fa-eye');
                                    }
                                "
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="remember-row">
                        <input type="checkbox" id="remember" name="remember" class="checkbox-custom">
                        <label for="remember" class="remember-label">Mantener sesión iniciada</label>
                    </div>

                    <div class="captcha-box" aria-label="Verificación reCAPTCHA">
                        <i class="fas fa-robot" style="font-size:18px; color:var(--text-3);"></i>
                        <div>
                            <div style="font-size:12.5px; color:var(--text-2);">No soy un robot</div>
                            <div style="font-size:10px; margin-top:2px; color:var(--text-3);">reCAPTCHA · Privacidad · Términos</div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-arrow-right-to-bracket" style="font-size:13px;"></i>
                        Ingresar al sistema
                    </button>
                </form>

                <div class="form-footer">
                    ¿Problemas para acceder? Contacta a
                    <a href="mailto:soporte@proyecto.mx">soporte técnico</a>
                </div>
            </div>

            <div class="status-bar">
                <div class="status-dot"></div>
                <span>Todos los sistemas operativos en línea</span>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const btn = document.querySelector('.btn-submit');
            const errorBox = document.getElementById('errorBox');
            const errorMessage = document.getElementById('errorMessage');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:13px;"></i> Validando...';
            btn.disabled = true;
            errorBox.style.display = 'none';

            // 1. Recopilamos los datos del formulario
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // 🚨 EL TRUCO: Capturamos el estado real del checkbox como booleano (true/false)
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
                const text = await response.text(); 
                try {
                    return JSON.parse(text); 
                } catch (error) {
                    document.open();
                    document.write(text);
                    document.close();
                    return null;
                }
            })
            .then(result => {
                if(!result) return; 
                
                if(result.error) {
                    errorMessage.textContent = result.error;
                    errorBox.style.display = 'flex';
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                } 
                else if(result.token) {
                    // 🚨 LA MAGIA DEL TIEMPO: Calculamos los segundos reales basados en la respuesta del backend
                    // Si el backend no manda 'expires_in', por seguridad le damos 1 hora (3600 seg)
                    const maxAgeSegundos = result.expires_in ? (result.expires_in * 60) : 3600;
                    
                    // Guardamos la cookie con el tiempo correcto
                    document.cookie = `jwt_token=${result.token}; path=/; max-age=${maxAgeSegundos}; SameSite=Strict`;
                    
                    window.location.href = result.redirect;
                }
            })
            .catch(error => {
                errorMessage.textContent = "Error de red. Verifica la consola.";
                errorBox.style.display = 'flex';
                btn.innerHTML = originalText;
                btn.disabled = false;
                console.error("Detalle de fetch:", error);
            });
        });
    </script>
</body>
</html>