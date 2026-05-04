<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formas_pago', function (Blueprint $table) {
            $table->id('IdFormaPago');
            $table->string('Nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index('Nombre');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formas_pago');
    }
};