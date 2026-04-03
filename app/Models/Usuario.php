<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'IdUsuario';

    protected $fillable = [
        'IdRol', 
        'IdHotel', 
        'Username', 
        'PasswordHash', 
        'Email'
    ];

    protected $hidden = [
        'PasswordHash',
        'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'IdRol', 'IdRol');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }
}