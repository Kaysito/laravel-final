<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('modulos', function (Blueprint $table) {
        // ¿En qué carpeta va? (Ej: "Seguridad", "Ventas". Si es NULL, va suelto en el menú principal)
        $table->string('strGrupo')->nullable()->after('strNombreModulo'); 
        // La ruta de Laravel (Ej: "usuarios.index")
        $table->string('strRuta')->nullable()->after('strGrupo');
        // El icono de FontAwesome (Ej: "fas fa-users")
        $table->string('strIcono')->default('fas fa-cube')->after('strRuta'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            //
        });
    }
};
