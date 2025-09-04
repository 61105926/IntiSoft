<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Si existen los campos antiguos, primero copiar datos a los nuevos campos
            if (Schema::hasColumn('reservas', 'monto_efectivo') && Schema::hasColumn('reservas', 'anticipo')) {
                DB::statement('UPDATE reservas SET anticipo = COALESCE(monto_efectivo, 0) WHERE anticipo = 0');
            }
            
            if (Schema::hasColumn('reservas', 'total_estimado') && Schema::hasColumn('reservas', 'total')) {
                DB::statement('UPDATE reservas SET total = COALESCE(total_estimado, 0) WHERE total = 0');
            }

            // Eliminar campos antiguos si existen
            $columnsToRemove = [];
            if (Schema::hasColumn('reservas', 'monto_efectivo')) {
                $columnsToRemove[] = 'monto_efectivo';
            }
            if (Schema::hasColumn('reservas', 'total_estimado')) {
                $columnsToRemove[] = 'total_estimado';
            }
            
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Restaurar campos antiguos para rollback
            if (!Schema::hasColumn('reservas', 'monto_efectivo')) {
                $table->decimal('monto_efectivo', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('reservas', 'total_estimado')) {
                $table->decimal('total_estimado', 10, 2)->default(0);
            }
        });

        // Copiar datos de vuelta
        Schema::table('reservas', function (Blueprint $table) {
            DB::statement('UPDATE reservas SET monto_efectivo = COALESCE(anticipo, 0)');
            DB::statement('UPDATE reservas SET total_estimado = COALESCE(total, 0)');
        });
    }
};
