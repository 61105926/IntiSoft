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
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('producto_id');
            
            // Cantidad y precios
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento_unitario', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            
            // Información del producto en el momento de la venta
            $table->string('nombre_producto');
            $table->string('codigo_producto')->nullable();
            
            // Estado del item
            $table->enum('estado', ['ACTIVO', 'CANCELADO'])->default('ACTIVO');
            
            $table->timestamps();
            
            // Índices
            $table->index('venta_id');
            $table->index('producto_id');
            $table->index(['venta_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
    }
};