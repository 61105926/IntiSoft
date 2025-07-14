<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['ENTRADA', 'SALIDA', 'AJUSTE', 'ALQUILER', 'DEVOLUCION', 'VENTA', 'TRANSFERENCIA']);
            $table->integer('cantidad');
            $table->string('motivo', 200)->nullable();
            $table->string('referencia_documento', 100)->nullable();
            $table->foreignId('usuario_responsable')->constrained('users')->nullable();
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_movimientos');
    }
};
