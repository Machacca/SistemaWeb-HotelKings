<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id('IdMovimiento');
            
            $table->foreignId('IdUsuario')
                  ->constrained('usuarios', 'IdUsuario');
            
            $table->foreignId('IdComprobante')
                  ->nullable()
                  ->constrained('comprobantes', 'IdComprobante')
                  ->onDelete('set null');
            
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->string('concepto', 200);
            $table->decimal('monto', 10, 2);
            $table->date('fecha_movimiento');
            $table->string('referencia', 100)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('tipo');
            $table->index('fecha_movimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};