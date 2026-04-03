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
        Schema::create('huespedes', function (Blueprint $table) {
            $table->id('IdHuesped');
            $table->string('Nombre', 100);
            $table->string('Apellido', 100);
            $table->string('TipoDocumento', 20);
            $table->string('NroDocumento', 20);
            $table->string('Email', 100)->nullable();
            $table->string('Telefono', 100)->nullable();
            $table->string('Nacionalidad', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huespedes');
    }
};
