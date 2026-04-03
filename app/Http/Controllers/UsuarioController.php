<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para encriptar contraseñas

class UsuarioController extends Controller
{
    public function index()
    {
        // Traemos los usuarios con su Rol y Hotel asignado
        $usuarios = Usuario::with(['rol', 'hotel'])->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        $hoteles = Hotel::all();
        return view('usuarios.create', compact('roles', 'hoteles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Username' => 'required|unique:usuarios,Username',
            'Email'    => 'required|email|unique:usuarios,Email',
            'Password' => 'required|min:6',
            'IdRol'    => 'required',
            'IdHotel'  => 'required',
        ]);

        Usuario::create([
            'Username' => $request->Username,
            'Email'    => $request->Email,
            'IdRol'    => $request->IdRol,
            'IdHotel'  => $request->IdHotel,
            // IMPORTANTE: Encriptamos la contraseña antes de guardar
            'PasswordHash' => Hash::make($request->Password),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Rol::all();
        $hoteles = Hotel::all();
        return view('usuarios.edit', compact('usuario', 'roles', 'hoteles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'Username' => 'required|unique:usuarios,Username,' . $id . ',IdUsuario',
            'Email'    => 'required|email|unique:usuarios,Email,' . $id . ',IdUsuario',
            'IdRol'    => 'required',
        ]);

        // Actualizamos datos básicos
        $usuario->Username = $request->Username;
        $usuario->Email = $request->Email;
        $usuario->IdRol = $request->IdRol;

        // Solo actualizamos la contraseña si el usuario escribió una nueva
        if ($request->filled('Password')) {
            $usuario->PasswordHash = Hash::make($request->Password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }
}