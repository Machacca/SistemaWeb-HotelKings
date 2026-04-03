<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    protected $table = 'tipo_habitacion';
    protected $primaryKey = 'IdTipo';
    protected $fillable = ['Nombre', 'Tarifa_base', 'Capacidad', 'Descripcion'];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'IdTipo', 'IdTipo');
    }
}