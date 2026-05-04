<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        
        // Datos básicos para validar que la autenticación funciona
        $datos = [
            'usuario' => $usuario,
            'nombre_usuario' => $usuario->Username,
            'email_usuario' => $usuario->Email,
            'rol_usuario' => $usuario->rol?->NombreRol ?? 'Sin rol asignado',
            'id_rol' => $usuario->IdRol,
            'hotel_id' => session('hotel_id'),
            'hotel_nombre' => session('hotel_nombre', 'No asignado'),
            'es_master' => $usuario->IdRol == 4,
        ];

        return view('dashboard', $datos);
    }
}