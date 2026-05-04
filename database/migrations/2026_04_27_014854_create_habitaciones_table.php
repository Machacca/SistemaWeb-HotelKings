<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id('IdHabitacion');
            
            $table->foreignId('IdTipo')
                  ->constrained('tipo_habitacion', 'IdTipo')
                  ->onDelete('restrict');
            
            $table->foreignId('IdHotel')
                  ->constrained('hoteles', 'IdHotel')
                  ->onDelete('cascade');
            
            $table->string('Numero', 20);
            $table->string('Piso', 10);
            $table->integer('IdEstadoHabitacion')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['IdHotel', 'Numero']);
            $table->index(['IdHotel', 'IdEstadoHabitacion']);
            $table->index('Piso');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};