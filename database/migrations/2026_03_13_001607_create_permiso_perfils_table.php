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
    Schema::create('permisos_perfil', function (Blueprint $table) {
        $table->id();
        // Llaves foráneas
        $table->foreignId('idModulo')->constrained('modulos');
        $table->foreignId('idPerfil')->constrained('perfiles');
        
        // Los 5 permisos que pide tu rúbrica
        $table->boolean('bitAgregar')->default(0);
        $table->boolean('bitEditar')->default(0);
        $table->boolean('bitConsulta')->default(0);
        $table->boolean('bitEliminar')->default(0);
        $table->boolean('bitDetalle')->default(0);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permiso_perfils');
    }
};
