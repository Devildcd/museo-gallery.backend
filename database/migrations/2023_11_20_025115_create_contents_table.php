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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('info')->nullable();
            $table->string('fecha')->nullable();
            $table->longText('detalles');
            $table->boolean('principal');
            $table->boolean('programado');
            $table->boolean('prioridad');
            $table->bigInteger('visitas')->default(0);
            $table->string('tipo');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
