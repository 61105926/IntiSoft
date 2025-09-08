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
        Schema::table('productos', function (Blueprint $table) {
            $table->string('imagen_principal', 500)->nullable()->after('material');
            $table->json('imagenes_adicionales')->nullable()->after('imagen_principal');
            $table->string('codigo_barras', 100)->nullable()->after('codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['imagen_principal', 'imagenes_adicionales', 'codigo_barras']);
        });
    }
};
