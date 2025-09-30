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
        Schema::table('alquiler_detalles', function (Blueprint $table) {
            // Hacer producto_id nullable ya que ahora solo usaremos conjuntos
            $table->unsignedBigInteger('producto_id')->nullable()->change();

            // Agregar campos para conjuntos si no existen
            if (!Schema::hasColumn('alquiler_detalles', 'conjunto_id')) {
                $table->foreignId('conjunto_id')->nullable()->after('producto_id')->constrained('conjuntos')->onDelete('restrict');
            }

            if (!Schema::hasColumn('alquiler_detalles', 'instancia_conjunto_id')) {
                $table->foreignId('instancia_conjunto_id')->nullable()->after('conjunto_id')->constrained('instancias_conjunto')->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alquiler_detalles', function (Blueprint $table) {
            // Revertir producto_id a NOT NULL
            $table->unsignedBigInteger('producto_id')->nullable(false)->change();

            // Eliminar campos de conjuntos
            if (Schema::hasColumn('alquiler_detalles', 'instancia_conjunto_id')) {
                $table->dropForeign(['instancia_conjunto_id']);
                $table->dropColumn('instancia_conjunto_id');
            }

            if (Schema::hasColumn('alquiler_detalles', 'conjunto_id')) {
                $table->dropForeign(['conjunto_id']);
                $table->dropColumn('conjunto_id');
            }
        });
    }
};
