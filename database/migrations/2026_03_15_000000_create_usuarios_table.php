<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            
            // Datos de Identidad
            $table->string('strNombreUsuario')->unique();
            $table->string('strCorreo')->unique();
            $table->string('strPwd');
            
            // Datos Complementarios (MFA y CDN)
            $table->string('strNumeroCelular')->nullable();
            $table->string('strImagen')->nullable(); // Para Uploadcare
            $table->string('google2fa_secret')->nullable(); // Para Google Authenticator
            
            // Control de Estado (Soft Disable)
            $table->boolean('idEstadoUsuario')->default(1); // 1: Activo, 0: Inactivo
            
            // Llave Foránea hacia la tabla perfiles
            $table->foreignId('idPerfil')->constrained('perfiles')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};