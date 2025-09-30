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
        Schema::create('historial_componentes_conjunto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instancia_conjunto_id')->constrained('instancias_conjunto')->onDelete('restrict');
            $table->foreignId('componente_id')->constrained('componentes')->onDelete('restrict');
            $table->foreignId('instancia_componente_id')->nullable()->constrained('instancia_componentes')->onDelete('set null');
            $table->enum('tipo_movimiento', ['ASIGNACION_INICIAL', 'PERDIDA', 'REPOSICION', 'REEMPLAZO', 'DANO', 'DEVOLUCION']);
            $table->foreignId('producto_anterior_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->foreignId('producto_nuevo_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->foreignId('alquiler_detalle_id')->nullable()->constrained('alquiler_detalles')->onDelete('set null');
            $table->text('motivo')->nullable();
            $table->decimal('costo_reposicion', 12, 2)->default(0);
            $table->foreignId('usuario_registro')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('fecha_movimiento');
            $table->timestamp('created_at')->nullable();

            $table->index('instancia_conjunto_id');
            $table->index('tipo_movimiento');
            $table->index('fecha_movimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_componentes_conjunto');
    }
};
