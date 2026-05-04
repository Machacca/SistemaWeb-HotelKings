<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HabitacionController extends Controller
{
    /**
     * Muestra el listado de habitaciones con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Habitacion::with(['tipo', 'hotel']);
        
        if ($user->IdRol != 4) {
            $query->where('IdHotel', $user->IdHotel);
        } elseif ($request->filled('hotel')) {
            $query->where('IdHotel', $request->hotel);
        } elseif (session('hotel_id')) {
            $query->where('IdHotel', session('hotel_id'));
        }
        
        if ($request->filled('buscar')) {
            $query->where('Numero', 'like', '%' . $request->buscar . '%');
        }
        
        if ($request->filled('estado')) {
            $query->where('IdEstadoHabitacion', $request->estado);
        }
        
        if ($request->has('activo') && $request->activo !== '' && $request->activo !== null) {
            $query->where('activo', $request->activo == '1');
        } else {
            $query->where('activo', true);
        }
        
        $habitaciones = $query->orderBy('Numero')->paginate(10);
        $hoteles = ($user->IdRol == 4) ? Hotel::where('activo', true)->get() : collect();
        $tipos = TipoHabitacion::where('activo', true)->get();
        
        return view('habitaciones.index', compact('habitaciones', 'hoteles', 'tipos'));
    }

    /**
     * Muestra el formulario para crear una nueva habitación (página independiente).
     */
    public function create()
    {
        $user = Auth::user();
        
        $tipos = TipoHabitacion::where('activo', true)->get();
        
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        return view('habitaciones.create', compact('tipos', 'hoteles'));
    }

    /**
     * Guarda una nueva habitación en la base de datos.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'IdTipo' => 'required|exists:tipo_habitacion,IdTipo',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'Numero' => 'required|string|max:20',
            'Piso' => 'required|string|max:10',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        if ($user->IdRol != 4) {
            $validated['IdHotel'] = $user->IdHotel;
        }
        
        $exists = Habitacion::where('IdHotel', $validated['IdHotel'])
            ->where('Numero', $validated['Numero'])
            ->exists();
        
        if ($exists) {
            return redirect()->route('habitaciones.index')
                             ->with('error', 'Ya existe una habitación con ese número en este hotel.');
        }
        
        $validated['IdEstadoHabitacion'] = 1;
        $validated['activo'] = true;
        
        Habitacion::create($validated);
        
        return redirect()->route('habitaciones.index')
                         ->with('success', 'Habitación creada correctamente.');
    }

    /**
     * Muestra los detalles de una habitación específica (página independiente).
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::with(['tipo', 'hotel'])->findOrFail($id);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            return redirect()->route('habitaciones.index')
                             ->with('error', 'No tienes permiso para ver esta habitación.');
        }
        
        return view('habitaciones.show', compact('habitacion'));
    }

    /**
     * Muestra el formulario para editar una habitación (página independiente).
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::findOrFail($id);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            return redirect()->route('habitaciones.index')
                             ->with('error', 'No tienes permiso para editar esta habitación.');
        }
        
        $tipos = TipoHabitacion::where('activo', true)->get();
        
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        return view('habitaciones.edit', compact('habitacion', 'tipos', 'hoteles'));
    }

    /**
     * Actualiza una habitación existente.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $user = Auth::user();
        
        $habitacion = Habitacion::findOrFail($id);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            return redirect()->route('habitaciones.index')
                             ->with('error', 'No tienes permiso para editar esta habitación.');
        }
        
        $validator = Validator::make($request->all(), [
            'IdTipo' => 'required|exists:tipo_habitacion,IdTipo',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'Numero' => 'required|string|max:20',
            'Piso' => 'required|string|max:10',
            'IdEstadoHabitacion' => 'required|in:1,2,3,4',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        if ($user->IdRol != 4) {
            $validated['IdHotel'] = $user->IdHotel;
        }
        
        $exists = Habitacion::where('IdHotel', $validated['IdHotel'])
            ->where('Numero', $validated['Numero'])
            ->where('IdHabitacion', '!=', $id)
            ->exists();
        
        if ($exists) {
            return redirect()->route('habitaciones.index')
                             ->with('error', 'Ya existe otra habitación con ese número en este hotel.');
        }
        
        $habitacion->update($validated);
        
        return redirect()->route('habitaciones.index')
                         ->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Desactiva una habitación (soft delete con campo activo).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::findOrFail($id);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para desactivar esta habitación.'
                ], 403);
            }
            return redirect()->route('habitaciones.index')
                             ->with('error', 'No tienes permiso para desactivar esta habitación.');
        }
        
        $tieneReservas = $habitacion->detallesReserva()->exists();
        
        if ($tieneReservas) {
            $errorMessage = 'No se puede desactivar la habitación porque tiene reservas asociadas.';
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return redirect()->route('habitaciones.index')->with('error', $errorMessage);
        }
        
        $habitacion->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Habitación desactivada correctamente'
            ]);
        }
        
        return redirect()->route('habitaciones.index')
                         ->with('success', 'Habitación desactivada correctamente.');
    }

    /**
     * Reactiva una habitación (cambia activo de false a true).
     */
    public function reactivar($id)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::findOrFail($id);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para reactivar esta habitación.'
                ], 403);
            }
            return redirect()->route('habitaciones.index')
                             ->with('error', 'No tienes permiso para reactivar esta habitación.');
        }
        
        $habitacion->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Habitación reactivada correctamente'
            ]);
        }
        
        return redirect()->route('habitaciones.index')
                         ->with('success', 'Habitación reactivada correctamente.');
    }

    /**
     * Cambia el estado de una habitación a Disponible (1).
     */
    public function liberar($id)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::findOrFail($id);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para modificar esta habitación.'
                ], 403);
            }
            return redirect()->route('habitaciones.index')
                             ->with('error', 'No tienes permiso para modificar esta habitación.');
        }
        
        $habitacion->IdEstadoHabitacion = 1;
        $habitacion->save();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'La habitación ' . $habitacion->Numero . ' ahora está DISPONIBLE.'
            ]);
        }
        
        return redirect()->route('habitaciones.index')
                         ->with('success', 'La habitación ' . $habitacion->Numero . ' ahora está DISPONIBLE.');
    }
}