<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos_folkloricos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_evento', 50)->unique();
            $table->string('nombre_evento', 255);
            $table->text('descripcion')->nullable();
            $table->enum('tipo_evento', ['FESTIVAL', 'CONCURSO', 'PRESENTACION', 'DESFILE', 'ESCOLAR', 'UNIVERSITARIO']);
            $table->string('institucion_organizadora', 255)->nullable();
            $table->date('fecha_evento');
            $table->time('hora_evento')->nullable();
            $table->string('lugar_evento', 255);
            $table->text('direccion_evento')->nullable();
            $table->integer('numero_participantes')->default(0);
            $table->decimal('costo_por_participante', 10, 2)->nullable();
            $table->decimal('total_estimado', 10, 2)->nullable();
            $table->decimal('total_real', 10, 2)->default(0);
            $table->enum('estado', ['PLANIFICADO', 'CONFIRMADO', 'EN_CURSO', 'FINALIZADO', 'CANCELADO'])->default('PLANIFICADO');
            $table->boolean('requiere_transporte')->default(false);
            $table->text('observaciones')->nullable();
            $table->foreignId('sucursal_id')->constrained('sucursals');
            $table->foreignId('usuario_creacion_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos_folkloricos');
    }
};