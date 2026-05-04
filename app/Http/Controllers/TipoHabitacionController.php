<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TipoHabitacionController extends Controller
{
    /**
     * Muestra el listado de tipos de habitación con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master (IdRol = 4) puede acceder
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = TipoHabitacion::query();
        
        // Filtro por nombre
        if ($request->filled('buscar')) {
            $query->where('Nombre', 'like', '%' . $request->buscar . '%');
        }
        
        // Filtro por actividad
        if ($request->has('activo') && $request->activo !== '' && $request->activo !== null) {
            $query->where('activo', $request->activo == '1');
        } else {
            // Por defecto: solo mostrar activos
            $query->where('activo', true);
        }
        
        $tipos = $query->orderBy('Nombre')->paginate(10);
        
        return view('tipos_habitacion.index', compact('tipos'));
    }

    /**
     * Muestra el formulario para crear un nuevo tipo (página independiente).
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        return view('tipos_habitacion.create');
    }

    /**
     * Guarda un nuevo tipo de habitación.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:50|unique:tipo_habitacion,Nombre',
            'Tarifa_base' => 'required|numeric|min:0',
            'Capacidad' => 'required|integer|min:1',
            'Descripcion' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $validated['activo'] = true;
        
        TipoHabitacion::create($validated);
        
        return redirect()->route('tiposhabitacion.index')
                         ->with('success', 'Tipo de habitación creado correctamente.');
    }

    /**
     * Muestra los detalles de un tipo de habitación (página independiente).
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $tipo = TipoHabitacion::findOrFail($id);
        
        return view('tipos_habitacion.show', compact('tipo'));
    }

    /**
     * Muestra el formulario para editar un tipo (página independiente).
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $tipo = TipoHabitacion::findOrFail($id);
        
        return view('tipos_habitacion.edit', compact('tipo'));
    }

    /**
     * Actualiza un tipo de habitación existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $tipo = TipoHabitacion::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:50|unique:tipo_habitacion,Nombre,' . $id . ',IdTipo',
            'Tarifa_base' => 'required|numeric|min:0',
            'Capacidad' => 'required|integer|min:1',
            'Descripcion' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $tipo->update($validated);
        
        return redirect()->route('tiposhabitacion.index')
                         ->with('success', 'Tipo de habitación actualizado correctamente.');
    }

    /**
     * Desactiva un tipo de habitación (no elimina físicamente).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.'
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $tipo = TipoHabitacion::findOrFail($id);
        
        // Verificar si tiene habitaciones asociadas
        $tieneHabitaciones = $tipo->habitaciones()->exists();
        
        if ($tieneHabitaciones) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar el tipo porque tiene habitaciones asociadas.'
                ], 422);
            }
            return redirect()->route('tiposhabitacion.index')
                             ->with('error', 'No se puede desactivar el tipo porque tiene habitaciones asociadas.');
        }
        
        $tipo->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de habitación desactivado correctamente'
            ]);
        }
        
        return redirect()->route('tiposhabitacion.index')
                         ->with('success', 'Tipo de habitación desactivado correctamente.');
    }

    /**
     * Reactiva un tipo de habitación desactivado.
     */
    public function reactivar($id)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.'
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $tipo = TipoHabitacion::findOrFail($id);
        $tipo->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de habitación reactivado correctamente'
            ]);
        }
        
        return redirect()->route('tiposhabitacion.index')
                         ->with('success', 'Tipo de habitación reactivado correctamente.');
    }
}