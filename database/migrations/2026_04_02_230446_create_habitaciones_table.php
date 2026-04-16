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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id('IdHabitacion');
            $table->foreignId('IdTipo')->constrained('tipo_habitacion', 'IdTipo');
            $table->foreignId('IdHotel')->constrained('hoteles', 'IdHotel');
            
            $table->string('Numero', 10);
            $table->string('Piso', 10);
            $table->integer('IdEstadoHabitacion')->default(1); // 1: Disponible, 2: Ocupada, 4: Mantenimiento, 3: Reservada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
