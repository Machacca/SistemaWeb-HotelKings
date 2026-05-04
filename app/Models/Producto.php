<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovimientoInventario;
use App\Models\Hotel;
use App\Models\Usuario;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'IdProducto';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdHotel',
        'Nombre',
        'PrecioVenta',
        'StockMinimo',
        'StockActual',
        'categoria',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'IdProducto' => 'integer',
        'IdHotel' => 'integer',
        'PrecioVenta' => 'decimal:2',
        'StockMinimo' => 'integer',
        'StockActual' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'IdProducto', 'IdProducto');
    }

    public function consumos()
    {
        return $this->hasMany(ConsumoReserva::class, 'IdProducto', 'IdProducto');
    }

    // =============================================
    // SCOPES
    // =============================================
    
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereRaw('StockActual <= StockMinimo');
    }

    public function scopePorHotel($query, $hotelId = null)
    {
        if ($hotelId) {
            return $query->where('IdHotel', $hotelId);
        }
        return $query;
    }

    // =============================================
    // MÉTODOS
    // =============================================
    
    public function estaActivo(): bool
    {
        return $this->activo;
    }

    public function stockBajo(): bool
    {
        return $this->StockActual <= $this->StockMinimo;
    }

    public function getValorInventarioAttribute(): float
    {
        return $this->StockActual * $this->PrecioVenta;
    }

    public function getTotalComprasAttribute(): float
    {
        return $this->movimientos()->where('tipo', 'compra')->sum('cantidad');
    }

    public function getTotalVentasAttribute(): float
    {
        return $this->movimientos()->where('tipo', 'venta')->sum('cantidad');
    }

    /**
     * Registrar un movimiento de inventario y actualizar stock
     */
    public function registrarMovimiento(array $data): MovimientoInventario
    {
        // Obtener el IdUsuario actual
        $idUsuario = $data['IdUsuario'] ?? null;
        if (!$idUsuario && auth()->checkdate()) {
            $idUsuario = auth()->user()->IdUsuario;
        }
        
        $movimiento = $this->movimientos()->create([
            'tipo' => $data['tipo'],
            'cantidad' => $data['cantidad'],
            'precio_unitario' => $data['precio_unitario'] ?? null,
            'observacion' => $data['observacion'] ?? null,
            'fecha_movimiento' => $data['fecha_movimiento'] ?? now(),
            'IdUsuario' => $idUsuario,
            'IdReserva' => $data['IdReserva'] ?? null,
        ]);

        // Actualizar stock según el tipo de movimiento
        if (in_array($data['tipo'], ['compra', 'devolucion'])) {
            $this->increment('StockActual', $data['cantidad']);
        } elseif (in_array($data['tipo'], ['venta', 'retiro', 'merma'])) {
            $this->decrement('StockActual', $data['cantidad']);
        }
        // 'ajuste' no modifica stock automáticamente

        return $movimiento;
    }
}