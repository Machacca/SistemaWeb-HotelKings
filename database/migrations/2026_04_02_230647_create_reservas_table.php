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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('IdReserva');
            $table->foreignId('IdCanal')->constrained('canales_reserva', 'IdCanal');
            $table->foreignId('IdHuesped')->constrained('huespedes', 'IdHuesped');
            
            $table->date('FechaReserva');
            $table->string('Estado', 20); 
            $table->double('TotalReserva');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
