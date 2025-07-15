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
        Schema::create('stock_por_sucursals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_reservado')->default(0);
            $table->integer('stock_alquilado')->default(0);
            $table->integer('stock_vendido')->default(0);
            $table->decimal('precio_venta_sucursal', 10, 2)->nullable();
            $table->decimal('precio_alquiler_sucursal', 10, 2)->nullable();
            $table->boolean('activo')->default(true);

            $table->unique(['producto_id', 'sucursal_id']);
            $table->index(['sucursal_id', 'stock_actual']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_por_sucursals');
    }
};
