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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_reserva')->unique();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->enum('tipo_reserva', ['ALQUILER', 'VENTA']);
            $table->date('fecha_reserva');
            $table->date('fecha_vencimiento');
            $table->decimal('monto_efectivo', 10, 2)->default(0);
            $table->decimal('total_estimado', 10, 2);
            $table->text('observaciones')->nullable();
            $table->foreignId('sucursal_id')->constrained('sucursals');
            $table->foreignId('usuario_creacion_id')->constrained('users');
            $table->enum('estado', ['ACTIVA', 'PROXIMA_VENCER', 'VENCIDA', 'CONFIRMADA', 'CANCELADA'])->default('ACTIVA');
            $table->timestamps();

            $table->index(['estado', 'fecha_vencimiento']);
            $table->index(['cliente_id', 'estado']);
            $table->index(['sucursal_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
