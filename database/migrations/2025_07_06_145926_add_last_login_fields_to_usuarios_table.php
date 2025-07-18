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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('ultimo_inicio_sesion')->nullable()->after('tipo_usuario');
            $table->string('ip_ultimo_inicio_sesion', 45)->nullable()->after('ultimo_inicio_sesion');
            $table->string('estado', 20)->default('activo')->after('ip_ultimo_inicio_sesion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
                'ultimo_inicio_sesion',
                'ip_ultimo_inicio_sesion',
                'estado'
            ]);
        });
    }
};
