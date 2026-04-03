<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion; // Para elegir Simple, Doble, etc.
use App\Models\Hotel;          // Para saber a qué hotel pertenece
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    /**
     * 1. INDEX: Listado de todas las habitaciones.
     */
    public function index()
    {
        $habitaciones = Habitacion::with(['tipo', 'hotel'])->get();
        return view('habitaciones.index', compact('habitaciones'));
    }

    /**
     * 2. CREATE: Mostrar el formulario para una nueva habitación.
     */
    public function create()
    {
        $tipos = TipoHabitacion::all();
        $hoteles = Hotel::all();
        return view('habitaciones.create', compact('tipos', 'hoteles'));
    }

    /**
     * 3. STORE: Guardar la nueva habitación en la DB.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'IdHotel' => 'required|integer',
            'IdTipo' => 'required|integer',
            'Numero' => 'required|unique:habitaciones,Numero',
            'Piso' => 'required|integer',
        ]);

        $data['IdEstadoHabitacion'] = 1; 

        Habitacion::create($data);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación creada correctamente.');
    }

    /**
     * 4. EDIT: Mostrar el formulario con los datos cargados.
     */
    public function edit($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $tipos = TipoHabitacion::all();
        $hoteles = Hotel::all();
        
        return view('habitaciones.edit', compact('habitacion', 'tipos', 'hoteles'));
    }

    /**
     * 5. UPDATE: Procesar los cambios de la edición.
     */
    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $data = $request->validate([
            'IdHotel' => 'required|integer',
            'IdTipo' => 'required|integer',
            'Numero' => 'required|unique:habitaciones,Numero,' . $id . ',IdHabitacion',
            'Piso' => 'required|integer',
            'IdEstadoHabitacion' => 'required|integer' // En el edit sí dejamos cambiar el estado
        ]);

        $habitacion->update($data);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada.');
    }

    /**
     * 6. DESTROY: Eliminar la habitación.
     */
    public function destroy($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->delete();

        return redirect()->route('habitaciones.index')->with('success', 'Habitación eliminada.');
    }
}