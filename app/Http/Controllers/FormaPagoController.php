<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormaPagoController extends Controller
{
    /**
     * Muestra el listado de formas de pago.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master, Admin y Cajero pueden acceder
        if (!in_array($user->IdRol, [1, 3, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = FormaPago::query();
        
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
        
        $formasPago = $query->orderBy('Nombre')->paginate(10);
        
        return view('formas_pago.index', compact('formasPago'));
    }

    /**
     * Muestra el formulario para crear una nueva forma de pago.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Solo Master y Admin pueden crear
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        return view('formas_pago.create');
    }

    /**
     * Guarda una nueva forma de pago.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100|unique:formas_pago,Nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $validated['activo'] = true;
        
        FormaPago::create($validated);
        
        return redirect()->route('formas-pago.index')
                         ->with('success', 'Forma de pago creada correctamente.');
    }

    /**
     * Muestra los detalles de una forma de pago.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 3, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $formaPago = FormaPago::findOrFail($id);
        
        return view('formas_pago.show', compact('formaPago'));
    }

    /**
     * Muestra el formulario para editar una forma de pago.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $formaPago = FormaPago::findOrFail($id);
        
        return view('formas_pago.edit', compact('formaPago'));
    }

    /**
     * Actualiza una forma de pago existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $formaPago = FormaPago::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100|unique:formas_pago,Nombre,' . $id . ',IdFormaPago',
            'descripcion' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $formaPago->update($validated);
        
        return redirect()->route('formas-pago.index')
                         ->with('success', 'Forma de pago actualizada correctamente.');
    }

    /**
     * Desactiva una forma de pago (no elimina físicamente).
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
        
        $formaPago = FormaPago::findOrFail($id);
        
        // Verificar si tiene comprobantes asociados
        $tieneComprobantes = $formaPago->comprobantes()->exists();
        
        if ($tieneComprobantes) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar la forma de pago porque tiene comprobantes asociados.'
                ], 422);
            }
            return redirect()->route('formas-pago.index')
                             ->with('error', 'No se puede desactivar la forma de pago porque tiene comprobantes asociados.');
        }
        
        $formaPago->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Forma de pago desactivada correctamente'
            ]);
        }
        
        return redirect()->route('formas-pago.index')
                         ->with('success', 'Forma de pago desactivada correctamente.');
    }

    /**
     * Reactiva una forma de pago desactivada.
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
        
        $formaPago = FormaPago::findOrFail($id);
        $formaPago->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Forma de pago reactivada correctamente'
            ]);
        }
        
        return redirect()->route('formas-pago.index')
                         ->with('success', 'Forma de pago reactivada correctamente.');
    }
}