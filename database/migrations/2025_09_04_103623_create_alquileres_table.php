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
        Schema::create('alquileres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursal_id');
            $table->string('numero_contrato', 50)->unique();
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('unidad_educativa_id')->nullable();
            $table->unsignedBigInteger('garantia_id')->nullable();
            $table->unsignedBigInteger('tipo_pago_id')->default(1);
            $table->date('fecha_alquiler');
            $table->time('hora_entrega')->default('09:00:00');
            $table->date('fecha_devolucion_programada');
            $table->time('hora_devolucion_programada')->default('18:00:00');
            $table->datetime('fecha_devolucion_real')->nullable();
            $table->integer('dias_alquiler');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('impuestos', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('anticipo', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);
            $table->decimal('penalizacion', 12, 2)->default(0);
            $table->decimal('comision_vendedor', 10, 2)->default(0);
            $table->enum('estado', ['ACTIVO', 'DEVUELTO', 'VENCIDO', 'CANCELADO', 'PARCIAL'])->default('ACTIVO');
            $table->enum('estado_pago', ['PENDIENTE', 'PARCIAL', 'PAGADO', 'VENCIDO'])->default('PENDIENTE');
            $table->string('referencia_pago', 100)->nullable();
            $table->string('lugar_entrega', 200)->nullable();
            $table->string('lugar_devolucion', 200)->nullable();
            $table->text('observaciones')->nullable();
            $table->text('condiciones_especiales')->nullable();
            $table->unsignedBigInteger('usuario_creacion');
            $table->unsignedBigInteger('usuario_entrega')->nullable();
            $table->unsignedBigInteger('usuario_devolucion')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('sucursal_id')->references('id')->on('sucursals')->onDelete('restrict');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('set null');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('restrict');
            // $table->foreign('unidad_educativa_id')->references('id')->on('unidades_educativas')->onDelete('set null');
            $table->foreign('usuario_creacion')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('usuario_entrega')->references('id')->on('users')->onDelete('set null');
            $table->foreign('usuario_devolucion')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['estado', 'sucursal_id']);
            $table->index(['fecha_devolucion_programada', 'estado']);
            $table->index('estado_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquileres');
    }
};
