<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->after('id')->nullable();
        });

        // Llenar la columna name con nombres + apellidos
        DB::statement("UPDATE users SET name = CONCAT(nombres, ' ', apellidos) WHERE name IS NULL");

        // Hacer la columna obligatoria después de llenarla
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};