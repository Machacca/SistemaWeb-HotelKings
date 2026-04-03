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
        Schema::create('detalle_reserva', function (Blueprint $table) {
            $table->id('IdDetalle');
            $table->foreignId('IdReserva')->constrained('reservas', 'IdReserva')->onDelete('cascade');
            $table->foreignId('IdHabitacion')->constrained('habitaciones', 'IdHabitacion');
            
            $table->date('FechaCheckIn');
            $table->date('FechaCheckOut');
            $table->double('PrecioNoche');
            $table->double('PagosAdelantados')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_reserva');
    }
};
