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
        Schema::create('fletes_programados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alquiler_id')->nullable();
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->unsignedBigInteger('evento_id')->nullable();
            $table->string('numero_flete', 50)->unique();
            $table->enum('tipo_flete', ['ENTREGA', 'RECOGIDA', 'AMBOS']);
            $table->enum('estado_flete', ['PROGRAMADO', 'EN_RUTA', 'ENTREGADO', 'RECOGIDO', 'COMPLETADO', 'CANCELADO'])->default('PROGRAMADO');

            // Datos de entrega
            $table->text('direccion_entrega');
            $table->string('referencia_entrega', 200)->nullable();
            $table->datetime('fecha_entrega_programada')->nullable();
            $table->datetime('fecha_entrega_real')->nullable();
            $table->string('contacto_entrega', 100);
            $table->string('telefono_entrega', 20);

            // Datos de recogida
            $table->text('direccion_recogida')->nullable();
            $table->string('referencia_recogida', 200)->nullable();
            $table->datetime('fecha_recogida_programada')->nullable();
            $table->datetime('fecha_recogida_real')->nullable();
            $table->string('contacto_recogida', 100)->nullable();
            $table->string('telefono_recogida', 20)->nullable();

            // Costos y detalles
            $table->decimal('costo_entrega', 10, 2)->default(0);
            $table->decimal('costo_recogida', 10, 2)->default(0);
            $table->decimal('costo_total', 10, 2);
            $table->string('vehiculo_tipo', 50)->nullable();
            $table->string('conductor_nombre', 100)->nullable();
            $table->string('conductor_telefono', 20)->nullable();
            $table->text('observaciones')->nullable();
            $table->json('evidencias')->nullable(); // Fotos, firmas, etc.

            $table->unsignedBigInteger('usuario_programacion');
            $table->unsignedBigInteger('usuario_entrega')->nullable();
            $table->unsignedBigInteger('usuario_recogida')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('alquiler_id')->references('id')->on('alquileres')->onDelete('cascade');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            $table->foreign('evento_id')->references('id')->on('eventos_folkloricos')->onDelete('cascade');
            $table->foreign('usuario_programacion')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('usuario_entrega')->references('id')->on('users')->onDelete('set null');
            $table->foreign('usuario_recogida')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['estado_flete', 'fecha_entrega_programada']);
            $table->index(['fecha_recogida_programada', 'estado_flete']);
            $table->index(['tipo_flete']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fletes_programados');
    }
};