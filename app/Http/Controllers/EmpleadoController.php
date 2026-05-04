<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Hotel;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    /**
     * Muestra el listado de empleados con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master y Admin pueden acceder
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = Empleado::with(['usuario', 'hotel']);
        
        // Filtrar por hotel (Admin solo ve su hotel)
        if ($user->IdRol != 4) {
            $query->where('IdHotel', $user->IdHotel);
        } elseif ($request->filled('hotel')) {
            $query->where('IdHotel', $request->hotel);
        }
        
        // Filtro por búsqueda (nombre o apellido)
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('nombres', 'like', '%' . $search . '%')
                  ->orWhere('apellidos', 'like', '%' . $search . '%')
                  ->orWhere('numero_documento', 'like', '%' . $search . '%');
            });
        }
        
        // Filtro por puesto
        if ($request->filled('puesto')) {
            $query->where('puesto', $request->puesto);
        }
        
        // Filtro por actividad
        if ($request->has('activo') && $request->activo !== '' && $request->activo !== null) {
            $query->where('activo', $request->activo == '1');
        } else {
            // Por defecto: solo mostrar activos
            $query->where('activo', true);
        }
        
        $empleados = $query->orderBy('apellidos')->orderBy('nombres')->paginate(10);
        
        // Lista de hoteles para el filtro (solo para Master)
        $hoteles = ($user->IdRol == 4) ? Hotel::where('activo', true)->get() : collect();
        
        // Lista de puestos para el filtro
        $puestos = [
            'Recepcionista',
            'Cajero',
            'Limpieza',
            'Mantenimiento',
            'Cocina',
            'Seguridad',
            'Administrativo',
            'Gerente',
            'Otro'
        ];
        
        return view('empleados.index', compact('empleados', 'hoteles', 'puestos'));
    }

    /**
     * Muestra el formulario para crear un nuevo empleado.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        // Hoteles según el rol
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        // Usuarios disponibles para asociar (solo los que no tienen empleado asociado)
        $usuariosDisponibles = Usuario::where('activo', true)
            ->whereDoesntHave('empleado')
            ->get();
        
        $puestos = [
            'Recepcionista',
            'Cajero',
            'Limpieza',
            'Mantenimiento',
            'Cocina',
            'Seguridad',
            'Administrativo',
            'Gerente',
            'Otro'
        ];
        
        $tiposDocumento = [
            'DNI' => 'DNI',
            'CE' => 'Carnet de Extranjería',
            'PAS' => 'Pasaporte'
        ];
        
        return view('empleados.create', compact('hoteles', 'usuariosDisponibles', 'puestos', 'tiposDocumento'));
    }

    /**
     * Guarda un nuevo empleado.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'puesto' => 'required|string|max:100',
            'fecha_ingreso' => 'required|date',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'IdUsuario' => 'nullable|exists:usuarios,IdUsuario',
            'tipo_documento' => 'nullable|string|max:20',
            'numero_documento' => 'nullable|string|max:20|unique:empleados,numero_documento',
            'telefono' => 'nullable|string|max:20',
            'email_personal' => 'nullable|email|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Seguridad: Admin solo puede crear empleados en su hotel
        if ($user->IdRol != 4) {
            $request->merge(['IdHotel' => $user->IdHotel]);
        }
        
        $validated = $validator->validated();
        $validated['activo'] = true;
        
        Empleado::create($validated);
        
        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado creado correctamente.');
    }

    /**
     * Muestra los detalles de un empleado.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $empleado = Empleado::with(['usuario', 'hotel'])->findOrFail($id);
        
        // Admin solo puede ver empleados de su hotel
        if ($user->IdRol != 4 && $empleado->IdHotel != $user->IdHotel) {
            return redirect()->route('empleados.index')
                             ->with('error', 'No tienes permiso para ver este empleado.');
        }
        
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Muestra el formulario para editar un empleado.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $empleado = Empleado::findOrFail($id);
        
        // Admin solo puede editar empleados de su hotel
        if ($user->IdRol != 4 && $empleado->IdHotel != $user->IdHotel) {
            return redirect()->route('empleados.index')
                             ->with('error', 'No tienes permiso para editar este empleado.');
        }
        
        // Hoteles según el rol
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        // Usuarios disponibles para asociar (incluyendo el actual si tiene)
        $usuariosDisponibles = Usuario::where('activo', true)
            ->where(function($q) use ($empleado) {
                $q->whereDoesntHave('empleado')
                  ->orWhere('IdUsuario', $empleado->IdUsuario);
            })
            ->get();
        
        $puestos = [
            'Recepcionista',
            'Cajero',
            'Limpieza',
            'Mantenimiento',
            'Cocina',
            'Seguridad',
            'Administrativo',
            'Gerente',
            'Otro'
        ];
        
        $tiposDocumento = [
            'DNI' => 'DNI',
            'CE' => 'Carnet de Extranjería',
            'PAS' => 'Pasaporte'
        ];
        
        return view('empleados.edit', compact('empleado', 'hoteles', 'usuariosDisponibles', 'puestos', 'tiposDocumento'));
    }

    /**
     * Actualiza un empleado existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $empleado = Empleado::findOrFail($id);
        
        // Admin solo puede editar empleados de su hotel
        if ($user->IdRol != 4 && $empleado->IdHotel != $user->IdHotel) {
            return redirect()->route('empleados.index')
                             ->with('error', 'No tienes permiso para editar este empleado.');
        }
        
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'puesto' => 'required|string|max:100',
            'fecha_ingreso' => 'required|date',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'IdUsuario' => 'nullable|exists:usuarios,IdUsuario',
            'tipo_documento' => 'nullable|string|max:20',
            'numero_documento' => 'nullable|string|max:20|unique:empleados,numero_documento,' . $id . ',IdEmpleado',
            'telefono' => 'nullable|string|max:20',
            'email_personal' => 'nullable|email|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Seguridad: Admin solo puede asignar su hotel
        if ($user->IdRol != 4) {
            $request->merge(['IdHotel' => $user->IdHotel]);
        }
        
        $validated = $validator->validated();
        $empleado->update($validated);
        
        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Desactiva un empleado (no elimina físicamente).
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
        
        $empleado = Empleado::findOrFail($id);
        
        // Admin solo puede desactivar empleados de su hotel
        if ($user->IdRol != 4 && $empleado->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para desactivar este empleado.'
                ], 403);
            }
            return redirect()->route('empleados.index')
                             ->with('error', 'No tienes permiso para desactivar este empleado.');
        }
        
        $empleado->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Empleado desactivado correctamente'
            ]);
        }
        
        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado desactivado correctamente.');
    }

    /**
     * Reactiva un empleado desactivado.
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
        
        $empleado = Empleado::findOrFail($id);
        
        // Admin solo puede reactivar empleados de su hotel
        if ($user->IdRol != 4 && $empleado->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para reactivar este empleado.'
                ], 403);
            }
            return redirect()->route('empleados.index')
                             ->with('error', 'No tienes permiso para reactivar este empleado.');
        }
        
        $empleado->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Empleado reactivado correctamente'
            ]);
        }
        
        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado reactivado correctamente.');
    }
}