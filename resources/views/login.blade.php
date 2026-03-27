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

        /* ☀️ DEFAULT: TEMA CLARO (Misma paleta que el Dashboard) */
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

        /* ══════════════════════════════════════
           LEFT PANEL — logo centrado, minimal
        ══════════════════════════════════════ */
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

        /* Cuadrícula sutil de fondo */
        .brand-panel::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(var(--surface-4) 1px, transparent 1px),
                linear-gradient(90deg, var(--surface-4) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.5;
            pointer-events: none;
            transition: background-image 0.3s ease;
        }

        /* Orb de glow detrás del logo */
        .brand-orb {
            position: absolute;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--neon-muted) 0%, transparent 68%);
            pointer-events: none;
        }

        /* Logo container */
        .logo-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 28px;
        }

        /* SVG Laravel */
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

        /* Anillos orbitales decorativos */
        .orbit-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid;
            animation: orbitSpin linear infinite;
            pointer-events: none;
        }
        .orbit-ring-1 {
            width: 150px; height: 150px;
            border-color: rgba(230,55,87,0.15);
            animation-duration: 18s;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }
        .orbit-ring-2 {
            width: 210px; height: 210px;
            border-color: rgba(230,55,87,0.08);
            animation-duration: 28s;
            animation-direction: reverse;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }
        .orbit-ring-3 {
            width: 290px; height: 290px;
            border-color: rgba(230,55,87,0.04);
            animation-duration: 40s;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes orbitSpin {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to   { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Punto orbitando en el ring externo */
        .orbit-dot {
            position: absolute;
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--neon);
            box-shadow: 0 0 8px rgba(230,55,87,0.8);
            top: -2.5px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Wordmark y tagline */
        .brand-wordmark {
            text-align: center;
            animation: fadeUp 1s ease 0.2s both;
        }
        .brand-name {
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--text-2);
        }
        .brand-tagline {
            font-size: 11px;
            color: var(--text-3);
            margin-top: 5px;
            letter-spacing: 0.05em;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Footer de panel */
        .brand-footer {
            position: absolute; bottom: 24px;
            font-family: 'Space Mono', monospace;
            font-size: 9px; color: var(--text-3);
            letter-spacing: 0.08em;
            text-align: center;
        }

        /* ══════════════════════════════════════
           RIGHT PANEL — form
        ══════════════════════════════════════ */
        .form-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 52px 48px;
            background: var(--surface-1);
            transition: background-color 0.3s ease;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
        }

        .form-eyebrow {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--neon);
            margin-bottom: 8px;
            animation: fadeUp 0.7s ease 0.05s both;
        }

        .form-title {
            font-size: 22px; font-weight: 600;
            color: var(--text-1);
            line-height: 1.2;
            letter-spacing: -0.015em;
            animation: fadeUp 0.7s ease 0.1s both;
        }

        .form-subtitle {
            font-size: 13px; color: var(--text-3);
            margin-top: 5px;
            animation: fadeUp 0.7s ease 0.15s both;
        }

        .form-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--surface-4) 40%, var(--surface-4) 60%, transparent);
            margin: 28px 0;
            animation: fadeUp 0.7s ease 0.2s both;
        }

        /* Campos */
        .field { margin-bottom: 16px; }

        .field-label {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 12px; font-weight: 500;
            color: var(--text-2);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .field-label a {
            font-size: 11.5px; color: var(--text-3);
            text-decoration: none; transition: color 0.15s;
        }
        .field-label a:hover { color: var(--neon); }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: var(--text-3); font-size: 12px;
            pointer-events: none; transition: color 0.15s;
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
            background: var(--surface-3);
            border: 1px solid var(--surface-4);
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
            background: var(--surface-1);
            border-color: var(--neon-border);
            box-shadow: 0 0 0 3px var(--neon-muted);
        }
        .input-wrap:focus-within .input-icon { color: var(--neon); }

        /* reCaptcha */
        .captcha-box {
            width: 100%;
            background: var(--surface-3);
            border: 1px dashed var(--surface-4);
            border-radius: 9px;
            height: 64px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            color: var(--text-3); font-size: 12.5px;
            margin-bottom: 20px;
            transition: border-color 0.15s;
        }
        .captcha-box:hover { border-color: var(--neon-border); }

        /* Remember */
        .remember-row {
            display: flex; align-items: center; gap: 9px;
            margin-bottom: 20px;
        }
        .checkbox-custom {
            width: 17px; height: 17px; flex-shrink: 0;
            appearance: none;
            background: var(--surface-3);
            border: 1px solid var(--surface-4);
            border-radius: 5px;
            cursor: pointer; transition: all 0.15s; position: relative;
        }
        .checkbox-custom:checked {
            background: var(--neon); border-color: var(--neon);
            box-shadow: 0 0 8px rgba(230,55,87,0.4);
        }
        .checkbox-custom:checked::after {
            content: '';
            position: absolute; left: 4px; top: 2px;
            width: 6px; height: 9px;
            border: 2px solid #fff;
            border-top: none; border-left: none;
            transform: rotate(42deg);
        }
        .remember-label { font-size: 13px; color: var(--text-2); cursor: pointer; }

        /* Alert */
        .alert-error {
            display: flex; align-items: center; gap: 10px;
            background: rgba(230,55,87,0.06);
            border: 1px solid var(--neon-border);
            border-left: 3px solid var(--neon);
            border-radius: 9px;
            padding: 11px 14px;
            margin-bottom: 18px;
            font-size: 13px; color: var(--neon);
            font-weight: 500;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            background: var(--neon);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; font-weight: 600;
            padding: 12px;
            border: none; border-radius: 9px;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(230,55,87,0.30), 0 2px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s, transform 0.15s, background 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            letter-spacing: 0.01em;
        }
        .btn-submit:hover {
            box-shadow: 0 0 30px rgba(230,55,87,0.50), 0 4px 12px rgba(0,0,0,0.2);
            transform: translateY(-1px);
            background: var(--neon-dark);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Footer */
        .form-footer {
            margin-top: 22px;
            text-align: center;
            font-size: 11.5px; color: var(--text-3);
        }
        .form-footer a { color: var(--text-2); text-decoration: none; transition: color 0.15s; }
        .form-footer a:hover { color: var(--neon); }

        /* Status bar */
        .status-bar {
            position: absolute; bottom: 20px;
            display: flex; align-items: center; gap: 7px;
            font-size: 11px; color: var(--text-3);
        }
        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 6px rgba(34,197,94,0.6);
            animation: blink 2.5s infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* Toast de Éxito / Error */
        .toast {
            position: fixed; bottom: 24px; right: 24px;
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px; border-radius: 10px;
            background: var(--surface-3); border: 1px solid var(--surface-4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            z-index: 1000;
            transform: translateY(80px); opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 360px; pointer-events: none;
        }
        .toast.show { transform: translateY(0); opacity: 1; pointer-events: auto; }

        @media (max-width: 768px) {
            .page { grid-template-columns: 1fr; }
            .brand-panel { display: none; }
            .form-panel { min-height: 100vh; padding: 40px 24px; }
        }
    </style>
</head>
<body>

<div class="page">

    <section class="brand-panel" aria-hidden="true">
        <div class="brand-orb"></div>

        <div class="orbit-ring orbit-ring-1">
            <div class="orbit-dot"></div>
        </div>
        <div class="orbit-ring orbit-ring-2"></div>
        <div class="orbit-ring orbit-ring-3"></div>

        <div class="logo-wrap">
            <svg class="laravel-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 128 133">
                <g>
                    <path fill="var(--neon)" d="
                        M 26.027344 0.136719 C 25.824219 0.214844 20.085938 3.484375 13.28125 7.394531
                        C 5.035156 12.136719 0.800781 14.632812 0.574219 14.867188
                        C 0.394531 15.070312 0.191406 15.421875 0.128906 15.644531
                        C -0.0429688 16.21875 -0.0546875 100.347656 0.117188 100.953125
                        C 0.179688 101.1875 0.382812 101.53125 0.566406 101.722656
                        C 1.011719 102.191406 50.238281 130.496094 50.835938 130.632812
                        C 51.113281 130.699219 51.425781 130.6875 51.734375 130.601562
                        C 52.40625 130.433594 101.503906 102.191406 101.941406 101.730469
                        C 102.121094 101.53125 102.324219 101.1875 102.390625 100.953125
                        C 102.476562 100.675781 102.507812 96.277344 102.507812 87.015625
                        L 102.507812 73.480469 L 114.476562 66.605469
                        C 125.761719 60.117188 126.453125 59.710938 126.742188 59.265625
                        L 127.039062 58.785156 L 127.039062 44.207031
                        C 127.039062 28.335938 127.070312 29.230469 126.441406 28.65625
                        C 126.273438 28.507812 120.523438 25.152344 113.652344 21.195312
                        L 101.171875 14.015625 L 99.785156 14.015625
                        L 87.574219 21.027344 C 80.851562 24.894531 75.136719 28.199219 74.859375 28.378906
                        C 74.582031 28.5625 74.25 28.902344 74.113281 29.148438
                        L 73.867188 29.574219 L 73.8125 43.308594 L 73.761719 57.046875
                        L 63.679688 62.855469 C 58.132812 66.042969 53.515625 68.683594 53.417969 68.707031
                        C 53.238281 68.757812 53.226562 67.449219 53.226562 42.203125
                        L 53.226562 15.632812 L 52.960938 15.175781
                        C 52.628906 14.621094 54.121094 15.507812 39.136719 6.894531
                        C 26.570312 -0.332031 26.871094 -0.179688 26.027344 0.136719 Z
                        M 37.578125 10.65625 C 42.835938 13.671875 47.136719 16.167969 47.136719 16.199219
                        C 47.136719 16.230469 42.527344 18.894531 36.894531 22.132812
                        L 26.644531 28.015625 L 16.414062 22.132812
                        C 10.792969 18.894531 6.1875 16.230469 6.1875 16.199219
                        C 6.1875 16.167969 10.785156 13.503906 16.40625 10.273438
                        L 26.613281 4.402344 L 27.316406 4.785156
                        C 27.710938 5 32.332031 7.640625 37.578125 10.65625 Z
                        M 110.730469 24.191406 C 116.265625 27.378906 120.84375 30.011719 120.886719 30.054688
                        C 121.003906 30.160156 100.703125 41.828125 100.425781 41.816406
                        C 100.148438 41.808594 80.097656 30.246094 80.105469 30.105469
                        C 80.117188 29.945312 100.289062 18.339844 100.492188 18.371094
                        C 100.585938 18.394531 105.195312 21.015625 110.730469 24.191406 Z
                        M 14.828125 25.875 L 24.585938 31.492188 L 24.640625 59.304688
                        L 24.691406 87.121094 L 24.929688 87.496094
                        C 25.054688 87.695312 25.289062 87.964844 25.460938 88.089844
                        C 25.621094 88.207031 31.050781 91.300781 37.523438 94.953125
                        L 49.28125 101.59375 L 49.28125 113.359375
                        C 49.28125 119.816406 49.238281 125.113281 49.183594 125.113281
                        C 49.140625 125.113281 38.976562 119.296875 26.601562 112.175781
                        L 4.105469 99.238281 L 4.074219 59.464844 L 4.054688 19.703125
                        L 4.554688 19.980469 C 4.84375 20.132812 9.460938 22.785156 14.828125 25.875 Z
                        M 49.28125 45.453125 L 49.28125 71.082031 L 48.886719 71.339844
                        C 48.351562 71.679688 28.8125 82.910156 28.746094 82.910156
                        C 28.714844 82.910156 28.691406 71.339844 28.691406 57.195312
                        L 28.703125 31.492188 L 38.910156 25.621094
                        C 44.523438 22.390625 49.152344 19.769531 49.207031 19.789062
                        C 49.246094 19.8125 49.28125 31.363281 49.28125 45.453125 Z
                        M 88.222656 39.558594 L 98.453125 45.441406 L 98.453125 57.101562
                        C 98.453125 68.164062 98.441406 68.757812 98.273438 68.695312
                        C 98.164062 68.652344 93.535156 66 87.980469 62.800781
                        L 77.867188 56.992188 L 77.867188 45.335938
                        C 77.867188 38.917969 77.898438 33.675781 77.929688 33.675781
                        C 77.972656 33.675781 82.601562 36.320312 88.222656 39.558594 Z
                        M 123.09375 45.261719 C 123.09375 51.644531 123.050781 56.910156 123.007812 56.960938
                        C 122.933594 57.078125 102.699219 68.738281 102.570312 68.738281
                        C 102.539062 68.738281 102.507812 63.496094 102.507812 57.078125
                        L 102.507812 45.421875 L 112.714844 39.546875
                        C 118.335938 36.320312 122.964844 33.675781 123.007812 33.675781
                        C 123.0625 33.675781 123.09375 38.886719 123.09375 45.261719 Z
                        M 86.230469 66.46875 C 94.835938 71.421875 96.320312 72.308594 96.171875 72.425781
                        C 96.074219 72.488281 92.8125 74.363281 88.929688 76.582031
                        C 85.046875 78.796875 74.988281 84.53125 66.570312 89.328125
                        L 51.273438 98.054688 L 50.785156 97.789062
                        C 47.863281 96.191406 30.910156 86.546875 30.910156 86.472656
                        C 30.902344 86.3125 75.765625 60.53125 75.945312 60.597656
                        C 76.03125 60.628906 80.660156 63.269531 86.230469 66.46875 Z
                        M 98.433594 87.558594 L 98.398438 99.238281
                        L 75.914062 112.175781 C 63.542969 119.296875 53.375 125.113281 53.324219 125.113281
                        C 53.269531 125.113281 53.226562 120.359375 53.226562 113.359375
                        L 53.226562 101.59375 L 75.765625 88.742188
                        C 88.148438 81.675781 98.324219 75.890625 98.378906 75.878906
                        C 98.421875 75.878906 98.441406 81.132812 98.433594 87.558594 Z
                    " />
                </g>
            </svg>

            <div class="brand-wordmark">
                <div class="brand-name">Proyecto</div>
                <div class="brand-tagline">Sistema Corporativo · v2.4.1</div>
            </div>
        </div>

        <div class="brand-footer">
            © <script>document.write(new Date().getFullYear())</script> Proyecto &nbsp;·&nbsp; Uso Interno
        </div>
    </section>

    <section class="form-panel">
        <div class="form-box">

            <div class="form-eyebrow">Acceso al sistema</div>
            <h2 class="form-title">Bienvenido de nuevo</h2>
            <p class="form-subtitle">Ingresa tus credenciales para continuar.</p>

            <div class="form-divider"></div>

            <div id="errorBox" class="alert-error" role="alert" style="display:none;">
                <i class="fas fa-triangle-exclamation" style="flex-shrink:0;"></i>
                <span id="errorMessage"></span>
            </div>

            @if(session('error') || $errors->has('error'))
            <div class="alert-error" role="alert">
                <i class="fas fa-triangle-exclamation" style="flex-shrink:0;"></i>
                <span>{{ session('error') ?? $errors->first('error') }}</span>
            </div>
            @endif

            <form id="loginForm" action="javascript:void(0);" novalidate>

                <div class="field">
                    <div class="field-label">
                        <label for="usuario">Usuario</label>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-user input-icon"></i>
                        <input id="usuario" name="usuario" type="text"
                            class="field-input"
                            placeholder="Ej. admin"
                            autocomplete="username" required maxlength="100">
                    </div>
                </div>

                <div class="field">
                    <div class="field-label">
                        <label for="password">Contraseña</label>
                        <a href="#" tabindex="-1">¿Olvidaste la tuya?</a>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" name="password" type="password"
                            class="field-input"
                            style="padding-right:42px;"
                            placeholder="••••••••••"
                            autocomplete="current-password" required maxlength="60">
                        <button type="button" class="input-toggle"
                            aria-label="Mostrar u ocultar contraseña"
                            onclick="
                                const i = document.getElementById('password');
                                const t = this.querySelector('i');
                                if(i.type==='password'){i.type='text';t.classList.replace('fa-eye','fa-eye-slash');}
                                else{i.type='password';t.classList.replace('fa-eye-slash','fa-eye');}
                            ">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" class="checkbox-custom">
                    <label for="remember" class="remember-label">Mantener sesión iniciada</label>
                </div>

                <div class="captcha-box" aria-label="Verificación reCAPTCHA">
                    <i class="fas fa-robot" style="font-size:18px;color:var(--text-3);"></i>
                    <div>
                        <div style="font-size:12.5px;color:var(--text-2);">No soy un robot</div>
                        <div style="font-size:10px;margin-top:2px;color:var(--text-3);">reCAPTCHA · Privacidad · Términos</div>
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

{{-- 🌗 BOTÓN FLOTANTE PARA CAMBIAR TEMA 🌗 --}}
<button onclick="toggleTheme()" class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-[var(--surface-3)] border border-[var(--surface-4)] shadow-lg flex items-center justify-center text-[var(--neon)] hover:scale-110 transition-transform z-50">
    <i id="themeIcon" class="fas fa-moon text-lg"></i>
</button>

{{-- Toast global para avisos (Como Verificación de Correo exitosa) --}}
<div id="toast" class="toast">
    <div id="toastIcon" class="flex-shrink-0"></div>
    <div>
        <p id="toastMsg" class="text-sm font-medium text-[var(--text-1)]"></p>
        <p id="toastSub" class="text-xs text-[var(--text-3)] mt-0.5"></p>
    </div>
</div>

<script>
// --- Lógica del Tema Claro/Oscuro ---
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
initTheme(); // Ejecutar al cargar la página

// --- Lógica del Toast Global ---
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
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 5000);
};

