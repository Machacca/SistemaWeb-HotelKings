<?php
// app/Models/Acompanante.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acompanante extends Model
{
    protected $table = 'acompanantes';
    protected $primaryKey = 'IdAcompanante';
    public $timestamps = true;
    
    protected $fillable = [
        'IdDetalleReserva',
        'Nombre',
        'Apellido',
        'TipoDocumento',
        'NroDocumento',
        'Parentesco'
    ];
    
    // Relación con DetalleReserva
    public function detalleReserva()
    {
        return $this->belongsTo(DetalleReserva::class, 'IdDetalleReserva', 'IdDetalle');
    }
    
    // Acceso a la reserva a través del detalle
    public function reserva()
    {
        return $this->detalleReserva ? $this->detalleReserva->reserva : null;
    }
    
    // Obtener la reserva como atributo
    public function getReservaAttribute()
    {
        return $this->detalleReserva?->reserva;
    }
    
    // Nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->Nombre} {$this->Apellido}");
    }
    
    // Parentesco con formato
    public function getParentescoLabelAttribute()
    {
        return $this->Parentesco ?? 'No especificado';
    }
}