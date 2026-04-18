<?php
// app/Features/Huespedes/Controllers/HuespedController.php

namespace App\Features\Huespedes\Controllers;

use App\Models\Huesped;
use App\Features\Huespedes\Requests\StoreHuespedRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class HuespedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $huespedes = Huesped::orderBy('created_at', 'desc')->paginate(15);
        return view('huespedes.index', compact('huespedes'));
    }
    
    public function create()
    {
        return view('huespedes.create');
    }
    
    public function store(Request $request)
    {
        try {
            // Validación manual para API
            $validated = $request->validate([
                'Nombre' => 'required|string|max:100',
                'Apellido' => 'required|string|max:100',
                'TipoDocumento' => 'required|string',
                'NroDocumento' => 'required|string|unique:huespedes,NroDocumento',
                'Email' => 'nullable|email|unique:huespedes,Email',
                'Telefono' => 'nullable|string|max:20',
            ]);
            
            $huesped = Huesped::create($validated);
            
            // Si es una petición AJAX/API
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'huesped' => $huesped,
                    'message' => 'Huésped creado exitosamente'
                ]);
            }
            
            return redirect()->route('huespedes.index')
                           ->with('success', 'Huésped creado exitosamente');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
    
    public function edit($id)
    {
        $huesped = Huesped::findOrFail($id);
        return view('huespedes.edit', compact('huesped'));
    }
    
    public function update(Request $request, $id)
    {
        $huesped = Huesped::findOrFail($id);
        
        $validated = $request->validate([
            'Nombre' => 'required|string|max:100',
            'Apellido' => 'required|string|max:100',
            'TipoDocumento' => 'required|string',
            'NroDocumento' => 'required|string|unique:huespedes,NroDocumento,' . $id . ',IdHuesped',
            'Email' => 'nullable|email|unique:huespedes,Email,' . $id . ',IdHuesped',
            'Telefono' => 'nullable|string|max:20',
        ]);
        
        $huesped->update($validated);
        
        return redirect()->route('huespedes.index')
                       ->with('success', 'Huésped actualizado exitosamente');
    }
    
    public function destroy($id)
    {
        $huesped = Huesped::findOrFail($id);
        
        if ($huesped->reservas()->count() > 0) {
            return back()->with('error', 'No se puede eliminar porque tiene reservas asociadas');
        }
        
        $huesped->delete();
        
        return redirect()->route('huespedes.index')
                       ->with('success', 'Huésped eliminado exitosamente');
    }
}