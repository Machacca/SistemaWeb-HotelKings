<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'IdEmpleado';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdUsuario',
        'IdHotel',
        'nombres',
        'apellidos',
        'puesto',
        'fecha_ingreso',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'email_personal',
        'activo',
    ];

    protected $casts = [
        'IdEmpleado' => 'integer',
        'IdUsuario' => 'integer',
        'IdHotel' => 'integer',
        'fecha_ingreso' => 'date',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorHotel($query, $hotelId)
    {
        if ($hotelId) {
            return $query->where('IdHotel', $hotelId);
        }
        return $query;
    }
}