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
        Schema::create('garantias_individuales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alquiler_id')->nullable();
            $table->unsignedBigInteger('evento_id')->nullable(); // Para eventos folklóricos
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('numero_garantia', 50)->unique();
            $table->integer('cantidad');
            $table->decimal('monto_garantia_unitario', 10, 2);
            $table->decimal('monto_garantia_total', 10, 2);
            $table->decimal('monto_devuelto', 10, 2)->default(0);
            $table->enum('estado_prenda', ['ENTREGADA', 'DEVUELTA', 'DAÑADA', 'PERDIDA'])->default('ENTREGADA');
            $table->enum('estado_garantia', ['ACTIVA', 'DEVUELTA_COMPLETA', 'DEVUELTA_PARCIAL', 'RETENIDA'])->default('ACTIVA');
            $table->text('observaciones_entrega')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->json('evaluacion_estado')->nullable(); // {condicion: 'buena/regular/mala', detalles: '...', fotos: []}
            $table->decimal('costo_reparacion', 10, 2)->default(0);
            $table->decimal('descuento_aplicado', 10, 2)->default(0);
            $table->datetime('fecha_entrega');
            $table->datetime('fecha_devolucion')->nullable();
            $table->unsignedBigInteger('usuario_entrega');
            $table->unsignedBigInteger('usuario_devolucion')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('alquiler_id')->references('id')->on('alquileres')->onDelete('cascade');
            $table->foreign('evento_id')->references('id')->on('eventos_folkloricos')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('usuario_entrega')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('usuario_devolucion')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['estado_prenda', 'estado_garantia']);
            $table->index(['cliente_id', 'estado_garantia']);
            $table->index(['alquiler_id']);
            $table->index(['evento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias_individuales');
    }
};