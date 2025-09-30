<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE instancias_conjunto MODIFY COLUMN estado_disponibilidad ENUM('DISPONIBLE', 'ALQUILADO', 'RESERVADO', 'EN_LIMPIEZA', 'EN_REPARACION', 'VENDIDO', 'INCOMPLETO') DEFAULT 'DISPONIBLE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE instancias_conjunto MODIFY COLUMN estado_disponibilidad ENUM('DISPONIBLE', 'ALQUILADO', 'RESERVADO', 'EN_LIMPIEZA', 'EN_REPARACION', 'VENDIDO') DEFAULT 'DISPONIBLE'");
    }
};
