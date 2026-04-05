<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WebLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        // 🔍 Búsqueda manual por tu campo 'Email'
        $usuario = Usuario::where('Email', $request->Email)->first();

        // 🔐 Verificación manual por tu campo 'PasswordHash'
        if (!$usuario || !Hash::check($request->Password, $usuario->PasswordHash)) {
            throw ValidationException::withMessages([
                'Email' => ['Credenciales inválidas'],
            ]);
        }

        // ✅ Login exitoso: iniciar sesión manualmente
        Auth::guard('web')->login($usuario);
        $request->session()->regenerate();

        // 📝 Registrar en auditoría
        Auditoria::create([
            'IdUsuario' => $usuario->IdUsuario,
            'Accion' => 'Login Web',
            'TablaAfectada' => 'sesiones',
            'FechaHora' => now(),
            'IP' => $request->ip(),
        ]);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}