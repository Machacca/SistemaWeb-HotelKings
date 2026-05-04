<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumo_reserva', function (Blueprint $table) {
            $table->id('IdConsumo');
            $table->foreignId('IdReserva')->constrained('reservas', 'IdReserva')->onDelete('cascade');
            $table->foreignId('IdProducto')->constrained('productos', 'IdProducto');
            
            $table->integer('Cantidad');
            $table->decimal('PrecioVenta', 10, 2);
            $table->date('FechaConsumo');
            $table->boolean('EstadoPago')->default(false);
            $table->timestamps();
            
            // Índices
            $table->index('EstadoPago');
            $table->index('FechaConsumo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumo_reserva');
    }
};