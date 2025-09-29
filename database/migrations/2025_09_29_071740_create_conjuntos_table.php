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
        Schema::create('conjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_conjunto_id')->constrained('categorias_conjunto')->onDelete('restrict');
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('imagen_principal', 500)->nullable();
            $table->decimal('precio_venta_base', 12, 2)->default(0);
            $table->decimal('precio_alquiler_dia', 12, 2)->default(0);
            $table->decimal('precio_alquiler_semana', 12, 2)->default(0);
            $table->decimal('precio_alquiler_mes', 12, 2)->default(0);
            $table->enum('genero', ['MASCULINO', 'FEMENINO', 'UNISEX', 'INFANTIL'])->default('UNISEX');
            $table->integer('edad_minima')->default(0);
            $table->integer('edad_maxima')->default(100);
            $table->enum('temporada', ['VERANO', 'INVIERNO', 'TODO_ANIO'])->default('TODO_ANIO');
            $table->boolean('disponible_venta')->default(true);
            $table->boolean('disponible_alquiler')->default(true);
            $table->boolean('requiere_limpieza')->default(true);
            $table->integer('tiempo_limpieza_horas')->default(24);
            $table->decimal('peso_aproximado', 8, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_creacion')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjuntos');
    }
};