// Disparar Toast si viene un Success de Laravel (ej. "¡Correo verificado!")
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => {
        window.showToast("{{ session('success') }}", 'success');
    });
@endif

// --- Lógica del Login Blindada ---
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.querySelector('.btn-submit');
    const errorBox = document.getElementById('errorBox');
    const errorMessage = document.getElementById('errorMessage');
    const originalText = btn.innerHTML;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:13px;"></i> Validando...';
    btn.disabled = true;
    errorBox.style.display = 'none';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.usuario = data.usuario.trim(); // Limpiamos espacios accidentales
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
        try { return JSON.parse(text); }
        catch { document.open(); document.write(text); document.close(); return null; }
    })
    .then(result => {
        if (!result) return;
        
        // 1. Errores personalizados (Contraseña incorrecta, Inactivo, etc.)
        if (result.error) {
            errorMessage.textContent = result.error;
            errorBox.style.display = 'flex';
            btn.innerHTML = originalText;
            btn.disabled = false;
        } 
        // 2. Errores nativos de validación de Laravel (Campos vacíos)
        else if (result.errors) {
            // Extraemos el primer mensaje de error del objeto 'errors'
            errorMessage.textContent = Object.values(result.errors)[0][0]; 
            errorBox.style.display = 'flex';
            btn.innerHTML = originalText;
            btn.disabled = false;
        } 
        // 3. ¡Éxito! Guardamos token y datos del usuario (Permisos RBAC)
        else if (result.token) {
            const maxAge = result.expires_in ? (result.expires_in * 60) : 3600;
            document.cookie = `jwt_token=${result.token}; path=/; max-age=${maxAge}; SameSite=Strict`;
            localStorage.setItem('user_data', JSON.stringify(result.user));
            window.location.href = result.redirect;
        }
        // 4. Captura de errores inesperados del servidor
        else {
            errorMessage.textContent = result.message || "Error desconocido en el servidor.";
            errorBox.style.display = 'flex';
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        errorMessage.textContent = "Error de red. Verifica la conexión o consola.";
        errorBox.style.display = 'flex';
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.error("Detalle de fetch:", error);
    });
});
</script>
</body>
</html>