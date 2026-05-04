<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';
    protected $primaryKey = 'IdMovimiento';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdProducto',
        'IdUsuario',
        'IdReserva',
        'tipo',
        'cantidad',
        'precio_unitario',
        'observacion',
        'fecha_movimiento',
    ];

    protected $casts = [
        'IdMovimiento' => 'integer',
        'IdProducto' => 'integer',
        'IdUsuario' => 'integer',
        'IdReserva' => 'integer',
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'fecha_movimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'IdProducto', 'IdProducto');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }

    public function reserva()
    {
       return $this->belongsTo(Reserva::class, 'IdReserva', 'IdReserva');
    }

    // =============================================
    // SCOPES
    // =============================================
    
    public function scopeCompras($query)
    {
        return $query->where('tipo', 'compra');
    }

    public function scopeVentas($query)
    {
        return $query->where('tipo', 'venta');
    }

    public function scopeMermas($query)
    {
        return $query->where('tipo', 'merma');
    }

    public function scopeRetiros($query)
    {
        return $query->where('tipo', 'retiro');
    }

    public function scopeAjustes($query)
    {
        return $query->where('tipo', 'ajuste');
    }

    public function scopeDevoluciones($query)
    {
        return $query->where('tipo', 'devolucion');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    public function scopePorMes($query, $anio, $mes)
    {
        return $query->whereYear('fecha_movimiento', $anio)->whereMonth('fecha_movimiento', $mes);
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getTipoNombreAttribute(): string
    {
        $nombres = [
            'compra' => 'Compra',
            'venta' => 'Venta',
            'retiro' => 'Retiro',
            'ajuste' => 'Ajuste de Inventario',
            'merma' => 'Merma / Vencimiento',
            'devolucion' => 'Devolución',
        ];
        return $nombres[$this->tipo] ?? ucfirst($this->tipo);
    }

    public function getTipoBadgeClassAttribute(): string
    {
        $clases = [
            'compra' => 'bg-primary',
            'venta' => 'bg-success',
            'retiro' => 'bg-warning text-dark',
            'ajuste' => 'bg-info text-dark',
            'merma' => 'bg-danger',
            'devolucion' => 'bg-secondary',
        ];
        return $clases[$this->tipo] ?? 'bg-dark';
    }

    public function getCantidadFormateadaAttribute(): string
    {
        $signo = in_array($this->tipo, ['compra', 'devolucion']) ? '+' : '-';
        return $signo . abs($this->cantidad);
    }
}