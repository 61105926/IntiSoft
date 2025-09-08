<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE movimientos_caja MODIFY COLUMN categoria ENUM(
            'VENTA', 'ALQUILER', 'RESERVA', 'PAGO_ALQUILER', 'GARANTIA', 'DEVOLUCION_GARANTIA',
            'GASTO_OPERATIVO', 'PAGO_PROVEEDOR', 'PAGO_SERVICIOS', 'PAGO_SUELDOS',
            'APERTURA', 'ARQUEO', 'TRANSFERENCIA', 'VARIOS'
        ) DEFAULT 'VARIOS'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE movimientos_caja MODIFY COLUMN categoria ENUM(
            'VENTA', 'ALQUILER', 'PAGO_ALQUILER', 'GARANTIA', 'DEVOLUCION_GARANTIA',
            'GASTO_OPERATIVO', 'PAGO_PROVEEDOR', 'PAGO_SERVICIOS', 'PAGO_SUELDOS',
            'APERTURA', 'ARQUEO', 'TRANSFERENCIA', 'VARIOS'
        ) DEFAULT 'VARIOS'");
    }
};
