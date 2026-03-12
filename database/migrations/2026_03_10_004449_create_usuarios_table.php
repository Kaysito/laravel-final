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
            
            // Campos exactos de tu especificación
            $table->string('strNombreUsuario')->unique();
            $table->unsignedBigInteger('idPerfil')->nullable(); // Nullable por ahora para poder probar el login
            $table->string('strPwd'); 
            $table->boolean('idEstadoUsuario')->default(1); // 1 es Activo, 0 es Inactivo
            $table->string('strCorreo')->unique();
            $table->string('strNumeroCelular')->nullable();
            
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