<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Muestra el listado de productos.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Solo Master, Admin y Cajero pueden acceder
        if (!in_array($user->IdRol, [1, 3, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $query = Producto::with('hotel');
        
        // Filtrar por hotel (Admin solo ve su hotel, Cajero también)
        if ($user->IdRol != 4) {
            $query->where('IdHotel', $user->IdHotel);
        } elseif ($request->filled('hotel')) {
            $query->where('IdHotel', $request->hotel);
        }
        
        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'like', '%' . $search . '%')
                  ->orWhere('categoria', 'like', '%' . $search . '%');
            });
        }
        
        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        
        // Filtro por estado de stock
        if ($request->filled('stock')) {
            if ($request->stock == 'bajo') {
                $query->whereRaw('StockActual <= StockMinimo');
            } elseif ($request->stock == 'critico') {
                $query->where('StockActual', 0);
            }
        }
        
        // Filtro por actividad
        if ($request->has('activo') && $request->activo !== '' && $request->activo !== null) {
            $query->where('activo', $request->activo == '1');
        } else {
            $query->where('activo', true);
        }
        
        $productos = $query->orderBy('Nombre')->paginate(12);
        
        // Datos para filtros
        $hoteles = ($user->IdRol == 4) ? Hotel::where('activo', true)->get() : collect();
        
        // Categorías para filtro
        $categorias = Producto::where('activo', true)
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria');
        
        return view('productos.index', compact('productos', 'hoteles', 'categorias'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        // Hoteles según el rol
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        return view('productos.create', compact('hoteles'));
    }

    /**
     * Guarda un nuevo producto.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'PrecioVenta' => 'required|numeric|min:0',
            'StockMinimo' => 'required|integer|min:0',
            'StockActual' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        // Seguridad: si no es Master, forzar su hotel
        if ($user->IdRol != 4) {
            $validated['IdHotel'] = $user->IdHotel;
        }
        
        $validated['activo'] = true;
        
        $producto = Producto::create($validated);
        
        // Registrar movimiento inicial de compra si hay stock inicial
        if ($validated['StockActual'] > 0) {
            $producto->registrarMovimiento([
                'tipo' => 'compra',
                'cantidad' => $validated['StockActual'],
                'precio_unitario' => $validated['PrecioVenta'],
                'observacion' => 'Stock inicial al crear producto',
                'IdUsuario' => $user->IdUsuario,
            ]);
        }
        
        return redirect()->route('productos.index')
                         ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Muestra los detalles de un producto.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 3, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para acceder a esta sección.');
        }
        
        $producto = Producto::with(['hotel', 'movimientos' => function($q) {
            $q->orderBy('fecha_movimiento', 'desc')->limit(20);
        }])->findOrFail($id);
        
        // Si no es Master, verificar que vea productos de su hotel
        if ($user->IdRol != 4 && $producto->IdHotel != $user->IdHotel) {
            return redirect()->route('productos.index')
                             ->with('error', 'No tienes permiso para ver este producto.');
        }
        
        // Estadísticas
        $stats = [
            'total_compras' => $producto->movimientos()->compras()->sum('cantidad'),
            'total_ventas' => $producto->movimientos()->ventas()->sum('cantidad'),
            'total_mermas' => $producto->movimientos()->mermas()->sum('cantidad'),
            'total_retiros' => $producto->movimientos()->retiros()->sum('cantidad'),
        ];
        
        return view('productos.show', compact('producto', 'stats'));
    }

    /**
     * Muestra el formulario para editar un producto.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $producto = Producto::findOrFail($id);
        
        // Admin solo puede editar productos de su hotel
        if ($user->IdRol != 4 && $producto->IdHotel != $user->IdHotel) {
            return redirect()->route('productos.index')
                             ->with('error', 'No tienes permiso para editar este producto.');
        }
        
        // Hoteles según el rol
        if ($user->IdRol == 4) {
            $hoteles = Hotel::where('activo', true)->get();
        } else {
            $hoteles = Hotel::where('IdHotel', $user->IdHotel)->where('activo', true)->get();
        }
        
        return view('productos.edit', compact('producto', 'hoteles'));
    }

    /**
     * Actualiza un producto existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->IdRol, [1, 4])) {
            return redirect()->route('dashboard')
                             ->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        $producto = Producto::findOrFail($id);
        
        // Admin solo puede editar productos de su hotel
        if ($user->IdRol != 4 && $producto->IdHotel != $user->IdHotel) {
            return redirect()->route('productos.index')
                             ->with('error', 'No tienes permiso para editar este producto.');
        }
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'PrecioVenta' => 'required|numeric|min:0',
            'StockMinimo' => 'required|integer|min:0',
            'StockActual' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();
        
        // Seguridad: si no es Master, forzar su hotel
        if ($user->IdRol != 4) {
            $validated['IdHotel'] = $user->IdHotel;
        }
        
        $producto->update($validated);
        
        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Desactiva un producto (no elimina físicamente).
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
        
        $producto = Producto::findOrFail($id);
        
        // Verificar si tiene movimientos asociados
        if ($producto->movimientos()->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar el producto porque tiene movimientos asociados.'
                ], 422);
            }
            return redirect()->route('productos.index')
                             ->with('error', 'No se puede desactivar el producto porque tiene movimientos asociados.');
        }
        
        $producto->update(['activo' => false]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto desactivado correctamente'
            ]);
        }
        
        return redirect()->route('productos.index')
                         ->with('success', 'Producto desactivado correctamente.');
    }

    /**
     * Reactiva un producto desactivado.
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
        
        $producto = Producto::findOrFail($id);
        $producto->update(['activo' => true]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto reactivado correctamente'
            ]);
        }
        
        return redirect()->route('productos.index')
                         ->with('success', 'Producto reactivado correctamente.');
    }
}