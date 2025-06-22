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
            $table->double('monto');
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('estado_id');
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
