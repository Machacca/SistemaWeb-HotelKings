<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';
    protected $primaryKey = 'IdComprobante';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdReserva',
        'IdFormaPago',
        'IdUsuario',
        'Tipo',
        'Serie',
        'Numero',
        'FechaEmision',
        'Subtotal',
        'IGV',
        'Total',
    ];

    protected $casts = [
        'IdComprobante' => 'integer',
        'IdReserva' => 'integer',
        'IdFormaPago' => 'integer',
        'IdUsuario' => 'integer',
        'Subtotal' => 'decimal:2',
        'IGV' => 'decimal:2',
        'Total' => 'decimal:2',
        'FechaEmision' => 'date',
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

    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'IdFormaPago', 'IdFormaPago');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getNumeroCompletoAttribute(): string
    {
        return $this->Serie . '-' . $this->Numero;
    }

    public function getTipoNombreAttribute(): string
    {
        return $this->Tipo === 'Boleta' ? 'Boleta' : 'Factura';
    }
}