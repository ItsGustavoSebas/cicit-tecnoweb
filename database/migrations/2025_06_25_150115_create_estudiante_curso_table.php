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
        Schema::create('estudiante_curso', function (Blueprint $table) {
            $table->increments('codigo');  
            $table->double('monto')->nullable();
            $table->unsignedBigInteger('estudiante_id')->nullable();
            $table->unsignedBigInteger('curso_id')->nullable();
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->unsignedBigInteger('factura_id')->nullable();

            $table->foreign('estudiante_id')->references('codigo')->on('estudiante')->nullable();
            $table->foreign('curso_id')->references('codigo')->on('curso')->nullable();
            $table->foreign('estado_id')->references('codigo')->on('estado')->nullable();
            $table->foreign('factura_id')->references('codigo')->on('factura')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiante_curso');
    }
};
