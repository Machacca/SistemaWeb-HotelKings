<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acompanante extends Model
{
    protected $table = 'acompanantes';
    protected $primaryKey = 'IdAcompanante';
    protected $fillable = ['IdDetalleReserva', 'Nombre', 'Apellido', 'TipoDocumento', 'NroDocumento', 'Parentesco'];

    public function detalleReserva()
    {
        return $this->belongsTo(DetalleReserva::class, 'IdDetalleReserva', 'IdDetalle');
    }
}