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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id('IdComprobante');
            $table->foreignId('IdReserva')->constrained('reservas', 'IdReserva');
            
            $table->string('Tipo', 50);
            $table->string('Serie', 20); 
            $table->string('Numero', 20);
            $table->string('MetodoPago', 50);
            $table->date('FechaEmision');
            $table->double('Subtotal');
            $table->double('IGV');
            $table->double('Total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
