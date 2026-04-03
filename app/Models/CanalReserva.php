<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanalReserva extends Model
{
    protected $table = 'canales_reserva';
    protected $primaryKey = 'IdCanal';
    protected $fillable = ['Nombre'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'IdCanal', 'IdCanal');
    }
}