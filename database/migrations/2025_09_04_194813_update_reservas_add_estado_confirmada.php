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
        Schema::table('reservas', function (Blueprint $table) {
            // Modificar el enum para incluir CONFIRMADA
            $table->enum('estado', ['ACTIVA', 'PROXIMA_VENCER', 'VENCIDA', 'CONFIRMADA', 'CANCELADA'])->default('ACTIVA')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Revertir al enum original
            $table->enum('estado', ['ACTIVA', 'PROXIMA_VENCER', 'VENCIDA', 'CANCELADA'])->default('ACTIVA')->change();
        });
    }
};
