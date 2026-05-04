<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('IdProducto');
            $table->foreignId('IdHotel')->constrained('hoteles', 'IdHotel');
            
            $table->string('Nombre', 100);
            $table->decimal('PrecioVenta', 10, 2);
            $table->integer('StockMinimo')->default(0);
            $table->integer('StockActual')->default(0);
            $table->string('categoria', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index('Nombre');
            $table->index('categoria');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};