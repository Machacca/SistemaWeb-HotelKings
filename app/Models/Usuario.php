<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 'usuarios';
    protected $primaryKey = 'IdUsuario';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'IdRol',
        'IdHotel',
        'Username',
        'PasswordHash',
        'Email',
        'activo',
    ];
    protected $hidden = [
        'PasswordHash',
        'remember_token',
    ];


    protected $casts = [
        'IdUsuario' => 'integer',
        'IdRol' => 'integer',
        'IdHotel' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function rol()
    {
        return $this->belongsTo(Rol::class, 'IdRol', 'IdRol');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }

    public function esMaster(): bool
    {
        return $this->IdRol == 4;
    }

    public function esActivo(): bool
    {
        return $this->activo;
    }

    public function getNombreRolAttribute(): ?string
    {
        return $this->rol?->NombreRol;
    }

    public function getNombreHotelAttribute(): ?string
    {
        return $this->hotel?->Nombre;
    }

    public function getAuthPassword()
    {
        return $this->PasswordHash;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'IdUsuario', 'IdUsuario');
    }

    public function getAuthIdentifierName()
    {
        return 'IdUsuario';
    }
    public function getAuthIdentifier()
    {
        return $this->IdUsuario;
    }
}