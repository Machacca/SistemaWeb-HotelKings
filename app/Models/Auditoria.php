<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'IdLog';
    public $incrementing = true;
    protected $keyType = 'int';

    // 🔹 IMPORTANTE: Agregar FechaHora a fillable
    protected $fillable = [
        'IdUsuario',
        'Accion',
        'TablaAfectada',
        'FechaHora',  // ← ¡Esto faltaba!
        'IP',
    ];

    // 🔹 Casting para que FechaHora sea objeto Carbon
    protected $casts = [
        'FechaHora' => 'datetime',
    ];

    // 🔹 Si usas created_at/updated_at de Laravel, déjalo en true
    // Si NO los usas y solo quieres FechaHora, ponlo en false
    public $timestamps = true;

    // 🔹 Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }
}