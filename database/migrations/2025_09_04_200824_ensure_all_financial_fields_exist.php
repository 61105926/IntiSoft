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
        Schema::table('reservas', function (Blueprint $table) {
            // Agregar campo 'total' si no existe (reemplazo de total_estimado)
            if (!Schema::hasColumn('reservas', 'total')) {
                $table->decimal('total', 12, 2)->default(0)->after('costos_adicionales');
            }
            
            // Agregar campo 'anticipo' si no existe (reemplazo de monto_efectivo)
            if (!Schema::hasColumn('reservas', 'anticipo')) {
                $table->decimal('anticipo', 12, 2)->default(0)->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['total', 'anticipo']);
        });
    }
};
