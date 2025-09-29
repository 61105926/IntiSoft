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
        Schema::create('componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_componente_id')->constrained('tipos_componente')->onDelete('restrict');
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('imagen', 500)->nullable();
            $table->string('talla', 20)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('material', 100)->nullable();
            $table->enum('genero', ['MASCULINO', 'FEMENINO', 'UNISEX', 'INFANTIL'])->default('UNISEX');
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('costo_unitario', 12, 2)->nullable();
            $table->decimal('precio_venta_individual', 12, 2)->nullable();
            $table->decimal('precio_alquiler_individual', 12, 2)->nullable();
            $table->boolean('es_reutilizable')->default(true);
            $table->boolean('requiere_limpieza')->default(true);
            $table->integer('tiempo_limpieza_horas')->default(24);
            $table->integer('vida_util_usos')->default(100);
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
        Schema::dropIfExists('componentes');
    }
};
