<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login API - Retorna token Sanctum
     */
    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        $usuario = Usuario::with('rol', 'hotel')
            ->where('Email', $request->Email)
            ->first();

        if (!$usuario || !Hash::check($request->Password, $usuario->PasswordHash)) {
            throw ValidationException::withMessages([
                'Email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Revocar tokens anteriores del mismo dispositivo (opcional)
        // $usuario->tokens()->delete();

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => [
                'IdUsuario' => $usuario->IdUsuario,
                'Username' => $usuario->Username,
                'Email' => $usuario->Email,
                'Rol' => $usuario->rol?->NombreRol,
                'Hotel' => $usuario->hotel?->Nombre,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * Logout - Revocar token actual
     */
    public function logout(Request $request)
    {
        // Elimina el token usado en esta petición
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ], 200);
    }

    /**
     * Profile - Datos del usuario autenticado
     */
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('rol', 'hotel'),
        ], 200);
    }
}