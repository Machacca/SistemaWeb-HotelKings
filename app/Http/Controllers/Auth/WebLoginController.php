<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Auditoria;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WebLoginController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function login(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        // 2. Buscar al usuario por email con sus relaciones
        $usuario = Usuario::with(['rol', 'hotel'])
            ->where('Email', $request->Email)
            ->first();

        // 3. Verificar credenciales
        if (!$usuario || !Hash::check($request->Password, $usuario->PasswordHash)) {
            throw ValidationException::withMessages([
                'Email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // 4. Verificar si el usuario está activo
        if (!$usuario->activo) {
            throw ValidationException::withMessages([
                'Email' => ['Este usuario está desactivado. Contacte al administrador.'],
            ]);
        }

        // 5. Iniciar sesión
        Auth::guard('web')->login($usuario);
        $request->session()->regenerate();

        // 6. Configurar contexto de hotel en sesión
        if ($usuario->IdRol == 4) {
            // Usuario Master: puede ver todos los hoteles
            session([
                'hotel_id' => null,
                'hotel_nombre' => 'ADMINISTRACIÓN GLOBAL'
            ]);
        } else {
            // Usuario normal: solo su hotel asignado
            session([
                'hotel_id' => $usuario->IdHotel,
                'hotel_nombre' => $usuario->hotel?->Nombre ?? 'Sede No Asignada'
            ]);
        }

        // 7. Registrar en auditoría
        Auditoria::create([
            'IdUsuario' => $usuario->IdUsuario,
            'Accion' => 'Login Web',
            'TablaAfectada' => 'sesiones',
            'FechaHora' => now(),
            'IP' => $request->ip(),
        ]);

        // 8. Redirigir al dashboard
        return redirect()->intended('/dashboard');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Cambia el contexto de hotel para usuarios Master.
     */
    public function cambiarContexto(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->IdRol == 4) {
            $hotelId = $request->IdHotel;

            if ($hotelId) {
                $hotel = Hotel::find($hotelId);
                if ($hotel) {
                    session([
                        'hotel_id' => $hotel->IdHotel,
                        'hotel_nombre' => $hotel->Nombre
                    ]);
                }
            } else {
                session([
                    'hotel_id' => null,
                    'hotel_nombre' => 'ADMINISTRACIÓN GLOBAL'
                ]);
            }
        }

        return back();
    }
}