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
        Schema::create('consumo_reserva', function (Blueprint $table) {
            $table->id('IdConsumo');
            $table->foreignId('IdReserva')->constrained('reservas', 'IdReserva');
            $table->foreignId('IdProducto')->constrained('productos', 'IdProducto');
            
            $table->integer('Cantidad');
            $table->date('FechaConsumo');
            $table->boolean('EstadoPago')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_reserva');
    }
};
