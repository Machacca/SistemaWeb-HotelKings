<?php

namespace App\Traits;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Registra una acción en la tabla auditoria
     */
    public static function audit(string $accion, string $tabla, mixed $registroId = null): void
    {
        $usuario = Auth::guard('web')->user() ?? Auth::guard('sanctum')->user();
        
        Auditoria::create([
            'IdUsuario' => $usuario?->IdUsuario ?? 1, // Fallback a usuario sistema
            'Accion' => $accion,
            'TablaAfectada' => $tabla,
            'RegistroId' => $registroId, // Opcional: agrega esta columna si quieres trackear el ID
            'FechaHora' => now(),
            'IP' => request()->ip(),
        ]);
    }
}