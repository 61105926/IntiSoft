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
        Schema::create('conjunto_componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conjunto_id')->constrained('conjuntos')->onDelete('cascade');
            $table->foreignId('componente_id')->constrained('componentes')->onDelete('cascade');
            $table->integer('cantidad_requerida')->default(1);
            $table->boolean('es_obligatorio')->default(true);
            $table->boolean('es_intercambiable')->default(false);
            $table->integer('orden_ensamblaje')->default(0);
            $table->string('observaciones', 200)->nullable();
            $table->timestamps();

            $table->unique(['conjunto_id', 'componente_id'], 'unique_conjunto_componente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjunto_componentes');
    }
};
