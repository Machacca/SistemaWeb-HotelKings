<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /**
     * Muestra el listado de hoteles según el rol del usuario.
     * - Master: ve todos los hoteles en tarjetas
     * - Admin, Recepcionista, Cajero: ven solo su hotel
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $query = Hotel::query();
        
        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where('Nombre', 'like', '%' . $request->buscar . '%');
        }
        
        // Filtro por estado
        $estado = $request->get('estado', 'activos');
        if ($estado == 'activos') {
            $query->where('activo', true);
        } elseif ($estado == 'inactivos') {
            $query->where('activo', false);
        }
        // 'todos' no aplica filtro
        
        $hoteles = $query->orderBy('Nombre')->get();
        
        return view('hoteles.index', compact('hoteles'));
    }

    /**
     * Muestra el formulario para crear un nuevo hotel (solo Master).
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        return view('hoteles.create');
    }

    /**
     * Guarda un nuevo hotel (solo Master).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->IdRol != 4) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100|unique:hoteles,Nombre',
            'Direccion' => 'nullable|string|max:255',
            'Telefono' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $validated['codigo'] = Hotel::generarCodigo($request->Nombre);
        $validated['activo'] = true;
        
        Hotel::create($validated);
        
        return redirect()->route('hoteles.index')
                         ->with('success', 'Hotel creado correctamente.');
    }

    /**
     * Muestra los detalles de un hotel específico.
     * - Master: puede ver cualquier hotel
     * - Admin/Recepcionista/Cajero: solo pueden ver su propio hotel
     */
    public function show($id)
    {
        $user = Auth::user();
        $rol = $user->IdRol;
        
        if (!in_array($rol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $hotel = Hotel::with(['usuarios' => function($q) {
            $q->where('activo', true);
        }])->findOrFail($id);
        
        // Si no es Master, verificar que sea su hotel
        if ($rol != 4 && $hotel->IdHotel != $user->IdHotel) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para ver este hotel.');
        }
        
        $totalUsuarios = $hotel->usuarios->count();
        $totalHabitaciones = $hotel->habitaciones()->count();
        
        return view('hoteles.show', compact('hotel', 'totalUsuarios', 'totalHabitaciones'));
    }

    /**
     * Muestra el formulario para editar un hotel.
     * - Master: puede editar cualquier hotel
     * - Admin: puede editar su propio hotel
     */
    public function edit($id)
    {
        $user = Auth::user();
        $rol = $user->IdRol;
        
        if (!in_array($rol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $hotel = Hotel::findOrFail($id);
        
        // Si no es Master, verificar que sea su hotel
        if ($rol != 4 && $hotel->IdHotel != $user->IdHotel) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para editar este hotel.');
        }
        
        return view('hoteles.edit', compact('hotel'));
    }

    /**
     * Actualiza un hotel existente.
     * - Master: puede editar cualquier hotel
     * - Admin: puede editar su propio hotel
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $rol = $user->IdRol;
        
        if (!in_array($rol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $hotel = Hotel::findOrFail($id);
        
        // Si no es Master, verificar que sea su hotel
        if ($rol != 4 && $hotel->IdHotel != $user->IdHotel) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para editar este hotel.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100|unique:hoteles,Nombre,' . $id . ',IdHotel',
            'Direccion' => 'nullable|string|max:255',
            'Telefono' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        // Solo Master puede cambiar el código
        if ($rol == 4) {
            $validated['codigo'] = $request->codigo ?? Hotel::generarCodigo($request->Nombre);
        }
        
        $hotel->update($validated);
        
        return redirect()->route('hoteles.index')
                         ->with('success', 'Hotel actualizado correctamente.');
    }

    /**
     * Desactiva un hotel (solo Master).
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
        
        $hotel = Hotel::findOrFail($id);
        
        // Verificar si tiene usuarios o habitaciones asociadas
        if ($hotel->usuarios()->exists() || $hotel->habitaciones()->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar el hotel porque tiene usuarios o habitaciones asociadas.'
                ], 422);
            }
            return redirect()->route('hoteles.index')
                             ->with('error', 'No se puede desactivar el hotel porque tiene usuarios o habitaciones asociadas.');
        }
        
        $hotel->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hotel desactivado correctamente'
            ]);
        }
        
        return redirect()->route('hoteles.index')
                         ->with('success', 'Hotel desactivado correctamente.');
    }

    /**
     * Reactiva un hotel desactivado (solo Master).
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
        
        $hotel = Hotel::findOrFail($id);
        $hotel->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hotel reactivado correctamente'
            ]);
        }
        
        return redirect()->route('hoteles.index')
                         ->with('success', 'Hotel reactivado correctamente.');
    }
}