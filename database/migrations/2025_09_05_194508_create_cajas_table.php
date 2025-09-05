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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->enum('estado', ['ABIERTA', 'CERRADA'])->default('CERRADA');
            
            // Montos
            $table->decimal('saldo_inicial', 12, 2)->default(0);
            $table->decimal('saldo_actual', 12, 2)->default(0);
            
            // Fechas de apertura y cierre
            $table->timestamp('fecha_apertura')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            
            // Usuarios responsables
            $table->foreignId('usuario_apertura')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_cierre')->nullable()->constrained('users')->onDelete('set null');
            
            // Observaciones
            $table->text('observaciones_apertura')->nullable();
            $table->text('observaciones_cierre')->nullable();
            
            // Arqueo
            $table->decimal('arqueo_sistema', 12, 2)->nullable();
            $table->decimal('arqueo_fisico', 12, 2)->nullable();
            $table->decimal('diferencia_arqueo', 12, 2)->nullable();
            
            // Flags
            $table->boolean('es_caja_principal')->default(false);
            
            $table->timestamps();
            
            // Índices
            $table->index('sucursal_id');
            $table->index('estado');
            $table->index(['sucursal_id', 'es_caja_principal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
