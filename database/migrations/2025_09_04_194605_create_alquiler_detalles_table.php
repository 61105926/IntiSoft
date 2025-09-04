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
        Schema::create('alquiler_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alquiler_id')->constrained('alquileres')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->enum('estado_devolucion', ['PENDIENTE', 'DEVUELTO', 'DAÑADO', 'PERDIDO'])->default('PENDIENTE');
            $table->text('observaciones_devolucion')->nullable();
            $table->datetime('fecha_devolucion')->nullable();
            $table->decimal('costo_daño', 12, 2)->default(0);
            $table->timestamps();

            // Índices
            $table->index(['alquiler_id', 'producto_id']);
            $table->index('estado_devolucion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquiler_detalles');
    }
};
