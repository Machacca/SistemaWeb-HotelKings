<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'IdRol';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'NombreRol',
    ];

    protected $casts = [
        'IdRol' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'IdRol', 'IdRol');
    }

    public function getUsuariosCountAttribute(): int
    {
        return $this->usuarios()->count();
    }
}