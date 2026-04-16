<?php

namespace App\Http\Controllers;

use App\Models\Huesped;
use Illuminate\Http\Request;

class HuespedController extends Controller
{
    // 1. LISTADO (El que te daba el error por falta)
    public function index()
    {
        $huespedes = Huesped::orderBy('IdHuesped', 'desc')->get();
        return view('huespedes.index', compact('huespedes'));
    }

    // 2. MOSTRAR FORMULARIO DE CREACIÓN
    public function create()
    {
        return view('huespedes.create');
    }

// 3. PROCESAR GUARDADO
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|max:100',
            'Apellido' => 'required|max:100',
            'TipoDocumento' => 'required',
            'NroDocumento' => 'required|unique:huespedes,NroDocumento',
            'Email' => 'nullable|email',
            'Nacionalidad' => 'required',
            'Telefono' => 'nullable' // Asegúrate de validar el teléfono también
        ]);

        try {
            // 1. Obtenemos todos los datos del formulario en un array
            $datos = $request->all();

            // 2. Lógica para formatear el teléfono
            if ($request->filled('Telefono')) {
                // Limpiamos el número de cualquier caracter que no sea número
                $soloNumeros = preg_replace('/[^0-9]/', '', $request->Telefono);
                
                // Formateamos: + (primeros dos dígitos) (espacio) (resto del número)
                // Ejemplo: 5191929123 -> +51 91929123
                $datos['Telefono'] = '+' . substr($soloNumeros, 0, 2) . ' ' . substr($soloNumeros, 2);
            }

            // 3. Creamos el registro usando el array $datos modificado
            Huesped::create($datos);

            return redirect()->route('huespedes.index')
                             ->with('success', '¡El huésped se registró correctamente!');
                             
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }
    // 4. MOSTRAR FORMULARIO DE EDICIÓN
    public function edit($id)
    {
        // Importante usar findOrFail para que si el ID no existe, mande a una página 404
        $huesped = Huesped::findOrFail($id);
        return view('huespedes.edit', compact('huesped'));
    }

    // 5. PROCESAR ACTUALIZACIÓN
    public function update(Request $request, $id)
    {
        $huesped = Huesped::findOrFail($id);

        $request->validate([
            'Nombre' => 'required|max:100',
            'Apellido' => 'required|max:100',
            'TipoDocumento' => 'required',
            'NroDocumento' => 'required|unique:huespedes,NroDocumento,' . $id . ',IdHuesped',
            'Email' => 'nullable|email',
            'Nacionalidad' => 'required',
            'Telefono' => 'nullable' 
        ]);

        try {
            // 1. Extraemos los datos del request
            $datos = $request->all();

            // 2. Aplicamos el formato al teléfono si existe
            if ($request->filled('Telefono')) {
                // Quitamos cualquier cosa que no sea número (puntos, guiones, espacios)
                $soloNumeros = preg_replace('/[^0-9]/', '', $request->Telefono);
                
                // Re-armamos el string: + (primeros 2) (espacio) (el resto)
                $datos['Telefono'] = '+' . substr($soloNumeros, 0, 2) . ' ' . substr($soloNumeros, 2);
            }

            // 3. Actualizamos con el array modificado
            $huesped->update($datos);

            return redirect()->route('huespedes.index')
                            ->with('success', 'Los datos de ' . $huesped->Nombre . ' han sido actualizados.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo actualizar: ' . $e->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            $huesped = Huesped::findOrFail($id);
            $nombreCompleto = $huesped->Nombre . ' ' . $huesped->Apellido;
            
            $huesped->delete();

            return redirect()->route('huespedes.index')
                            ->with('success', "El huésped $nombreCompleto ha sido eliminado correctamente.");
        } catch (\Exception $e) {
            return redirect()->route('huespedes.index')
                            ->with('error', "No se pudo eliminar al huésped: " . $e->getMessage());
        }
    }
}