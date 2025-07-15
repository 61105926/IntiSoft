<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transferencia_sucursals', function (Blueprint $table) {
            $table->id();
            $table->string('numero_transferencia', 50)->unique();
            $table->foreignId('sucursal_origen_id')->constrained('sucursals');
            $table->foreignId('sucursal_destino_id')->constrained('sucursals');
            $table->foreignId('usuario_solicita_id')->constrained('users');
            $table->foreignId('usuario_autoriza_id')->nullable()->constrained('users');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_autorizacion')->nullable();
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_recepcion')->nullable();
            $table->enum('estado', ['SOLICITADA', 'AUTORIZADA', 'ENVIADA', 'RECIBIDA', 'CANCELADA'])->default('SOLICITADA');
            $table->text('observaciones')->nullable();
            $table->string('motivo', 200);

            $table->index(['estado', 'fecha_solicitud']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferencia_sucursals');
    }
};
