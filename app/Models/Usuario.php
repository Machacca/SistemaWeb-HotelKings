<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // 🔹 Tabla y PK personalizados
    protected $table = 'usuarios';
    protected $primaryKey = 'IdUsuario';
    public $incrementing = true;
    protected $keyType = 'int';

    // 🔹 Campos asignables
    protected $fillable = [
        'Username',
        'Email',
        'PasswordHash',
        'IdRol',
        'IdHotel',
    ];

    // 🔹 Campos ocultos (nunca retornar en JSON)
    protected $hidden = [
        'PasswordHash',
        'remember_token',
    ];

    // 🔹 Laravel busca 'password', pero tú usas 'PasswordHash'
    public function getAuthPassword()
    {
        return $this->PasswordHash;
    }

    // 🔹 Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'IdRol', 'IdRol');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }
}