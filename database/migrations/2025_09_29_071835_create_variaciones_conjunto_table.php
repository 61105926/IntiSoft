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
        Schema::create('variaciones_conjunto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conjunto_id')->constrained('conjuntos')->onDelete('cascade');
            $table->string('codigo_variacion', 50)->unique();
            $table->string('nombre_variacion', 200)->nullable();
            $table->string('talla', 20)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('estilo', 50)->nullable();
            $table->string('material', 100)->nullable();
            $table->decimal('precio_venta', 12, 2)->nullable();
            $table->decimal('precio_alquiler_dia', 12, 2)->nullable();
            $table->decimal('precio_alquiler_semana', 12, 2)->nullable();
            $table->decimal('precio_alquiler_mes', 12, 2)->nullable();
            $table->decimal('peso', 8, 2)->nullable();
            $table->string('imagen', 500)->nullable();
            $table->text('observaciones_variacion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variaciones_conjunto');
    }
};
