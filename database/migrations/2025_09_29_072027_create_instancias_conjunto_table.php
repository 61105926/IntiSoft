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
        Schema::create('instancias_conjunto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variacion_conjunto_id')->constrained('variaciones_conjunto')->onDelete('restrict');
            $table->string('numero_serie', 50)->unique();
            $table->string('codigo_interno', 50)->unique()->nullable();
            $table->string('lote_fabricacion', 50)->nullable();
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('restrict');
            $table->enum('estado_fisico', ['EXCELENTE', 'BUENO', 'REGULAR', 'MALO', 'FUERA_SERVICIO'])->default('BUENO');
            $table->enum('estado_disponibilidad', ['DISPONIBLE', 'ALQUILADO', 'RESERVADO', 'EN_LIMPIEZA', 'EN_REPARACION', 'VENDIDO'])->default('DISPONIBLE');
            $table->date('fecha_adquisicion')->nullable();
            $table->date('fecha_ultimo_uso')->nullable();
            $table->date('fecha_proxima_disponibilidad')->nullable();
            $table->integer('total_usos')->default(0);
            $table->decimal('total_ingresos', 12, 2)->default(0);
            $table->string('ubicacion_almacen', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_creacion')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instancias_conjunto');
    }
};
