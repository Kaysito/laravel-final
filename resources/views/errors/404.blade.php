<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada — Proyecto</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Geist:wght@300;400;500;600;700&display=swap');

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
        }

        [data-theme="dark"] {
            --surface-1: #0a0a0f;
            --surface-2: #111118;
            --surface-3: #18181f;
            --surface-4: #22222c;
            --surface-5: #2a2a36;
            
            --text-1: #f0f0f5;
            --text-2: #a0a0b0;
            --text-3: #60607a;
        }

        * { font-family: 'Geist', system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        body { 
            background: var(--surface-1); 
            color: var(--text-1); 
            transition: background-color 0.3s ease, color 0.3s ease;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .error-card {
            background: var(--surface-2);
            border: 1px solid var(--surface-4);
            border-radius: 24px;
            padding: 3rem;
            text-align: center;
            max-width: 28rem;
            width: 90%;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2);
            position: relative;
            z-index: 10;
        }

        .glitch-text {
            font-size: 6rem;
            font-weight: 900;
            line-height: 1;
            color: var(--neon);
            text-shadow: 0 0 20px var(--neon-border);
            margin-bottom: 1rem;
        }

        /* Patrón de fondo estilo circuito */
        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(var(--surface-4) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.4;
            z-index: 0;
        }
    </style>
</head>
<body>

    <div class="bg-pattern"></div>

    <div class="error-card fade-in">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-[var(--surface-3)] border border-[var(--surface-4)] flex items-center justify-center mb-6 shadow-inner text-4xl text-[var(--text-3)]">
            <i class="fas fa-ghost"></i>
        </div>
        
        <h1 class="glitch-text font-mono">404</h1>
        
        <h2 class="text-2xl font-bold text-[var(--text-1)] mb-2">Página no encontrada</h2>
        <p class="text-sm text-[var(--text-3)] leading-relaxed mb-8">
            Parece que te has perdido en el hiperespacio. La ruta a la que intentas acceder no existe o fue movida a otro sector del sistema.
        </p>

        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 bg-[var(--neon)] hover:bg-[var(--neon-dark)] text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all hover:scale-105 w-full">
            <i class="fas fa-arrow-left"></i> Volver a zona segura
        </a>
    </div>

    {{-- Script ligerito solo para el tema oscuro --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light'; 
            document.documentElement.setAttribute('data-theme', savedTheme);
        });
    </script>
</body>
</html>