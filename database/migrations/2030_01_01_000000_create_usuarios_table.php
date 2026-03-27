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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('strNombreUsuario')->unique();
            $table->string('strPwd');
            $table->boolean('idEstadoUsuario')->default(1); // 1 = Activo, 0 = Inactivo
            
            // 👇 NUEVOS CAMPOS DE LA RÚBRICA 👇
            $table->foreignId('idPerfil')->constrained('perfiles'); // Llave foránea a perfiles
            $table->string('strCorreo')->unique();
            $table->string('strNumeroCelular');
            $table->string('strImagen')->nullable(); // Para guardar la URL de Uploadcare
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};