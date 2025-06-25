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
        Schema::create('factura', function (Blueprint $table) {
            $table->increments('codigo');  
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('estudiante_curso_id')->nullable();
            $table->float('monto')->nullable();
            $table->foreign('users_id')->references('id')->on('users')->nullable();
           // $table->foreign('ID_estudiante_curso')->references('id')->on('users')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura');
    }
};
