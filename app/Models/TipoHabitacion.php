<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    use HasFactory;

    protected $table = 'tipo_habitacion';
    protected $primaryKey = 'IdTipo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Tarifa_base',
        'Capacidad',
        'Descripcion',
        'activo',
    ];

    protected $casts = [
        'IdTipo' => 'integer',
        'Tarifa_base' => 'decimal:2',
        'Capacidad' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'IdTipo', 'IdTipo');
    }

    public function getCantidadHabitacionesAttribute(): int
    {
        return $this->habitaciones()->count();
    }

    public function estaActivo(): bool
    {
        return $this->activo;
    }
}