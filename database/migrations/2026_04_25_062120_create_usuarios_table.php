<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('IdUsuario');
            
            $table->foreignId('IdRol')
                  ->constrained('roles', 'IdRol')
                  ->onDelete('restrict');
            
            $table->foreignId('IdHotel')
                  ->nullable()
                  ->constrained('hoteles', 'IdHotel')
                  ->onDelete('set null');
            
            $table->string('Username', 100)->unique();
            $table->string('PasswordHash', 255);
            $table->string('Email', 100)->unique();
            $table->string('remember_token', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};