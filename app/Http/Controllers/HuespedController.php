<?php

namespace App\Http\Controllers;

use App\Models\Huesped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Log;
class HuespedController extends Controller
{
    /**
     * Muestra el listado de huéspedes con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = Huesped::query();
        
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'like', '%' . $search . '%')
                  ->orWhere('Apellido', 'like', '%' . $search . '%')
                  ->orWhere('NroDocumento', 'like', '%' . $search . '%')
                  ->orWhere('Email', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->has('activo') && $request->activo !== '' && $request->activo !== null) {
            $query->where('activo', $request->activo == '1');
        } else {
            $query->where('activo', true);
        }
        
        $huespedes = $query->orderBy('Nombre')->orderBy('Apellido')->paginate(10);
        
        return view('huespedes.index', compact('huespedes'));
    }

    /**
     * Muestra el formulario para crear un nuevo huésped.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        return view('huespedes.create');
    }

    /**
     * Guarda un nuevo huésped.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Apellido' => 'required|string|max:100',
            'TipoDocumento' => 'required|string|max:20',
            'NroDocumento' => 'required|string|max:20',
            'Email' => 'nullable|email|max:100',
            'Telefono' => 'nullable|string|max:20',
            'Nacionalidad' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $validated['activo'] = true;
        
        Huesped::create($validated);
        
        return redirect()->route('huespedes.index')
                         ->with('success', 'Huésped creado correctamente.');
    }

    /**
     * Muestra los detalles de un huésped.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $huesped = Huesped::with('reservas')->findOrFail($id);
        
        return view('huespedes.show', compact('huesped'));
    }

    /**
     * Muestra el formulario para editar un huésped.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $huesped = Huesped::findOrFail($id);
        
        return view('huespedes.edit', compact('huesped'));
    }

    /**
     * Actualiza un huésped existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }
        
        $huesped = Huesped::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Apellido' => 'required|string|max:100',
            'TipoDocumento' => 'required|string|max:20',
            'NroDocumento' => 'required|string|max:20',
            'Email' => 'nullable|email|max:100',
            'Telefono' => 'nullable|string|max:20',
            'Nacionalidad' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        $huesped->update($validated);
        
        return redirect()->route('huespedes.index')
                         ->with('success', 'Huésped actualizado correctamente.');
    }

    /**
     * Desactiva un huésped (no elimina físicamente).
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
        
        $huesped = Huesped::findOrFail($id);
        
        if ($huesped->reservas()->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar el huésped porque tiene reservas asociadas.'
                ], 422);
            }
            return redirect()->route('huespedes.index')
                             ->with('error', 'No se puede desactivar el huésped porque tiene reservas asociadas.');
        }
        
        $huesped->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Huésped desactivado correctamente'
            ]);
        }
        
        return redirect()->route('huespedes.index')
                         ->with('success', 'Huésped desactivado correctamente.');
    }

    /**
     * Reactiva un huésped desactivado.
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
        
        $huesped = Huesped::findOrFail($id);
        $huesped->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Huésped reactivado correctamente'
            ]);
        }
        
        return redirect()->route('huespedes.index')
                         ->with('success', 'Huésped reactivado correctamente.');
    }

    /**
     * Busca huéspedes para autocompletado (AJAX).
     * Útil para el formulario de reservas.
     */
    public function buscar(Request $request)
    {
        $term = $request->get('q');
        
        if (empty($term)) {
            return response()->json([]);
        }
        
        $huespedes = Huesped::where('activo', true)
            ->where(function($query) use ($term) {
                $query->where('Nombre', 'like', "%{$term}%")
                      ->orWhere('Apellido', 'like', "%{$term}%")
                      ->orWhere('NroDocumento', 'like', "%{$term}%")
                      ->orWhere('Email', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['IdHuesped', 'Nombre', 'Apellido', 'NroDocumento']);
        
        $results = [];
        foreach ($huespedes as $huesped) {
            $results[] = [
                'id' => $huesped->IdHuesped,
                'text' => $huesped->Nombre . ' ' . $huesped->Apellido . ' (' . $huesped->NroDocumento . ')'
            ];
        }
        return response()->json($results);
    }
    public function buscarmodal(Request $request)
    {
        
        $term = $request->get('q');
        
        // Si no hay término, devolver todos los huéspedes activos
        $query = Huesped::where('activo', true);
        
        if (!empty($term)) {
            $query->where(function($q) use ($term) {
                $q->where('Nombre', 'like', "%{$term}%")
                ->orWhere('Apellido', 'like', "%{$term}%")
                ->orWhere('NroDocumento', 'like', "%{$term}%")
                ->orWhere('Email', 'like', "%{$term}%");
            });
        }
        
        $huespedes = $query->orderBy('Nombre')->limit(20)->get(['IdHuesped', 'Nombre', 'Apellido', 'NroDocumento']);
        
        $results = [];
        foreach ($huespedes as $huesped) {
            $results[] = [
                'id' => $huesped->IdHuesped,
                'text' => $huesped->Nombre . ' ' . $huesped->Apellido . ($huesped->NroDocumento ? ' (' . $huesped->NroDocumento . ')' : '')
            ];
        }
        
        return response()->json($results);
    }
    public function storeModal(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso.'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Apellido' => 'required|string|max:100',
            'TipoDocumento' => 'required|string|max:20',
            'NroDocumento' => 'required|string|max:20',
            'Email' => 'nullable|email|max:100',
            'Telefono' => 'nullable|string|max:20',
            'Nacionalidad' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        $validated = $validator->validated();
        $validated['activo'] = true;
        
        $huesped = Huesped::create($validated);
        
        return response()->json([
            'success' => true,
            'id' => $huesped->IdHuesped,
            'nombre' => $huesped->Nombre . ' ' . $huesped->Apellido
        ]);
    }
}