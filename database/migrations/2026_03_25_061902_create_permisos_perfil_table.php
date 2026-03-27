<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos_perfil', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas (Cruza el Perfil con el Módulo)
            $table->foreignId('idPerfil')->constrained('perfiles')->onDelete('cascade');
            $table->foreignId('idModulo')->constrained('modulos')->onDelete('cascade');
            
            // Los 5 permisos de tu Matriz (Booleanos: 0 es No, 1 es Sí)
            $table->boolean('bitConsulta')->default(0);
            $table->boolean('bitAgregar')->default(0);
            $table->boolean('bitEditar')->default(0);
            $table->boolean('bitEliminar')->default(0);
            $table->boolean('bitDetalle')->default(0);
            
            $table->timestamps();

            // Evitar que un perfil tenga el mismo módulo duplicado
            $table->unique(['idPerfil', 'idModulo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos_perfil');
    }
};