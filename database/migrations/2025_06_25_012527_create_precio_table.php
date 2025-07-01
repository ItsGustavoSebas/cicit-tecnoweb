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
        Schema::create('precio', function (Blueprint $table) {
            $table->id();
            $table->double('precio');
            $table->unsignedBigInteger('tipo_estudiante_id')->nullable();
            $table->unsignedBigInteger('curso_id')->nullable();
            $table->foreign('tipo_estudiante_id')->references('codigo')->on('tipo_estudiante')->onDelete('set null');
            $table->foreign('curso_id')->references('codigo')->on('curso')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio');
    }
};
