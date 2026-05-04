<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_reserva', function (Blueprint $table) {
            $table->id('IdDetalle');
            $table->foreignId('IdReserva')->constrained('reservas', 'IdReserva')->onDelete('cascade');
            $table->foreignId('IdHabitacion')->constrained('habitaciones', 'IdHabitacion');
            
            $table->date('FechaCheckIn');
            $table->date('FechaCheckOut');
            $table->decimal('PrecioNoche', 10, 2);
            $table->decimal('PagosAdelantados', 10, 2)->default(0);
            $table->decimal('Descuento', 10, 2)->default(0);
            $table->timestamps();
            
            // Índices
            $table->index(['FechaCheckIn', 'FechaCheckOut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_reserva');
    }
};