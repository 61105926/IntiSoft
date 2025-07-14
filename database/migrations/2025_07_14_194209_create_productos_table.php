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
        Schema::create('productos', function (Blueprint $table) {
         $table->id();
        $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
        $table->foreignId('categoria_id')->constrained('categoria_productos')->onDelete('restrict');
        $table->string('codigo', 50)->unique();
        $table->string('nombre', 200);
        $table->text('descripcion')->nullable();
        $table->decimal('precio_venta', 10, 2)->nullable();
        $table->decimal('precio_alquiler', 10, 2)->nullable();
        $table->decimal('costo_promedio', 10, 2)->default(0);
        $table->decimal('margen_venta', 5, 2)->default(0);
        $table->decimal('margen_alquiler', 5, 2)->default(0);
        $table->string('talla', 20)->nullable();
        $table->string('color', 50)->nullable();
        $table->string('material', 100)->nullable();
        $table->boolean('disponible_venta')->default(true);
        $table->boolean('disponible_alquiler')->default(true);
        $table->integer('stock_actual')->default(0);
        $table->integer('stock_minimo')->default(1);
        $table->integer('stock_reservado')->default(0);
        $table->foreignId('usuario_creacion')->constrained('users')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
