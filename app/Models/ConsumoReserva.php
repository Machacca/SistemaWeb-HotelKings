<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumoReserva extends Model
{
    use HasFactory;

    protected $table = 'consumo_reserva';
    protected $primaryKey = 'IdConsumo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdReserva',
        'IdProducto',
        'Cantidad',
        'PrecioVenta',
        'FechaConsumo',
        'EstadoPago',
    ];

    protected $casts = [
        'IdConsumo' => 'integer',
        'IdReserva' => 'integer',
        'IdProducto' => 'integer',
        'Cantidad' => 'integer',
        'PrecioVenta' => 'decimal:2',
        'FechaConsumo' => 'date',
        'EstadoPago' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'IdReserva', 'IdReserva');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'IdProducto', 'IdProducto');
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getSubtotalAttribute(): float
    {
        return $this->Cantidad * $this->PrecioVenta;
    }

    public function getEstadoPagoNombreAttribute(): string
    {
        return $this->EstadoPago ? 'Pagado' : 'Pendiente';
    }

    public function getEstadoPagoBadgeClassAttribute(): string
    {
        return $this->EstadoPago ? 'bg-success' : 'bg-warning text-dark';
    }
}