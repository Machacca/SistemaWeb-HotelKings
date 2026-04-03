<?php

namespace App\Http\Controllers;

use App\Models\Huesped;
use Illuminate\Http\Request;

class HuespedController extends Controller
{
    /**
     * INDEX: Mostrar todos los huéspedes registrados.
     */
    public function index()
    {
        $huespedes = Huesped::all();
        return view('huespedes.index', compact('huespedes'));
    }

    /**
     * CREATE: Mostrar el formulario de registro.
     */
    public function create()
    {
        return view('huespedes.create');
    }

    /**
     * STORE: Validar y guardar el nuevo huésped.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'Nombre'        => 'required|string|max:100',
            'Apellido'      => 'required|string|max:100',
            'TipoDocumento' => 'required|string', // DNI, Pasaporte, etc.
            'NroDocumento'  => 'required|string|unique:huespedes,NroDocumento',
            'Email'         => 'nullable|email|unique:huespedes,Email',
            'Telefono'      => 'nullable|string|max:20',
            'Nacionalidad'  => 'nullable|string|max:50',
        ]);

        Huesped::create($data);

        return redirect()->route('huespedes.index')
                         ->with('success', 'Huésped registrado correctamente.');
    }

    /**
     * EDIT: Cargar los datos del huésped para modificar.
     */
    public function edit($id)
    {
        $huesped = Huesped::findOrFail($id);
        return view('huespedes.edit', compact('huesped'));
    }

    /**
     * UPDATE: Aplicar los cambios del formulario de edición.
     */
    public function update(Request $request, $id)
    {
        $huesped = Huesped::findOrFail($id);

        $data = $request->validate([
            'Nombre'        => 'required|string|max:100',
            'Apellido'      => 'required|string|max:100',
            'TipoDocumento' => 'required|string',
            'NroDocumento'  => 'required|string|unique:huespedes,NroDocumento,' . $id . ',IdHuesped',
            'Email'         => 'nullable|email|unique:huespedes,Email,' . $id . ',IdHuesped',
            'Telefono'      => 'nullable|string|max:20',
            'Nacionalidad'  => 'nullable|string|max:50',
        ]);

        $huesped->update($data);

        return redirect()->route('huespedes.index')
                         ->with('success', 'Datos del huésped actualizados.');
    }

    /**
     * DESTROY: Eliminar al huésped (Cuidado con las llaves foráneas).
     */
    public function destroy($id)
    {
        $huesped = Huesped::findOrFail($id);
        
        $huesped->delete();

        return redirect()->route('huespedes.index')
                         ->with('success', 'Huésped eliminado del sistema.');
    }
}