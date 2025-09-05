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
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caja_id');
            
            // Datos del movimiento
            $table->enum('tipo', ['INGRESO', 'EGRESO']);
            $table->decimal('monto', 12, 2);
            $table->string('concepto');
            $table->enum('categoria', [
                'VENTA', 'ALQUILER', 'PAGO_ALQUILER', 'GARANTIA', 'DEVOLUCION_GARANTIA',
                'GASTO_OPERATIVO', 'PAGO_PROVEEDOR', 'PAGO_SERVICIOS', 'PAGO_SUELDOS',
                'APERTURA', 'ARQUEO', 'TRANSFERENCIA', 'VARIOS'
            ])->default('VARIOS');
            
            $table->string('referencia')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_movimiento');
            $table->unsignedBigInteger('usuario_registro');
            
            // Saldos para auditoria
            $table->decimal('saldo_anterior', 12, 2);
            $table->decimal('saldo_posterior', 12, 2);
            
            // Referencias a otras entidades (nullable para flexibilidad)
            $table->unsignedBigInteger('alquiler_id')->nullable();
            $table->unsignedBigInteger('venta_id')->nullable();
            $table->unsignedBigInteger('garantia_id')->nullable();
            
            // Foreign keys se agregarán después si es necesario
            
            // Método de pago y documento
            $table->string('metodo_pago')->default('EFECTIVO');
            $table->string('documento_respaldo')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('caja_id');
            $table->index('tipo');
            $table->index('categoria');
            $table->index('fecha_movimiento');
            $table->index(['caja_id', 'fecha_movimiento']);
            $table->index(['caja_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};
