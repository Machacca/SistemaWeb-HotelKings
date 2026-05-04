<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('IdEmpleado');
            
            $table->foreignId('IdUsuario')
                  ->nullable()
                  ->constrained('usuarios', 'IdUsuario')
                  ->onDelete('set null');
            
            $table->foreignId('IdHotel')
                  ->constrained('hoteles', 'IdHotel')
                  ->onDelete('cascade');
            
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('puesto', 100);
            $table->date('fecha_ingreso');
            $table->string('tipo_documento', 20)->nullable(); // DNI, CE, PAS
            $table->string('numero_documento', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email_personal', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['nombres', 'apellidos']);
            $table->index('numero_documento');
            $table->index('puesto');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};