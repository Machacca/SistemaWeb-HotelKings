<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('hoteles', function (Blueprint $table) {
            $table->id('IdHotel'); 
            $table->string('Nombre', 100);
            $table->string('Direccion', 200);
            $table->string('RUC', 20);
            $table->date('Configuracion')->nullable();
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoteles');
    }
};
