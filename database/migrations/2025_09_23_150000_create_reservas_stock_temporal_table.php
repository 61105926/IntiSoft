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
        Schema::create('reservas_stock_temporal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->integer('cantidad_reservada');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['ACTIVA', 'LIBERADA', 'CONFIRMADA', 'VENCIDA'])->default('ACTIVA');
            $table->datetime('fecha_liberacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('sucursal_id')->references('id')->on('sucursals')->onDelete('cascade');

            // Indexes para optimizar consultas de disponibilidad
            $table->index(['producto_id', 'sucursal_id', 'fecha_inicio', 'fecha_fin'], 'idx_stock_temporal_disponibilidad');
            $table->index(['estado', 'fecha_fin'], 'idx_stock_temporal_estado');
            $table->unique(['reserva_id', 'producto_id', 'sucursal_id'], 'uk_stock_temporal_reserva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_stock_temporal');
    }
};