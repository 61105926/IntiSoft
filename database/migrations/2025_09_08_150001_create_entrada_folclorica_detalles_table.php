<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrada_folclorica_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_folclorica_id')->constrained('entradas_folcloricas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            
            // Información del producto
            $table->string('codigo_producto', 50);
            $table->string('nombre_producto', 200);
            $table->text('descripcion_producto')->nullable();
            $table->string('talla', 20)->nullable();
            $table->string('color', 50)->nullable();
            
            // Información del participante/usuario
            $table->string('nombre_participante', 150);
            $table->string('telefono_participante', 20)->nullable();
            $table->string('talla_solicitada', 20)->nullable();
            $table->text('observaciones_participante')->nullable();
            
            // Precios y cantidades
            $table->integer('cantidad')->default(1); // Normalmente 1 traje por persona
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento_unitario', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            
            // Control de entrega y devolución
            $table->enum('estado', ['PENDIENTE', 'ENTREGADO', 'DEVUELTO', 'DEVUELTO_PARCIAL'])->default('PENDIENTE');
            $table->datetime('fecha_entrega_individual')->nullable();
            $table->datetime('fecha_devolucion_individual')->nullable();
            $table->text('observaciones_entrega')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            
            // Control de daños o penalizaciones
            $table->decimal('penalizacion', 10, 2)->default(0);
            $table->text('motivo_penalizacion')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['entrada_folclorica_id', 'estado']);
            $table->index('estado');
            $table->index('producto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrada_folclorica_detalles');
    }
};