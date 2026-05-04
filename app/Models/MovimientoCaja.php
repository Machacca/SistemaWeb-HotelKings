<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimientos_caja';
    protected $primaryKey = 'IdMovimiento';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdUsuario',
        'IdComprobante',
        'tipo',
        'concepto',
        'monto',
        'fecha_movimiento',
        'referencia',
        'observacion',
    ];

    protected $casts = [
        'IdMovimiento' => 'integer',
        'IdUsuario' => 'integer',
        'IdComprobante' => 'integer',
        'monto' => 'decimal:2',
        'fecha_movimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'IdComprobante', 'IdComprobante');
    }

    // =============================================
    // SCOPES
    // =============================================
    
    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'egreso');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getTipoNombreAttribute(): string
    {
        return $this->tipo === 'ingreso' ? 'Ingreso' : 'Egreso';
    }

    public function getTipoBadgeClassAttribute(): string
    {
        return $this->tipo === 'ingreso' ? 'bg-success' : 'bg-danger';
    }

    public function getMontoFormateadoAttribute(): string
    {
        $signo = $this->tipo === 'ingreso' ? '+' : '-';
        return $signo . ' S/ ' . number_format($this->monto, 2);
    }
}
