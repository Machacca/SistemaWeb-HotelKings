<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'IdAuditoria';
    
    protected $fillable = ['IdUsuario', 'TablaAfectada', 'Accion', 'DetalleAnterior', 'DetalleNuevo', 'IP'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }
}