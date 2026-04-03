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
        Schema::create('acompanantes', function (Blueprint $table) {
            $table->id('IdAcompanante');
            $table->foreignId('IdDetalleReserva')->constrained('detalle_reserva', 'IdDetalle');
            
            $table->string('Nombre', 100);
            $table->string('Apellido', 100);
            $table->string('TipoDocumento', 20);
            $table->string('NroDocumento', 20);
            $table->string('Parentesco', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompanantes');
    }
};
