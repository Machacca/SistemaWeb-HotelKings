<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'IdReserva';
    protected $fillable = ['IdCanal', 'IdHuesped', 'FechaReserva', 'Estado', 'TotalReserva'];

    public function huesped()
    {
        return $this->belongsTo(Huesped::class, 'IdHuesped', 'IdHuesped');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleReserva::class, 'IdReserva', 'IdReserva');
    }
}