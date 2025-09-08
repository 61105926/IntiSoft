<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas_folcloricas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_entrada', 50)->unique();
            $table->foreignId('sucursal_id')->constrained('sucursals');
            
            // Información del evento
            $table->string('nombre_evento', 200);
            $table->text('descripcion_evento')->nullable();
            $table->date('fecha_evento');
            $table->time('hora_evento')->default('19:00:00');
            $table->string('lugar_evento', 300);
            
            // Información del contacto/responsable
            $table->foreignId('cliente_responsable_id')->constrained('clientes');
            $table->string('contacto_nombre', 150);
            $table->string('contacto_telefono', 20);
            $table->string('contacto_email', 150)->nullable();
            
            // Fechas de alquiler
            $table->date('fecha_entrega');
            $table->time('hora_entrega')->default('09:00:00');
            $table->date('fecha_devolucion_programada');
            $table->time('hora_devolucion_programada')->default('18:00:00');
            $table->datetime('fecha_devolucion_real')->nullable();
            
            // Información financiera del grupo
            $table->integer('cantidad_participantes');
            $table->decimal('subtotal_general', 12, 2);
            $table->decimal('descuento_general', 12, 2)->default(0);
            $table->decimal('total_general', 12, 2);
            $table->decimal('anticipo_total', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2);
            
            // Garantías
            $table->decimal('monto_garantia_individual', 10, 2); // Monto fijo por persona
            $table->decimal('total_garantias', 12, 2); // Total de todas las garantías
            $table->decimal('garantias_devueltas', 12, 2)->default(0);
            $table->decimal('garantias_pendientes', 12, 2);
            
            // Estados y control
            $table->enum('estado', ['ACTIVO', 'DEVUELTO_PARCIAL', 'DEVUELTO_COMPLETO', 'VENCIDO', 'CANCELADO'])->default('ACTIVO');
            $table->enum('estado_pago', ['PENDIENTE', 'PARCIAL', 'PAGADO'])->default('PENDIENTE');
            $table->enum('estado_garantias', ['PENDIENTE', 'PARCIAL', 'DEVUELTO_COMPLETO'])->default('PENDIENTE');
            
            // Observaciones y condiciones
            $table->text('observaciones')->nullable();
            $table->text('condiciones_especiales')->nullable();
            
            // Usuarios de control
            $table->foreignId('usuario_creacion')->constrained('users');
            $table->foreignId('usuario_entrega')->nullable()->constrained('users');
            $table->foreignId('usuario_devolucion')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Índices
            $table->index(['estado', 'sucursal_id']);
            $table->index(['fecha_evento', 'estado']);
            $table->index(['fecha_devolucion_programada', 'estado']);
            $table->index('estado_pago');
            $table->index('estado_garantias');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas_folcloricas');
    }
};