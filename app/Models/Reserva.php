<?php
// app/Models/Reserva.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'IdReserva';
    public $timestamps = true;
    
    protected $fillable = [
        'IdCanal',
        'IdHuesped',
        'FechaReserva',
        'Estado',
        'TotalReserva',
        'Observaciones' 
    ];
    
    protected $casts = [
        'FechaReserva' => 'datetime',
        'TotalReserva' => 'float'
    ];
    
    public function huesped()
    {
        return $this->belongsTo(Huesped::class, 'IdHuesped', 'IdHuesped');
    }
    
    public function detalles()
    {
        return $this->hasMany(DetalleReserva::class, 'IdReserva', 'IdReserva');
    }
    
    public function acompanantes()
    {
        return $this->hasManyThrough(
            Acompanante::class,
            DetalleReserva::class,
            'IdReserva',    // Foreign key en detalle_reserva
            'IdDetalleReserva', // Foreign key en acompanantes
            'IdReserva',    // Local key en reservas
            'IdDetalle'     // Local key en detalle_reserva
        );
    }
    
    public function consumos()
    {
        return $this->hasMany(ConsumoReserva::class, 'IdReserva', 'IdReserva');
    }
    
    public function getTotalAlojamientoAttribute()
    {
        return $this->detalles->sum(function($detalle) {
            return $detalle->Subtotal;
        });
    }
    
    public function getTotalConsumosAttribute()
    {
        return $this->consumos->sum('Total');
    }
    
    public function recalcularTotal()
    {
        $this->TotalReserva = $this->TotalAlojamiento + $this->TotalConsumos;
        $this->saveQuietly();
        return $this->TotalReserva;
    }
}