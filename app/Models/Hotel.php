<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hoteles';
    protected $primaryKey = 'IdHotel';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'codigo',
        'Direccion',
        'Telefono',
        'activo',
    ];

    protected $casts = [
        'IdHotel' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'IdHotel', 'IdHotel');
    }

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'IdHotel', 'IdHotel');
    }

    public function esActivo(): bool
    {
        return $this->activo;
    }

    public function getUsuariosActivosCountAttribute(): int
    {
        return $this->usuarios()->where('activo', true)->count();
    }

    /**
     * Genera automáticamente el código basado en el nombre
     */
    public static function generarCodigo(string $nombre): string
    {
        // Tomar primeras 3 letras del nombre (solo letras)
        $nombreLimpio = preg_replace('/[^A-Za-z]/', '', $nombre);
        $codigo = strtoupper(substr($nombreLimpio, 0, 3));
        
        // Verificar si ya existe, agregar número si es necesario
        $original = $codigo;
        $contador = 1;
        while (self::where('codigo', $codigo)->exists()) {
            $codigo = $original . $contador;
            $contador++;
        }
        
        return $codigo;
    }
}