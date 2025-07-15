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
        Schema::create('movimiento_stock_sucursals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('sucursal_id')->constrained('sucursals');
            $table->enum('tipo_movimiento', ['ENTRADA', 'SALIDA', 'AJUSTE', 'TRANSFERENCIA_ENTRADA', 'TRANSFERENCIA_SALIDA']);
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->string('referencia', 100)->nullable();
            $table->string('motivo', 200);
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_stock_sucursals');
    }
};
