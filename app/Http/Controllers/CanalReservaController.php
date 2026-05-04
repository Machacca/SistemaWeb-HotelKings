<?php

namespace App\Http\Controllers;

use App\Models\CanalReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CanalReservaController extends Controller
{
    /**
     * Muestra el listado de canales de reserva.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master y Admin pueden acceder
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = CanalReserva::query();
        
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
        
        $canales = $query->orderBy('Nombre')->paginate(10);
        
        return view('canales_reserva.index', compact('canales'));
    }

    /**
     * Muestra el formulario para crear un nuevo canal.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        return view('canales_reserva.create');
    }

    /**
     * Guarda un nuevo canal de reserva.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100|unique:canales_reserva,Nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $validated['activo'] = true;
        
        CanalReserva::create($validated);
        
        return redirect()->route('canales-reserva.index')
                         ->with('success', 'Canal de reserva creado correctamente.');
    }

    /**
     * Muestra los detalles de un canal.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $canal = CanalReserva::findOrFail($id);
        
        return view('canales_reserva.show', compact('canal'));
    }

    /**
     * Muestra el formulario para editar un canal.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $canal = CanalReserva::findOrFail($id);
        
        return view('canales_reserva.edit', compact('canal'));
    }

    /**
     * Actualiza un canal existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $canal = CanalReserva::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100|unique:canales_reserva,Nombre,' . $id . ',IdCanal',
            'descripcion' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $canal->update($validated);
        
        return redirect()->route('canales-reserva.index')
                         ->with('success', 'Canal de reserva actualizado correctamente.');
    }

    /**
     * Desactiva un canal (no elimina físicamente).
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
        
        $canal = CanalReserva::findOrFail($id);
        
        // Verificar si tiene reservas asociadas
        $tieneReservas = $canal->reservas()->exists();
        
        if ($tieneReservas) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar el canal porque tiene reservas asociadas.'
                ], 422);
            }
            return redirect()->route('canales-reserva.index')
                             ->with('error', 'No se puede desactivar el canal porque tiene reservas asociadas.');
        }
        
        $canal->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Canal desactivado correctamente'
            ]);
        }
        
        return redirect()->route('canales-reserva.index')
                         ->with('success', 'Canal desactivado correctamente.');
    }

    /**
     * Reactiva un canal desactivado.
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
        
        $canal = CanalReserva::findOrFail($id);
        $canal->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Canal reactivado correctamente'
            ]);
        }
        
        return redirect()->route('canales-reserva.index')
                         ->with('success', 'Canal reactivado correctamente.');
    }
}