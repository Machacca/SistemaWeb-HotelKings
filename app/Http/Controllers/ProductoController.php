<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * INDEX: Listado de productos con stock y precio.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * CREATE: Formulario para un nuevo producto/servicio.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * STORE: Guardar el producto en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string',
            'PrecioVenta' => 'required|numeric|min:0',
            'Stock' => 'required|integer|min:0',
            'IdHotel' => 'required|integer', // Para saber a qué hotel pertenece el inventario
        ]);

        Producto::create($data);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto agregado al inventario.');
    }

    /**
     * EDIT: Cargar datos para modificar precio o stock.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    /**
     * UPDATE: Actualizar el producto.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $data = $request->validate([
            'Nombre' => 'required|string|max:100',
            'PrecioVenta' => 'required|numeric|min:0',
            'Stock' => 'required|integer|min:0',
        ]);

        $producto->update($data);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado.');
    }

    /**
     * DESTROY: Eliminar producto.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
                         ->with('success', 'Producto eliminado.');
    }
}