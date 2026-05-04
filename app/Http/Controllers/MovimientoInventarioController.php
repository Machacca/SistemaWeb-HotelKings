<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MovimientoInventarioController extends Controller
{
    /**
     * Muestra el listado global de movimientos (para reportes)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master, Admin y Cajero pueden acceder
        if (!in_array($user->IdRol, [1, 3, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = MovimientoInventario::with(['producto', 'usuario']);
        
        // Filtrar por producto
        if ($request->filled('producto_id')) {
            $query->where('IdProducto', $request->producto_id);
        }
        
        // Filtrar por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        // Filtrar por fecha
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_movimiento', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_movimiento', '<=', $request->fecha_fin);
        }
        
        $movimientos = $query->orderBy('fecha_movimiento', 'desc')->paginate(20);
        
        // Lista de productos para el filtro
        $productos = Producto::activos()->orderBy('Nombre')->get();
        
        // Tipos de movimiento para el filtro
        $tipos = [
            'compra' => 'Compra',
            'venta' => 'Venta',
            'retiro' => 'Retiro',
            'ajuste' => 'Ajuste',
            'merma' => 'Merma',
            'devolucion' => 'Devolución',
        ];
        
        return view('movimientos.index', compact('movimientos', 'productos', 'tipos'));
    }

    /**
     * Registra un movimiento desde la vista del producto
     */
    public function storeFromProducto(Request $request, $id)
    {
        $user = Auth::user();
        
        // Solo Master y Admin pueden registrar movimientos manuales
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $producto = Producto::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:compra,venta,retiro,ajuste,merma,devolucion',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'nullable|numeric|min:0',
            'observacion' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        // Validar que no exceda el stock para salidas
        if (in_array($validated['tipo'], ['venta', 'retiro', 'merma'])) {
            if ($validated['cantidad'] > $producto->StockActual) {
                return redirect()->back()->with('error', 'No hay suficiente stock disponible. Stock actual: ' . $producto->StockActual);
            }
        }
        
        // Registrar el movimiento
        $movimiento = $producto->registrarMovimiento([
            'tipo' => $validated['tipo'],
            'cantidad' => $validated['cantidad'],
            'precio_unitario' => $validated['precio_unitario'] ?? null,
            'observacion' => $validated['observacion'],
            'IdUsuario' => $user->IdUsuario,
        ]);
        
        $mensajes = [
            'compra' => 'Compra registrada correctamente. Stock actual: ' . $producto->StockActual,
            'venta' => 'Venta registrada correctamente. Stock actual: ' . $producto->StockActual,
            'retiro' => 'Retiro/Cortesía registrado correctamente. Stock actual: ' . $producto->StockActual,
            'ajuste' => 'Ajuste de stock registrado correctamente. Stock actual: ' . $producto->StockActual,
            'merma' => 'Merma registrada correctamente. Stock actual: ' . $producto->StockActual,
            'devolucion' => 'Devolución registrada correctamente. Stock actual: ' . $producto->StockActual,
        ];
        
        return redirect()->route('productos.show', $producto->IdProducto)
                         ->with('success', $mensajes[$validated['tipo']]);
    }

    /**
     * Elimina un movimiento (solo si es necesario)
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
            return redirect()->back()->with('error', 'No tienes permiso.');
        }
        
        $movimiento = MovimientoInventario::findOrFail($id);
        $producto = $movimiento->producto;
        
        // Revertir el movimiento en el stock
        if (in_array($movimiento->tipo, ['compra', 'devolucion'])) {
            $producto->decrement('StockActual', $movimiento->cantidad);
        } elseif (in_array($movimiento->tipo, ['venta', 'retiro', 'merma'])) {
            $producto->increment('StockActual', $movimiento->cantidad);
        }
        
        $movimiento->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Movimiento eliminado y stock revertido correctamente.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Movimiento eliminado y stock revertido.');
    }
}