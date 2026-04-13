<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Modulo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inyecta la variable $modulosMenu en TODAS las vistas de Laravel
        View::composer('*', function ($view) {
            // Verifica que la tabla exista para que no explote cuando hagas migraciones nuevas en el futuro
            if (Schema::hasTable('modulos')) {
                // Trae los módulos ordenados y agrupados por su carpeta (strGrupo)
                $modulosMenu = Modulo::orderBy('strGrupo')->orderBy('strNombreModulo')->get()->groupBy('strGrupo');
                $view->with('modulosMenu', $modulosMenu);
            }
        });
    }
}