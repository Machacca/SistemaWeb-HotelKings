<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    use HasFactory;
    protected $table = 'huespedes';
    protected $primaryKey = 'IdHuesped';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Apellido',
        'TipoDocumento',
        'NroDocumento',
        'Email',
        'Telefono',
        'Nacionalidad',
        'activo',
    ];


    protected $casts = [
        'IdHuesped' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'IdHuesped', 'IdHuesped');
    }

 
    public function getNombreCompletoAttribute(): string
    {
        return $this->Nombre . ' ' . $this->Apellido;
    }


    public function estaActivo(): bool
    {
        return $this->activo;
    }


    public function desactivar(): bool
    {
        $this->activo = false;
        return $this->save();
    }


    public function reactivar(): bool
    {
        $this->activo = true;
        return $this->save();
    }
}