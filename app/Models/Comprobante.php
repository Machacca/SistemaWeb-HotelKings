<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobantes';
    protected $primary_key = 'IdComprobante';

    protected $fillable = [
        'IdReserva',
        'TipoComprobante', 
        'Serie',          
        'Correlativo',     
        'FechaEmision',
        'Subtotal',
        'IGV',             
        'Total',
        'Estado'           
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'IdReserva', 'IdReserva');
    }
}