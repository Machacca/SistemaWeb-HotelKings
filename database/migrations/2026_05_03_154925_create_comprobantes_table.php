<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id('IdComprobante');
            $table->foreignId('IdReserva')->constrained('reservas', 'IdReserva');
            $table->foreignId('IdFormaPago')->constrained('formas_pago', 'IdFormaPago');
            $table->foreignId('IdUsuario')->constrained('usuarios', 'IdUsuario');
            
            $table->string('Tipo', 20); // Boleta, Factura
            $table->string('Serie', 10);
            $table->string('Numero', 20);
            $table->date('FechaEmision');
            $table->decimal('Subtotal', 10, 2);
            $table->decimal('IGV', 10, 2);
            $table->decimal('Total', 10, 2);
            $table->timestamps();
            
            // Índices
            $table->unique(['Serie', 'Numero']);
            $table->index('FechaEmision');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};