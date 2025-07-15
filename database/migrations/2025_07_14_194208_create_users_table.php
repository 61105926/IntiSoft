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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('ultimo_login')->nullable();
            $table->integer('intentos_fallidos')->default(0);
            $table->timestamp('bloqueado_hasta')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
