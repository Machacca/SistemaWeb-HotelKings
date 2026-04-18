<?php
// app/Models/DetalleReserva.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DetalleReserva extends Model
{
    protected $table = 'detalle_reserva';
    protected $primaryKey = 'IdDetalle';
    public $timestamps = true;
    
    protected $fillable = [
        'IdReserva',
        'IdHabitacion',
        'FechaCheckIn',
        'FechaCheckOut',
        'PrecioNoche',
        'PagosAdelantados'
    ];
    
    // Esto es IMPORTANTE - convierte las fechas automáticamente a Carbon
    protected $casts = [
        'FechaCheckIn' => 'date',
        'FechaCheckOut' => 'date',
        'PrecioNoche' => 'float',
        'PagosAdelantados' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
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
    
    // Accessor para obtener las noches
    public function getNochesAttribute()
    {
        if ($this->FechaCheckIn && $this->FechaCheckOut) {
            return $this->FechaCheckIn->diffInDays($this->FechaCheckOut);
        }
        return 0;
    }
    
    // Accessor para obtener el subtotal
    public function getSubtotalAttribute()
    {
        return $this->Noches * $this->PrecioNoche;
    }
}