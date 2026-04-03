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
        Schema::create('tipo_habitacion', function (Blueprint $table) {
            $table->id('IdTipo');
            $table->string('Nombre', 50);
            $table->decimal('Tarifa_base', 10, 2);
            $table->integer('Capacidad');
            $table->string('Descripcion', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_habitacion');
    }
};
