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
        Schema::create('alquiler_detalle_componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alquiler_detalle_id')->constrained('alquiler_detalles')->onDelete('cascade');
            $table->foreignId('instancia_componente_id')->constrained('instancia_componentes')->onDelete('restrict');
            $table->foreignId('componente_id')->constrained('componentes')->onDelete('restrict');
            $table->enum('estado_devolucion', ['PENDIENTE', 'DEVUELTO', 'DANADO', 'PERDIDO'])->default('PENDIENTE');
            $table->datetime('fecha_devolucion')->nullable();
            $table->enum('estado_fisico_devolucion', ['EXCELENTE', 'BUENO', 'REGULAR', 'MALO'])->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->decimal('costo_penalizacion', 12, 2)->default(0);
            $table->foreignId('usuario_registro_devolucion')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('alquiler_detalle_id');
            $table->index('estado_devolucion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquiler_detalle_componentes');
    }
};
