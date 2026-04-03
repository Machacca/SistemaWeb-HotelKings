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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('IdProducto');
            $table->foreignId('IdHotel')->constrained('hoteles', 'IdHotel');
            
            $table->string('Nombre', 100);
            $table->double('PrecioVenta');
            $table->integer('StockMinimo')->default(0);
            $table->integer('StockActual')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
