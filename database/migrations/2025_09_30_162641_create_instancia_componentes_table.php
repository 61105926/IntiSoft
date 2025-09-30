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
        Schema::create('instancia_componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instancia_conjunto_id')->constrained('instancias_conjunto')->onDelete('restrict');
            $table->foreignId('componente_id')->constrained('componentes')->onDelete('restrict');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->string('numero_serie_componente', 100)->nullable()->unique();
            $table->enum('estado_fisico', ['EXCELENTE', 'BUENO', 'REGULAR', 'MALO'])->default('BUENO');
            $table->enum('estado_actual', ['ASIGNADO', 'PERDIDO', 'DANADO', 'REEMPLAZADO'])->default('ASIGNADO');
            $table->datetime('fecha_asignacion');
            $table->datetime('fecha_desvinculacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_asignacion')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['instancia_conjunto_id', 'componente_id']);
            $table->index('estado_actual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instancia_componentes');
    }
};
