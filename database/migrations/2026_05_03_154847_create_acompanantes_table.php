<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acompanantes', function (Blueprint $table) {
            $table->id('IdAcompanante');
            $table->foreignId('IdDetalleReserva')->constrained('detalle_reserva', 'IdDetalle')->onDelete('cascade');
            
            $table->string('Nombre', 100);
            $table->string('Apellido', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acompanantes');
    }
};