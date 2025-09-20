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
        Schema::table('movimiento_stock_sucursals', function (Blueprint $table) {
            $table->decimal('valor_unitario', 10, 2)->default(0)->after('cantidad');
            $table->text('observaciones')->nullable()->after('motivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimiento_stock_sucursals', function (Blueprint $table) {
            $table->dropColumn(['valor_unitario', 'observaciones']);
        });
    }
};