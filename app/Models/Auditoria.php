<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditoria';
    protected $primaryKey = 'IdLog';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdUsuario',
        'Accion',
        'TablaAfectada',
        'FechaHora',
        'IP',
    ];

    protected $casts = [
        'IdLog' => 'integer',
        'IdUsuario' => 'integer',
        'FechaHora' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }

    /**
     * Método helper para registrar acciones rápidamente.
     * 
     * @param int $idUsuario
     * @param string $accion
     * @param string $tablaAfectada
     * @param string|null $ip
     * @return self
     */
    public static function registrar($idUsuario, string $accion, string $tablaAfectada, ?string $ip = null): self
    {
        return self::create([
            'IdUsuario' => $idUsuario,
            'Accion' => $accion,
            'TablaAfectada' => $tablaAfectada,
            'FechaHora' => now(),
            'IP' => $ip,
        ]);
    }
}