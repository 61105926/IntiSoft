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
        // Comentado temporalmente - tabla reservas no existe
        /*
        // Agregar campos de control temporal a reservas
        Schema::table('reservas', function (Blueprint $table) {
            $table->date('fecha_inicio_uso')->nullable()->after('fecha_vencimiento');
            $table->date('fecha_fin_uso')->nullable()->after('fecha_inicio_uso');
            $table->boolean('bloquea_stock')->default(true)->after('fecha_fin_uso');
            $table->json('configuracion_flete')->nullable()->after('bloquea_stock');
            $table->decimal('total_garantias', 10, 2)->default(0)->after('total');
        });
        */

        // Agregar campos de garantías individuales a alquileres
        Schema::table('alquileres', function (Blueprint $table) {
            $table->decimal('total_garantias_individuales', 10, 2)->default(0)->after('penalizacion');
            $table->decimal('garantias_devueltas', 10, 2)->default(0)->after('total_garantias_individuales');
            $table->decimal('garantias_retenidas', 10, 2)->default(0)->after('garantias_devueltas');
            $table->boolean('garantias_completadas')->default(false)->after('garantias_retenidas');
            $table->json('configuracion_flete')->nullable()->after('condiciones_especiales');
        });

        // Agregar campos de integración folklórica a alquileres
        Schema::table('alquileres', function (Blueprint $table) {
            $table->unsignedBigInteger('evento_folklorico_id')->nullable()->after('cliente_id');
            $table->foreign('evento_folklorico_id')->references('id')->on('eventos_folkloricos')->onDelete('set null');
        });

        // Agregar referencia de alquiler a eventos folklóricos (para vinculación bidireccional)
        Schema::table('eventos_folkloricos', function (Blueprint $table) {
            $table->boolean('genera_alquileres_automaticos')->default(false)->after('requiere_transporte');
            $table->json('configuracion_alquiler')->nullable()->after('genera_alquileres_automaticos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos_folkloricos', function (Blueprint $table) {
            $table->dropColumn(['genera_alquileres_automaticos', 'configuracion_alquiler']);
        });

        Schema::table('alquileres', function (Blueprint $table) {
            $table->dropForeign(['evento_folklorico_id']);
            $table->dropColumn([
                'evento_folklorico_id',
                'total_garantias_individuales',
                'garantias_devueltas',
                'garantias_retenidas',
                'garantias_completadas',
                'configuracion_flete'
            ]);
        });

        // Comentado temporalmente - tabla reservas no existe
        /*
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_inicio_uso',
                'fecha_fin_uso',
                'bloquea_stock',
                'configuracion_flete',
                'total_garantias'
            ]);
        });
        */
    }
};