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
        Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_garantia_id')->constrained('tipos_garantia')->onDelete('restrict');
            $table->string('numero_ticket', 50)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->text('descripcion')->nullable();
            $table->decimal('monto', 10, 2)->default(0);
            $table->string('documento_respaldo', 200)->nullable();
            $table->string('archivo_documento', 500)->nullable();
            $table->enum('estado', ['RECIBIDA', 'DEVUELTA', 'PERDIDA', 'APLICADA', 'VENCIDA'])->default('RECIBIDA');
            $table->timestamp('fecha_recepcion')->useCurrent();
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamp('fecha_devolucion')->nullable();
            $table->decimal('monto_devuelto', 10, 2)->default(0);
            $table->decimal('monto_aplicado', 10, 2)->default(0);
            $table->foreignId('usuario_recepcion')->constrained('users')->onDelete('restrict');
            $table->foreignId('usuario_devolucion')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('restrict');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['estado', 'sucursal_id']);
            $table->index(['cliente_id', 'estado']);
            $table->index(['fecha_vencimiento', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};