<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('huespedes', function (Blueprint $table) {
            $table->id('IdHuesped');
            $table->string('Nombre', 100);
            $table->string('Apellido', 100);
            $table->string('TipoDocumento', 20);
            $table->string('NroDocumento', 20);
            $table->string('Email', 100)->nullable();
            $table->string('Telefono', 20)->nullable();
            $table->string('Nacionalidad', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices para búsqueda rápida
            $table->index(['Nombre', 'Apellido']);
            $table->index('NroDocumento');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('huespedes');
    }
};