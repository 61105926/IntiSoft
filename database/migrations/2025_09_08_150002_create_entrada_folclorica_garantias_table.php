<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrada_folclorica_garantias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_folclorica_id')->constrained('entradas_folcloricas')->onDelete('cascade');
            $table->foreignId('entrada_detalle_id')->constrained('entrada_folclorica_detalles')->onDelete('cascade');
            
            // Información de la garantía individual
            $table->string('numero_garantia', 50)->unique();
            $table->string('nombre_participante', 150); // Denormalizado para facilidad
            $table->string('telefono_participante', 20)->nullable();
            $table->string('documento_identidad', 20)->nullable(); // CI del participante
            
            // Información financiera de la garantía
            $table->decimal('monto_garantia', 10, 2);
            $table->decimal('monto_disponible', 10, 2); // Lo que se puede devolver
            $table->decimal('monto_usado', 10, 2)->default(0); // Para penalizaciones
            $table->decimal('monto_devuelto', 10, 2)->default(0);
            
            // Estados y fechas
            $table->enum('estado', ['ACTIVA', 'DEVUELTA_PARCIAL', 'DEVUELTA_COMPLETA', 'APLICADA'])->default('ACTIVA');
            $table->datetime('fecha_creacion_garantia');
            $table->datetime('fecha_devolucion_garantia')->nullable();
            
            // Información de pago de la garantía
            $table->enum('metodo_pago', ['EFECTIVO', 'TRANSFERENCIA', 'TARJETA', 'QR'])->default('EFECTIVO');
            $table->string('referencia_pago', 100)->nullable();
            
            // Observaciones y control
            $table->text('observaciones_creacion')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->text('motivo_uso_garantia')->nullable(); // Si se usa para penalizaciones
            
            // Control de usuarios
            $table->foreignId('usuario_creacion')->constrained('users');
            $table->foreignId('usuario_devolucion')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Índices
            $table->index(['entrada_folclorica_id', 'estado']);
            $table->index('estado');
            $table->index('numero_garantia');
            $table->index('documento_identidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrada_folclorica_garantias');
    }
};