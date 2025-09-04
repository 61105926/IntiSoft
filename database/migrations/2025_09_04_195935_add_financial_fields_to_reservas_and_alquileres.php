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
        // Agregar campos financieros a reservas
        Schema::table('reservas', function (Blueprint $table) {
            if (!Schema::hasColumn('reservas', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('total_estimado');
            }
            if (!Schema::hasColumn('reservas', 'descuento')) {
                $table->decimal('descuento', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('reservas', 'impuestos')) {
                $table->decimal('impuestos', 12, 2)->default(0)->after('descuento');
            }
            if (!Schema::hasColumn('reservas', 'costos_adicionales')) {
                $table->decimal('costos_adicionales', 12, 2)->default(0)->after('impuestos');
            }
            if (!Schema::hasColumn('reservas', 'detalle_costos_adicionales')) {
                $table->text('detalle_costos_adicionales')->nullable()->after('costos_adicionales');
            }
        });

        // Agregar campos financieros a alquileres
        Schema::table('alquileres', function (Blueprint $table) {
            if (!Schema::hasColumn('alquileres', 'costos_adicionales')) {
                $table->decimal('costos_adicionales', 12, 2)->default(0)->after('impuestos');
            }
            if (!Schema::hasColumn('alquileres', 'detalle_costos_adicionales')) {
                $table->text('detalle_costos_adicionales')->nullable()->after('costos_adicionales');
            }
            if (!Schema::hasColumn('alquileres', 'deposito_garantia')) {
                $table->decimal('deposito_garantia', 12, 2)->default(0)->after('detalle_costos_adicionales');
            }
            if (!Schema::hasColumn('alquileres', 'deposito_devuelto')) {
                $table->decimal('deposito_devuelto', 12, 2)->default(0)->after('deposito_garantia');
            }
            if (!Schema::hasColumn('alquileres', 'requiere_deposito')) {
                $table->boolean('requiere_deposito')->default(false)->after('deposito_devuelto');
            }
            if (!Schema::hasColumn('alquileres', 'anticipo_reserva')) {
                $table->decimal('anticipo_reserva', 12, 2)->default(0)->after('anticipo');
            }
            if (!Schema::hasColumn('alquileres', 'ajuste_conversion')) {
                $table->decimal('ajuste_conversion', 12, 2)->default(0)->after('anticipo_reserva');
            }
            if (!Schema::hasColumn('alquileres', 'motivo_ajuste')) {
                $table->text('motivo_ajuste')->nullable()->after('ajuste_conversion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal', 'descuento', 'impuestos', 
                'costos_adicionales', 'detalle_costos_adicionales'
            ]);
        });

        Schema::table('alquileres', function (Blueprint $table) {
            $table->dropColumn([
                'costos_adicionales', 'detalle_costos_adicionales',
                'deposito_garantia', 'deposito_devuelto', 'requiere_deposito',
                'anticipo_reserva', 'ajuste_conversion', 'motivo_ajuste'
            ]);
        });
    }
};
