<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleReserva;
use App\Models\ConsumoReserva;
use App\Models\CanalReserva;
use App\Models\Huesped;
use App\Models\Comprobante;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';
    protected $primaryKey = 'IdReserva';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdCanal',
        'IdHuesped',
        'FechaReserva',
        'Estado',
        'TotalReserva',
        'observaciones',
    ];

    protected $casts = [
        'IdReserva' => 'integer',
        'IdCanal' => 'integer',
        'IdHuesped' => 'integer',
        'FechaReserva' => 'date',
        'TotalReserva' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function canal()
    {
        return $this->belongsTo(CanalReserva::class, 'IdCanal', 'IdCanal');
    }

    public function huesped()
    {
        return $this->belongsTo(Huesped::class, 'IdHuesped', 'IdHuesped');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleReserva::class, 'IdReserva', 'IdReserva');
    }

    public function consumos()
    {
        return $this->hasMany(ConsumoReserva::class, 'IdReserva', 'IdReserva');
    }

    public function comprobante()
    {
        return $this->hasOne(Comprobante::class, 'IdReserva', 'IdReserva');
    }

    // =============================================
    // SCOPES
    // =============================================
    
    public function scopeActivas($query)
    {
        return $query->whereIn('Estado', ['Reserva', 'Check-in', 'Confirmada']);
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('Estado', 'Finalizada');
    }

    public function scopeAnuladas($query)
    {
        return $query->where('Estado', 'Anulada');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('FechaReserva', [$fechaInicio, $fechaFin]);
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getEstadoNombreAttribute(): string
    {
        $estados = [
            'Reserva' => 'Reserva',
            'Confirmada' => 'Confirmada',
            'Check-in' => 'Check-in',
            'Finalizada' => 'Finalizada',
            'Anulada' => 'Anulada',
        ];
        return $estados[$this->Estado] ?? $this->Estado;
    }

    public function getEstadoBadgeClassAttribute(): string
    {
        $clases = [
            'Reserva' => 'bg-primary',
            'Confirmada' => 'bg-info',
            'Check-in' => 'bg-success',
            'Finalizada' => 'bg-secondary',
            'Anulada' => 'bg-danger',
        ];
        return $clases[$this->Estado] ?? 'bg-dark';
    }

    public function getTotalHospedajeAttribute(): float
    {
        return $this->detalles->sum(function($detalle) {
            $noches = $detalle->FechaCheckIn->diffInDays($detalle->FechaCheckOut) ?: 1;
            return $detalle->PrecioNoche * $noches;
        });
    }

    public function getTotalConsumosAttribute(): float
    {
        return $this->consumos->sum(function($consumo) {
            return $consumo->Cantidad * $consumo->PrecioVenta;
        });
    }

    public function getSubtotalAttribute(): float
    {
        return $this->total_hospedaje + $this->total_consumos;
    }

    public function getSaldoPendienteAttribute(): float
    {
        $adelantos = $this->detalles->sum('PagosAdelantados');
        $descuentos = $this->detalles->sum('Descuento');
        return max(0, $this->subtotal - $adelantos - $descuentos);
    }

    // =============================================
    // MÉTODOS
    // =============================================
    
    public function calcularTotal(): float
    {
        $total = $this->total_hospedaje + $this->total_consumos;
        $adelantos = $this->detalles->sum('PagosAdelantados');
        $descuentos = $this->detalles->sum('Descuento');
        
        return $total - $adelantos - $descuentos;
    }
}