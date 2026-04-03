<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    protected $table = 'huespedes';
    protected $primaryKey = 'IdHuesped';
    protected $fillable = ['Nombre', 'Apellido', 'TipoDocumento', 'NroDocumento', 'Email', 'Telefono', 'Nacionalidad'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'IdHuesped', 'IdHuesped');
    }
}