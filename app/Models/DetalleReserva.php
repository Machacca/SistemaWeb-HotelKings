<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    protected $table = 'detalle_reserva';
    protected $primaryKey = 'IdDetalle';
    protected $fillable = ['IdReserva', 'IdHabitacion', 'FechaCheckIn', 'FechaCheckOut', 'PrecioNoche', 'PagosAdelantados'];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'IdReserva', 'IdReserva');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'IdHabitacion', 'IdHabitacion');
    }

    public function acompanantes()
    {
        return $this->hasMany(Acompanante::class, 'IdDetalleReserva', 'IdDetalle');
    }
}