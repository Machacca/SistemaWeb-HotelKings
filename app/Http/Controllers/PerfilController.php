<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PerfilController extends Controller
{
    /**
     * Muestra el formulario del perfil del usuario autenticado.
     */
    public function index()
    {
        $usuario = Auth::user();
        return view('perfil.index', compact('usuario'));
    }

    /**
     * Actualiza los datos del perfil del usuario autenticado.
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'Username' => 'required|string|max:100|unique:usuarios,Username,' . $usuario->IdUsuario . ',IdUsuario',
            'Email' => 'required|email|max:100|unique:usuarios,Email,' . $usuario->IdUsuario . ',IdUsuario',
            'Password' => 'nullable|string|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        // Preparar datos para actualizar
        $data = [
            'Username' => $validated['Username'],
            'Email' => $validated['Email'],
        ];
        
        // Actualizar contraseña solo si se proporcionó
        if (!empty($validated['Password'])) {
            $data['PasswordHash'] = Hash::make($validated['Password']);
        }
        
        $usuario->update($data);
        
        return redirect()->route('perfil.index')
                         ->with('success', 'Perfil actualizado correctamente.');
    }
}