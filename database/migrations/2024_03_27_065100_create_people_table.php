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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('names');
            $table->string('last_name');
            $table->string('second_last_name');
            $table->string('ci');
            $table->date('birthdate');
            $table->string('nationality');
            $table->string('address');
            $table->string('gender');
            $table->string('city');
            $table->string('photo')->nullable();
            $table->boolean('state')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
