<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $table = 'habitaciones';
    protected $primaryKey = 'IdHabitacion';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdTipo',
        'IdHotel',
        'Numero',
        'Piso',
        'IdEstadoHabitacion',
        'activo',
    ];

    protected $casts = [
        'IdHabitacion' => 'integer',
        'IdTipo' => 'integer',
        'IdHotel' => 'integer',
        'IdEstadoHabitacion' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoHabitacion::class, 'IdTipo', 'IdTipo');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }

    public function detallesReserva()
    {
        return $this->hasMany(DetalleReserva::class, 'IdHabitacion', 'IdHabitacion');
    }

    public static function getEstados(): array
    {
        return [
            1 => ['nombre' => 'Disponible', 'color' => '#28a745', 'texto_color' => '#ffffff'],
            2 => ['nombre' => 'Ocupada', 'color' => '#dc3545', 'texto_color' => '#ffffff'],
            3 => ['nombre' => 'Limpieza', 'color' => '#f1c40f', 'texto_color' => '#000000'],
            4 => ['nombre' => 'Mantenimiento', 'color' => '#6c757d', 'texto_color' => '#ffffff'],
        ];
    }

    public function getNombreEstadoAttribute(): string
    {
        return self::getEstados()[$this->IdEstadoHabitacion]['nombre'] ?? 'Desconocido';
    }

    public function getColorEstadoAttribute(): string
    {
        return self::getEstados()[$this->IdEstadoHabitacion]['color'] ?? '#343a40';
    }

    public function estaDisponible(): bool
    {
        return $this->IdEstadoHabitacion == 1 && $this->activo;
    }

    public function estaOcupada(): bool
    {
        return $this->IdEstadoHabitacion == 2;
    }

    public function estaActivo(): bool
    {
        return $this->activo;
    }

    public function cambiarEstado(int $nuevoEstado): bool
    {
        if (!array_key_exists($nuevoEstado, self::getEstados())) {
            return false;
        }
        
        $this->IdEstadoHabitacion = $nuevoEstado;
        return $this->save();
    }

    public function cambiarActivo(bool $activo): bool
    {
        $this->activo = $activo;
        return $this->save();
    }
}