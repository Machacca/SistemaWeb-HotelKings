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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('IdUsuario');
            $table->foreignId('IdRol')->constrained('roles', 'IdRol');
            $table->foreignId('IdHotel')->constrained('hoteles', 'IdHotel');
            
            $table->string('Username', 100)->unique();
            $table->string('PasswordHash', 255);
            $table->string('Email', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
