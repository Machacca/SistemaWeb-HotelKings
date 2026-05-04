<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Muestra el listado de usuarios con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master y Admin pueden acceder
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = Usuario::with(['rol', 'hotel']);
        
        // Filtrar por hotel (Admin solo ve su hotel)
        if ($user->IdRol != 4) {
            $query->where('IdHotel', $user->IdHotel);
        } elseif ($request->filled('hotel')) {
            $query->where('IdHotel', $request->hotel);
        }
        
        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('IdRol', $request->rol);
        }
        
        // Filtro por búsqueda (nombre o email)
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('Username', 'like', '%' . $search . '%')
                  ->orWhere('Email', 'like', '%' . $search . '%');
            });
        }
        
        // Filtro por actividad
        if ($request->has('activo') && $request->activo !== '' && $request->activo !== null) {
            $query->where('activo', $request->activo == '1');
        } else {
            // Por defecto: solo mostrar activos
            $query->where('activo', true);
        }
        
        $usuarios = $query->orderBy('Username')->paginate(10);
        
        // Datos para filtros
        $roles = Rol::all();
        $hoteles = ($user->IdRol == 4) ? Hotel::where('activo', true)->get() : collect();
        
        return view('usuarios.index', compact('usuarios', 'roles', 'hoteles'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $roles = Rol::all();
        
        // Si es Admin, solo puede asignar roles que no sean Master (4)
        if ($user->IdRol != 4) {
            $roles = Rol::where('IdRol', '!=', 4)->get();
        }
        
        // Hoteles según el rol
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        return view('usuarios.create', compact('roles', 'hoteles'));
    }

    /**
     * Guarda un nuevo usuario.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $validator = Validator::make($request->all(), [
            'Username' => 'required|string|max:100|unique:usuarios,Username',
            'Email' => 'required|email|max:100|unique:usuarios,Email',
            'Password' => 'required|string|min:6',
            'IdRol' => 'required|exists:roles,IdRol',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Seguridad: Admin no puede crear usuarios Master
        if ($user->IdRol != 4 && $request->IdRol == 4) {
            return redirect()->back()->with('error', 'No puedes crear un usuario con rol Master.')
                             ->withInput();
        }
        
        // Seguridad: Admin solo puede crear usuarios en su hotel
        if ($user->IdRol != 4) {
            $request->merge(['IdHotel' => $user->IdHotel]);
        }
        
        $validated = $validator->validated();
        $validated['PasswordHash'] = Hash::make($validated['Password']);
        unset($validated['Password']);
        $validated['activo'] = true;
        
        Usuario::create($validated);
        
        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Muestra los detalles de un usuario.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $usuario = Usuario::with(['rol', 'hotel'])->findOrFail($id);
        
        // Admin solo puede ver usuarios de su hotel
        if ($user->IdRol != 4 && $usuario->IdHotel != $user->IdHotel) {
            return redirect()->route('usuarios.index')
                             ->with('error', 'No tienes permiso para ver este usuario.');
        }
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $usuario = Usuario::findOrFail($id);
        
        // Admin solo puede editar usuarios de su hotel
        if ($user->IdRol != 4 && $usuario->IdHotel != $user->IdHotel) {
            return redirect()->route('usuarios.index')
                             ->with('error', 'No tienes permiso para editar este usuario.');
        }
        
        $roles = Rol::all();
        
        // Si es Admin, solo puede asignar roles que no sean Master (4)
        if ($user->IdRol != 4) {
            $roles = Rol::where('IdRol', '!=', 4)->get();
        }
        
        // Hoteles según el rol
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        return view('usuarios.edit', compact('usuario', 'roles', 'hoteles'));
    }

    /**
     * Actualiza un usuario existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $usuario = Usuario::findOrFail($id);
        
        // Admin solo puede editar usuarios de su hotel
        if ($user->IdRol != 4 && $usuario->IdHotel != $user->IdHotel) {
            return redirect()->route('usuarios.index')
                             ->with('error', 'No tienes permiso para editar este usuario.');
        }
        
        $validator = Validator::make($request->all(), [
            'Username' => 'required|string|max:100|unique:usuarios,Username,' . $id . ',IdUsuario',
            'Email' => 'required|email|max:100|unique:usuarios,Email,' . $id . ',IdUsuario',
            'IdRol' => 'required|exists:roles,IdRol',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'Password' => 'nullable|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Seguridad: Admin no puede asignar rol Master
        if ($user->IdRol != 4 && $request->IdRol == 4) {
            return redirect()->back()->with('error', 'No puedes asignar el rol Master.')
                             ->withInput();
        }
        
        // Seguridad: Admin solo puede asignar su hotel
        if ($user->IdRol != 4) {
            $request->merge(['IdHotel' => $user->IdHotel]);
        }
        
        $validated = $validator->validated();
        
        if (!empty($validated['Password'])) {
            $validated['PasswordHash'] = Hash::make($validated['Password']);
        }
        unset($validated['Password']);
        
        $usuario->update($validated);
        
        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Desactiva un usuario (no elimina físicamente).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.'
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $usuario = Usuario::findOrFail($id);
        
        // Admin solo puede desactivar usuarios de su hotel
        if ($user->IdRol != 4 && $usuario->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para desactivar este usuario.'
                ], 403);
            }
            return redirect()->route('usuarios.index')
                             ->with('error', 'No tienes permiso para desactivar este usuario.');
        }
        
        // No permitir desactivarse a sí mismo
        if ($usuario->IdUsuario == $user->IdUsuario) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes desactivar tu propio usuario.'
                ], 422);
            }
            return redirect()->route('usuarios.index')
                             ->with('error', 'No puedes desactivar tu propio usuario.');
        }
        
        $usuario->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario desactivado correctamente'
            ]);
        }
        
        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario desactivado correctamente.');
    }

    /**
     * Reactiva un usuario desactivado.
     */
    public function reactivar($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.'
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $usuario = Usuario::findOrFail($id);
        
        // Admin solo puede reactivar usuarios de su hotel
        if ($user->IdRol != 4 && $usuario->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para reactivar este usuario.'
                ], 403);
            }
            return redirect()->route('usuarios.index')
                             ->with('error', 'No tienes permiso para reactivar este usuario.');
        }
        
        $usuario->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario reactivado correctamente'
            ]);
        }
        
        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario reactivado correctamente.');
    }
}