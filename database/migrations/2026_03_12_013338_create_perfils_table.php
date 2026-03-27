<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id(); // Crea el campo 'id' autoincrementable
            
            // Los campos exactos de tu documento
            $table->string('strNombrePerfil');
            $table->boolean('bitAdministrador')->default(0); // 1 = Es Admin Total, 0 = Nivel normal
            
            $table->timestamps(); // create_at y updated_at (Laravel los exige por buenas prácticas)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfiles');
    }
};