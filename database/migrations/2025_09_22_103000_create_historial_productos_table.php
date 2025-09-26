<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historial_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->enum('tipo_movimiento', ['ENTRADA', 'SALIDA', 'RESERVA', 'LIBERACION', 'ALQUILER', 'DEVOLUCION', 'VENTA', 'EVENTO', 'MANTENIMIENTO', 'AJUSTE']);
            $table->enum('referencia_tipo', ['RESERVA', 'ALQUILER', 'VENTA', 'EVENTO', 'AJUSTE', 'MANTENIMIENTO']);
            $table->bigInteger('referencia_id'); // ID de la tabla referenciada
            $table->foreignId('sucursal_id')->constrained('sucursals');
            $table->integer('cantidad_anterior');
            $table->integer('cantidad_movimiento');
            $table->integer('cantidad_posterior');
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_productos');
    }
};