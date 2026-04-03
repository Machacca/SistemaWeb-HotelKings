<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumoReserva extends Model
{
    protected $table = 'consumo_reserva';
    protected $primaryKey = 'IdConsumo';
    protected $fillable = ['IdReserva', 'IdProducto', 'Cantidad', 'FechaConsumo', 'EstadoPago'];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'IdReserva', 'IdReserva');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'IdProducto', 'IdProducto');
    }
}