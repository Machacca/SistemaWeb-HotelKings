<?php

namespace App\Http\Controllers;

use App\Models\ConsumoReserva;
use App\Models\Producto;
use Illuminate\Http\Request;

class ConsumoController extends Controller
{
    public function store(Request $request)
    {
        // El 'IdReserva' viene oculto en el formulario para saber a quién cobrar
        $request->validate([
            'IdReserva' => 'required',
            'IdProducto' => 'required',
            'Cantidad' => 'required|integer|min:1',
        ]);

        // Buscamos el producto para saber su precio actual
        $producto = Producto::find($request->IdProducto);

        ConsumoReserva::create([
            'IdReserva' => $request->IdReserva,
            'IdProducto' => $request->IdProducto,
            'Cantidad' => $request->Cantidad,
            'PrecioVenta' => $producto->PrecioVenta, // Guardamos el precio del momento
            'Estado' => 'Pendiente'
        ]);

        // Restamos del stock del producto
        $producto->decrement('Stock', $request->Cantidad);

        return back()->with('success', 'Consumo registrado en la cuenta.');
    }
}