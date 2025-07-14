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
        Schema::create('unidad_educativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('nombre', 200);
            $table->string('codigo', 20)->unique()->nullable();
            $table->text('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contacto_responsable', 150)->nullable();
            $table->string('cargo_responsable', 100)->nullable();
            $table->enum('tipo', ['COLEGIO', 'UNIVERSIDAD', 'INSTITUTO', 'ACADEMIA', 'OTRO'])->default('COLEGIO');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_educativas');
    }
};
