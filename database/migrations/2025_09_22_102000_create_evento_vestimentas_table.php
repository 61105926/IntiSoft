<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evento_vestimentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos_folkloricos')->onDelete('cascade');
            $table->foreignId('participante_id')->constrained('eventos_participantes')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('sucursal_id')->constrained('sucursals');
            $table->integer('cantidad')->default(1);
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_entrega')->nullable();
            $table->timestamp('fecha_devolucion')->nullable();
            $table->enum('estado_vestimenta', ['ASIGNADA', 'ENTREGADA', 'DEVUELTA', 'PERDIDA', 'DAÑADA'])->default('ASIGNADA');
            $table->text('observaciones_entrega')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evento_vestimentas');
    }
};