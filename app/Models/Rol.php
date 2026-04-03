<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'IdRol';
    protected $fillable = ['NombreRol'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'IdRol', 'IdRol');
    }
}