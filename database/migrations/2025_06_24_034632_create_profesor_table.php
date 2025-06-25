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
        Schema::create('profesor', function (Blueprint $table) {
            $table->increments('codigo');  
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('titulo')->nullable(); //ing, licen, etc
            $table->string('foto', 2048)->nullable();
            $table->int('ci')->nullable();
            $table->timestamps();
            //sss
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesor');
    }
};
