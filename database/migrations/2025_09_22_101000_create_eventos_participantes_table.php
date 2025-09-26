<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos_folkloricos')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('garantia_id')->nullable()->constrained('garantias');
            $table->string('numero_participante', 50);
            $table->string('nombre_completo', 255);
            $table->string('cedula', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('edad')->nullable();
            $table->enum('talla_general', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);
            $table->text('observaciones_especiales')->nullable();
            $table->decimal('monto_garantia', 10, 2);
            $table->decimal('monto_participacion', 10, 2);
            $table->enum('estado_pago', ['PENDIENTE', 'PARCIAL', 'PAGADO'])->default('PENDIENTE');
            $table->enum('estado_participante', ['REGISTRADO', 'CONFIRMADO', 'VESTIMENTA_ASIGNADA', 'FINALIZADO', 'CANCELADO'])->default('REGISTRADO');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();

            $table->unique(['evento_id', 'numero_participante'], 'unique_participante_evento');
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos_participantes');
    }
};