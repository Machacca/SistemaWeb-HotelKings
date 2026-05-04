<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    use HasFactory;

    protected $table = 'formas_pago';
    protected $primaryKey = 'IdFormaPago';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'IdFormaPago' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con comprobantes (si aplica)
    public function comprobantes()
    {
       return $this->hasMany(Comprobante::class, 'IdFormaPago', 'IdFormaPago');
    }

    // Scope para activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Verificar si está activo
    public function esActivo(): bool
    {
        return $this->activo;
    }
}