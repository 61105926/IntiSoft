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
        Schema::table('alquileres', function (Blueprint $table) {
            // Eliminar la columna garantia_id si existe
            if (Schema::hasColumn('alquileres', 'garantia_id')) {
                $table->dropForeign(['garantia_id']);
                $table->dropColumn('garantia_id');
            }

            // Agregar campos de garantía directamente
            $table->enum('tipo_garantia', ['NINGUNA', 'CI', 'EFECTIVO', 'QR'])->default('NINGUNA')->after('cliente_id');
            $table->string('documento_garantia', 200)->nullable()->after('tipo_garantia');
            $table->decimal('monto_garantia', 10, 2)->default(0)->after('documento_garantia');
            $table->text('observaciones_garantia')->nullable()->after('monto_garantia');
            $table->enum('estado_garantia', ['PENDIENTE', 'DEVUELTA', 'APLICADA'])->default('PENDIENTE')->after('observaciones_garantia');
            $table->datetime('fecha_devolucion_garantia')->nullable()->after('estado_garantia');
            $table->decimal('monto_devuelto_garantia', 10, 2)->default(0)->after('fecha_devolucion_garantia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alquileres', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_garantia',
                'documento_garantia',
                'monto_garantia',
                'observaciones_garantia',
                'estado_garantia',
                'fecha_devolucion_garantia',
                'monto_devuelto_garantia'
            ]);

            // Restaurar garantia_id si es necesario
            $table->unsignedBigInteger('garantia_id')->nullable();
            $table->foreign('garantia_id')->references('id')->on('garantias')->onDelete('set null');
        });
    }
};
