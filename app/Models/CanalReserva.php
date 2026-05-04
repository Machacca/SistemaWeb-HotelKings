<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanalReserva extends Model
{
    use HasFactory;

    protected $table = 'canales_reserva';
    protected $primaryKey = 'IdCanal';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'IdCanal' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'IdCanal', 'IdCanal');
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