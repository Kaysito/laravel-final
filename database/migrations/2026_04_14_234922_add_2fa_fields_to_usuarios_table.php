<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'codigo_correo')) {
                $table->string('codigo_correo')->nullable()->after('strImagen');
            }
            if (!Schema::hasColumn('usuarios', 'correo_verificado_at')) {
                $table->timestamp('correo_verificado_at')->nullable()->after('codigo_correo');
            }
            if (!Schema::hasColumn('usuarios', 'codigo_sms')) {
                $table->string('codigo_sms')->nullable()->after('correo_verificado_at');
            }
            if (!Schema::hasColumn('usuarios', 'celular_verificado_at')) {
                $table->timestamp('celular_verificado_at')->nullable()->after('codigo_sms');
            }
            if (!Schema::hasColumn('usuarios', 'google2fa_secret')) {
                $table->string('google2fa_secret')->nullable()->after('celular_verificado_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
                'codigo_correo', 
                'correo_verificado_at', 
                'codigo_sms', 
                'celular_verificado_at', 
                'google2fa_secret'
            ]);
        });
    }
};