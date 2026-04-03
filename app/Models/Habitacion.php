<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'habitaciones';
    protected $primaryKey = 'IdHabitacion';
    protected $fillable = ['IdTipo', 'IdHotel', 'Numero', 'Piso', 'IdEstadoHabitacion'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoHabitacion::class, 'IdTipo', 'IdTipo');
    }
}