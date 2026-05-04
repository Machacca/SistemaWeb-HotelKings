<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acompanante extends Model
{
    use HasFactory;

    protected $table = 'acompanantes';
    protected $primaryKey = 'IdAcompanante';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdDetalleReserva',
        'Nombre',
        'Apellido',
    ];

    protected $casts = [
        'IdAcompanante' => 'integer',
        'IdDetalleReserva' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function detalleReserva()
    {
        return $this->belongsTo(DetalleReserva::class, 'IdDetalleReserva', 'IdDetalle');
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getNombreCompletoAttribute(): string
    {
        return $this->Nombre . ' ' . $this->Apellido;
    }
}