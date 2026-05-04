<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoteles', function (Blueprint $table) {
            $table->id('IdHotel');
            $table->string('Nombre', 100);
            $table->string('codigo', 20)->nullable(); // ← QUITAR ->after('Nombre')
            $table->string('Direccion', 255)->nullable();
            $table->string('Telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoteles');
    }
};