<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('IdMovimiento');
            
            $table->foreignId('IdProducto')->constrained('productos', 'IdProducto');
            $table->foreignId('IdUsuario')->constrained('usuarios', 'IdUsuario');
            $table->foreignId('IdReserva')->nullable()->constrained('reservas', 'IdReserva')->onDelete('set null');
            
            $table->enum('tipo', [
                'compra',      // Ingreso por compra a proveedor
                'venta',       // Salida por consumo en reserva
                'retiro',      // Salida por uso interno (staff, cortesía)
                'ajuste',      // Corrección por inventario físico
                'merma',       // Salida por vencimiento o daño
                'devolucion'   // Ingreso por devolución de cliente
            ]);
            
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2)->nullable(); // Para compras
            $table->text('observacion')->nullable();
            $table->date('fecha_movimiento');
            $table->timestamps();
            
            // Índices
            $table->index('tipo');
            $table->index('fecha_movimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};