<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hoteles';
    protected $primaryKey = 'IdHotel';
    protected $fillable = ['Nombre', 'Direccion', 'RUC', 'Configuracion'];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'IdHotel', 'IdHotel');
    }
}