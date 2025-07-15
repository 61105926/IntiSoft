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
        Schema::create('detalle_transferencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transferencia_id')->constrained('transferencia_sucursals');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad_solicitada');
            $table->integer('cantidad_enviada')->default(0);
            $table->integer('cantidad_recibida')->default(0);
            $table->string('observaciones', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_transferencias');
    }
};
