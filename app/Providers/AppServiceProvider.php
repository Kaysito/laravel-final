<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache; // <--- Agregamos Caché
use App\Models\Modulo;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap de servicios con alta optimización.
     */
    public function boot(): void
    {
        // 1. LIMITAR EL ALCANCE: Solo inyectamos en el layout principal. 
        // Cámbialo por 'layouts.app' si ese es el nombre exacto de tu archivo.
        View::composer('layouts.app', function ($view) {
            
            // 2. CACHÉ INTELIGENTE: Si los datos ya existen en memoria, no tocamos la DB.
            // Guardamos el menú por 24 horas (o hasta que lo limpiemos manualmente).
            $modulosMenu = Cache::remember('sidebar_modulos_menu', now()->addDay(), function () {
                
                // Verificamos la tabla una sola vez dentro de la caché
                if (Schema::hasTable('modulos')) {
                    return Modulo::orderBy('strGrupo')
                        ->orderBy('strNombreModulo')
                        ->get()
                        ->groupBy('strGrupo');
                }
                
                return collect(); // Retorna colección vacía si no hay tabla
            });

            $view->with('modulosMenu', $modulosMenu);
        });
    }
}