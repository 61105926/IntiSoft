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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_venta')->unique();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_id');
            
            // Fechas
            $table->timestamp('fecha_venta');
            $table->timestamp('fecha_entrega')->nullable();
            
            // Estados
            $table->enum('estado', ['PENDIENTE', 'COMPLETADA', 'CANCELADA', 'DEVUELTA'])->default('PENDIENTE');
            $table->enum('estado_pago', ['PENDIENTE', 'PAGADO', 'PARCIAL'])->default('PENDIENTE');
            
            // Montos
            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);
            
            // Información adicional
            $table->string('metodo_pago')->default('EFECTIVO');
            $table->text('observaciones')->nullable();
            $table->string('documento_referencia')->nullable();
            
            // Campos financieros adicionales
            $table->decimal('impuestos', 12, 2)->default(0);
            $table->string('moneda', 3)->default('BOB');
            $table->decimal('tipo_cambio', 8, 4)->default(1);
            
            // Caja relacionada
            $table->unsignedBigInteger('caja_id')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('cliente_id');
            $table->index('sucursal_id');
            $table->index('usuario_id');
            $table->index('estado');
            $table->index('estado_pago');
            $table->index('fecha_venta');
            $table->index(['sucursal_id', 'fecha_venta']);
            $table->index(['cliente_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};