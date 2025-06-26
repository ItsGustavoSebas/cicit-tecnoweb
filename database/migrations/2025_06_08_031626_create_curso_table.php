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
        Schema::create('curso', function (Blueprint $table) {
            $table->increments('codigo');  
            $table->string('nombre')->nullable();
            $table->string('duracion')->nullable();
            $table->integer('cupo')->nullable();
            $table->boolean('presencial')->nullable();
            $table->unsignedBigInteger('profesor_id')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();

            $table->foreign('profesor_id')->references('codigo')->on('profesor')->nullable();
            $table->foreign('users_id')->references('id')->on('users')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
