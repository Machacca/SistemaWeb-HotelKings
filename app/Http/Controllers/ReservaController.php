<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\DetalleReserva;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Producto;
use App\Models\Hotel;
use App\Models\Acompanante;
use App\Models\ConsumoReserva;
use App\Models\Comprobante;
use App\Models\CanalReserva;
use App\Models\FormaPago;
use App\Models\Usuario;
use App\Models\MovimientoInventario;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class ReservaController extends Controller
{
    /**
     * Mapa de habitaciones (index)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Habitacion::with(['tipo', 'hotel']);
        
        // Filtrar por hotel según rol
        if ($user->IdRol != 4) {
            $query->where('IdHotel', $user->IdHotel);
        } elseif (session('hotel_id')) {
            $query->where('IdHotel', session('hotel_id'));
        }
        
        // Ordenar por piso y número
        $habitaciones = $query->orderBy('Piso')->orderBy('Numero')->get();
        
        // Agrupar por piso
        $habitacionesPorPiso = $habitaciones->groupBy('Piso');
        
        // Lista de hoteles para el filtro (solo Master)
        $hoteles = ($user->IdRol == 4) ? Hotel::all() : collect();
        
        return view('reservas.index', compact('habitacionesPorPiso', 'hoteles'));
    }

    /**
     * Mostrar formulario de nueva reserva (desde habitación verde)
     */
    public function create($idHabitacion)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::with(['tipo', 'hotel'])->findOrFail($idHabitacion);
        
        // Solo disponible si la habitación está disponible (estado 1) o reservada (estado 3)
        if (!in_array($habitacion->IdEstadoHabitacion, [1, 3])) {
            return redirect()->route('reservas.index')
                             ->with('error', 'Esta habitación no está disponible para reserva.');
        }
        
        $productos = Producto::where('activo', true)->get();
        $canales = DB::table('canales_reserva')->get();
        
        return view('reservas.create', compact('habitacion', 'productos', 'canales'));
    }

    /**
     * Guardar nueva reserva
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'habitaciones' => 'required|array|min:1',
            'habitaciones.*.IdHabitacion' => 'required|exists:habitaciones,IdHabitacion',
            'habitaciones.*.PrecioNoche' => 'required|numeric|min:0',
            'habitaciones.*.acompanantes' => 'nullable|array',
            'habitaciones.*.acompanantes.*.Nombre' => 'required_with:habitaciones.*.acompanantes|string|max:100',
            'habitaciones.*.acompanantes.*.Apellido' => 'required_with:habitaciones.*.acompanantes|string|max:100',
            'IdHuesped' => 'required|exists:huespedes,IdHuesped',
            'IdCanal' => 'required|exists:canales_reserva,IdCanal',
            'FechaCheckIn' => 'required|date',
            'FechaCheckOut' => 'required|date|after:FechaCheckIn',
            'observaciones' => 'nullable|string',
            'PagosAdelantados' => 'nullable|numeric|min:0',
            'TipoRegistro' => 'required|in:Reserva,Ingreso',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $esIngresoDirecto = $request->TipoRegistro === 'Ingreso';
            $estadoReserva = $esIngresoDirecto ? 'Check-in' : 'Reserva';
            $estadoHabitacion = $esIngresoDirecto ? 2 : 3;
            
            // 1. Crear la reserva
            $reserva = Reserva::create([
                'IdCanal' => $request->IdCanal,
                'IdHuesped' => $request->IdHuesped,
                'FechaReserva' => now(),
                'Estado' => $estadoReserva,
                'TotalReserva' => 0,
                'observaciones' => $request->observaciones,
            ]);
            
            $totalReserva = 0;
            $totalAdelantos = 0;
            $fechaCheckIn = Carbon::parse($request->FechaCheckIn);
            $fechaCheckOut = Carbon::parse($request->FechaCheckOut);
            $noches = $fechaCheckIn->diffInDays($fechaCheckOut) ?: 1;
            
            // 2. Crear detalles de habitaciones
            foreach ($request->habitaciones as $habData) {
                $subtotal = $habData['PrecioNoche'] * $noches;
                $totalReserva += $subtotal;
                
                $detalle = DetalleReserva::create([
                    'IdReserva' => $reserva->IdReserva,
                    'IdHabitacion' => $habData['IdHabitacion'],
                    'FechaCheckIn' => $request->FechaCheckIn,
                    'FechaCheckOut' => $request->FechaCheckOut,
                    'PrecioNoche' => $habData['PrecioNoche'],
                    'PagosAdelantados' => $request->PagosAdelantados ?? 0,
                    'Descuento' => 0,
                ]);
                
                $totalAdelantos += $request->PagosAdelantados ?? 0;
                
                // *** NUEVO: Guardar acompañantes de esta habitación ***
                if (isset($habData['acompanantes']) && is_array($habData['acompanantes'])) {
                    foreach ($habData['acompanantes'] as $acompananteData) {
                        // Verificar que tenga nombre y apellido
                        if (!empty($acompananteData['Nombre']) && !empty($acompananteData['Apellido'])) {
                            Acompanante::create([
                                'IdDetalleReserva' => $detalle->IdDetalle, // Usar el ID del detalle recién creado
                                'Nombre' => $acompananteData['Nombre'],
                                'Apellido' => $acompananteData['Apellido'],
                            ]);
                        }
                    }
                }
                
                // Cambiar estado de la habitación
                Habitacion::where('IdHabitacion', $habData['IdHabitacion'])
                    ->update(['IdEstadoHabitacion' => $estadoHabitacion]);
            }
            
            // 3. Registrar consumos iniciales (se mantiene igual)
            if ($request->has('consumos')) {
                foreach ($request->consumos as $consumo) {
                    if (!empty($consumo['IdProducto']) && isset($consumo['Cantidad']) && $consumo['Cantidad'] > 0) {
                        $producto = Producto::find($consumo['IdProducto']);
                        if ($producto && $producto->StockActual >= $consumo['Cantidad']) {
                            $subtotalConsumo = $producto->PrecioVenta * $consumo['Cantidad'];
                            $totalReserva += $subtotalConsumo;
                            
                            ConsumoReserva::create([
                                'IdReserva' => $reserva->IdReserva,
                                'IdProducto' => $consumo['IdProducto'],
                                'Cantidad' => $consumo['Cantidad'],
                                'PrecioVenta' => $producto->PrecioVenta,
                                'FechaConsumo' => now(),
                                'EstadoPago' => false,
                            ]);
                            
                            $producto->decrement('StockActual', $consumo['Cantidad']);
                        }
                    }
                }
            }
            
            // 4. Actualizar total de la reserva
            $reserva->update(['TotalReserva' => $totalReserva - $totalAdelantos]);
            
            DB::commit();
            
            // Redirigir según el tipo de registro
            if ($esIngresoDirecto) {
                return redirect()->route('reservas.edit', $reserva->IdReserva)
                    ->with('success', 'Check-in directo registrado correctamente. El huésped ya está en la habitación.');
            }
            
            return redirect()->route('reservas.show', $reserva->IdReserva)
                ->with('success', 'Reserva creada correctamente.');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una reserva (para estado Amarillo - Reservada)
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $reserva = Reserva::with(['huesped', 'canal', 'detalles.habitacion', 'detalles.acompanantes', 'consumos.producto'])
            ->findOrFail($id);
        
        // Verificar que el usuario tenga acceso
        if ($user->IdRol != 4) {
            $habitacionHotel = $reserva->detalles->first()->habitacion->IdHotel ?? null;
            if ($habitacionHotel != $user->IdHotel) {
                return redirect()->route('reservas.index')
                                 ->with('error', 'No tienes permiso para ver esta reserva.');
            }
        }
        
        $productos = Producto::where('activo', true)->get();
        
        return view('reservas.show', compact('reserva', 'productos'));
    }

    /**
     * Panel de gestión de reserva (para estado Rojo - Check-in / Ocupada)
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        $reserva = Reserva::with(['huesped', 'canal', 'detalles.habitacion', 'detalles.acompanantes', 'consumos.producto'])
            ->findOrFail($id);
        
        // Verificar acceso
        if ($user->IdRol != 4) {
            $habitacionHotel = $reserva->detalles->first()->habitacion->IdHotel ?? null;
            if ($habitacionHotel != $user->IdHotel) {
                return redirect()->route('reservas.index')
                                ->with('error', 'No tienes permiso para gestionar esta reserva.');
            }
        }
        
        $productos = Producto::where('activo', true)->get();
        
        return view('reservas.edit', compact('reserva', 'productos'));
    }

    /**
     * Actualizar reserva (agregar consumos, extender días, etc.)
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $reserva = Reserva::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // 1. Agregar consumos
            if ($request->has('consumos')) {
                foreach ($request->consumos as $consumoData) {
                    if (!empty($consumoData['IdProducto']) && $consumoData['Cantidad'] > 0) {
                        $producto = Producto::find($consumoData['IdProducto']);
                        if ($producto && $producto->StockActual >= $consumoData['Cantidad']) {
                            ConsumoReserva::create([
                                'IdReserva' => $reserva->IdReserva,
                                'IdProducto' => $consumoData['IdProducto'],
                                'Cantidad' => $consumoData['Cantidad'],
                                'PrecioVenta' => $producto->PrecioVenta,
                                'FechaConsumo' => now(),
                                'EstadoPago' => false,
                            ]);
                            $producto->decrement('StockActual', $consumoData['Cantidad']);
                        }
                    }
                }
            }
            
            // 2. Extender checkout
            if ($request->has('ExtenderCheckOut')) {
                $detalle = $reserva->detalles->first();
                $detalle->update([
                    'FechaCheckOut' => $request->ExtenderCheckOut
                ]);
            }
            
            // 3. Agregar adelanto/pago
            if ($request->has('PagosAdelantados')) {
                $detalle = $reserva->detalles->first();
                $detalle->increment('PagosAdelantados', $request->PagosAdelantados);
            }
            
            // 4. Actualizar total
            $totalHospedaje = $reserva->total_hospedaje;
            $totalConsumos = $reserva->total_consumos;
            $adelantos = $reserva->detalles->sum('PagosAdelantados');
            $descuentos = $reserva->detalles->sum('Descuento');
            
            $reserva->update([
                'TotalReserva' => $totalHospedaje + $totalConsumos - $adelantos - $descuentos
            ]);
            
            DB::commit();
            
            // *** RESPUESTA PARA AJAX ***
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reserva actualizada correctamente.'
                ]);
            }
            
            return redirect()->route('reservas.edit', $reserva->IdReserva)
                            ->with('success', 'Reserva actualizada correctamente.');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    /**
     * Confirmar check-in (cambia estado de Reservada a Check-in)
     */
    public function checkin($id)
    {
        $user = Auth::user();
        $reserva = Reserva::findOrFail($id);
        
        if ($reserva->Estado !== 'Reserva') {
            return response()->json([
                'success' => false, 
                'message' => 'Esta reserva no puede ser confirmada.'
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            $reserva->update(['Estado' => 'Check-in']);
            
            foreach ($reserva->detalles as $detalle) {
                $detalle->habitacion->update(['IdEstadoHabitacion' => 2]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Check-in confirmado. El huésped ya está en la habitación.',
                'redirect' => route('reservas.edit', $reserva->IdReserva)
            ]);
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error al confirmar check-in: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar checkout (finalizar reserva y generar comprobante)
     */
    public function checkout(Request $request, $id)
    {
        $user = Auth::user();
        
        $reserva = Reserva::with(['detalles', 'consumos'])->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'Tipo' => 'required|in:Boleta,Factura',
            'IdFormaPago' => 'required|exists:formas_pago,IdFormaPago',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Calcular totales
            $subtotalHospedaje = $reserva->total_hospedaje;
            $totalConsumos = $reserva->total_consumos;
            $adelantos = $reserva->detalles->sum('PagosAdelantados');
            $descuentos = $reserva->detalles->sum('Descuento');
            
            $subtotal = $subtotalHospedaje + $totalConsumos;
            $igv = $subtotal * 0.18;
            $total = $subtotal - $adelantos - $descuentos;
            
            // Generar correlativo
            $ultimoComprobante = Comprobante::where('Tipo', $request->Tipo)->orderBy('IdComprobante', 'desc')->first();
            $numero = $ultimoComprobante ? intval($ultimoComprobante->Numero) + 1 : 1;
            
            // Crear comprobante
            $comprobante = Comprobante::create([
                'IdReserva' => $reserva->IdReserva,
                'IdFormaPago' => $request->IdFormaPago,
                'IdUsuario' => $user->IdUsuario,
                'Tipo' => $request->Tipo,
                'Serie' => $request->Tipo === 'Factura' ? 'F001' : 'B001',
                'Numero' => str_pad($numero, 8, '0', STR_PAD_LEFT),
                'FechaEmision' => now(),
                'Subtotal' => $subtotal,
                'IGV' => $igv,
                'Total' => $total,
            ]);
            
            // Registrar movimiento en caja (ingreso)
            MovimientoCaja::create([
                'IdUsuario' => $user->IdUsuario,
                'IdComprobante' => $comprobante->IdComprobante,
                'tipo' => 'ingreso',
                'concepto' => 'Pago de reserva #' . $reserva->IdReserva,
                'monto' => $total,
                'fecha_movimiento' => now(),
                'referencia' => $comprobante->Serie . '-' . $comprobante->Numero,
                'observacion' => 'Checkout de reserva',
            ]);
            
            // Cambiar estado de la reserva
            $reserva->update(['Estado' => 'Finalizada', 'TotalReserva' => $total]);
            
            // Cambiar estado de las habitaciones a Limpieza (4)
            foreach ($reserva->detalles as $detalle) {
                $detalle->habitacion->update(['IdEstadoHabitacion' => 4]);
            }
            
            // Marcar consumos como pagados
            foreach ($reserva->consumos as $consumo) {
                $consumo->update(['EstadoPago' => true]);
            }
            
            DB::commit();
            
            return redirect()->route('comprobantes.show', $comprobante->IdComprobante)
                             ->with('success', 'Checkout completado. Comprobante generado.');
                             
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar checkout: ' . $e->getMessage());
        }
    }

    /**
     * Anular reserva
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $reserva = Reserva::findOrFail($id);
        
        if (!in_array($user->IdRol, [1, 4])) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para anular reservas.'
            ], 403);
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($reserva->detalles as $detalle) {
                $detalle->habitacion->update(['IdEstadoHabitacion' => 1]);
            }
            
            $reserva->update(['Estado' => 'Anulada']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Reserva anulada correctamente. Habitaciones liberadas.'
            ]);
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al anular la reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar acompañante a un detalle de reserva
     */
    public function agregarAcompanante(Request $request, $idDetalle)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Apellido' => 'required|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $detalle = DetalleReserva::findOrFail($idDetalle);
        
        // Verificar acceso
        $reserva = $detalle->reserva;
        if ($user->IdRol != 4 && $reserva->detalles->first()->habitacion->IdHotel != $user->IdHotel) {
            return redirect()->back()->with('error', 'No tienes permiso para agregar acompañantes.');
        }
        
        Acompanante::create([
            'IdDetalleReserva' => $idDetalle,
            'Nombre' => $request->Nombre,
            'Apellido' => $request->Apellido,
        ]);
        
        return redirect()->back()->with('success', 'Acompañante agregado correctamente.');
    }

    /**
     * Liberar habitación (cambiar de gris a verde)
     */
    public function liberarHabitacion($idHabitacion)
    {
        $user = Auth::user();
        
        $habitacion = Habitacion::findOrFail($idHabitacion);
        
        if ($user->IdRol != 4 && $habitacion->IdHotel != $user->IdHotel) {
            return redirect()->route('reservas.index')
                             ->with('error', 'No tienes permiso para liberar esta habitación.');
        }
        
        $habitacion->update(['IdEstadoHabitacion' => 1]);
        
        return redirect()->route('reservas.index')
                         ->with('success', 'Habitación liberada y lista para usar.');
    }


    /**
     * Eliminar un consumo de reserva con registro en movimientos de inventario
     */
    public function eliminarConsumo(Request $request, $id)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'motivo' => 'required|string|min:10|max:500',
        ], [
            'motivo.required' => 'Debe especificar un motivo para la eliminación.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $consumo = ConsumoReserva::with(['producto', 'reserva'])->findOrFail($id);
        $reserva = $consumo->reserva;
        $producto = $consumo->producto;
        
        if ($user->IdRol != 4) {
            $habitacionHotel = $reserva->detalles->first()->habitacion->IdHotel ?? null;
            if ($habitacionHotel != $user->IdHotel) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para eliminar este consumo.'
                ], 403);
            }
        }
        
        if ($consumo->EstadoPago) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un consumo que ya fue pagado.'
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            MovimientoInventario::create([
                'IdProducto' => $producto->IdProducto,
                'IdUsuario' => $user->IdUsuario,
                'IdReserva' => $reserva->IdReserva,
                'tipo' => 'devolucion',
                'cantidad' => $consumo->Cantidad,
                'precio_unitario' => $consumo->PrecioVenta,
                'observacion' => 'DEVOLUCIÓN - Reserva #' . $reserva->IdReserva . ' | Producto: ' . $producto->Nombre . ' | Motivo: ' . $request->motivo,
                'fecha_movimiento' => now(),
            ]);
            
            $producto->increment('StockActual', $consumo->Cantidad);
            $consumo->delete();
            
            $totalHospedaje = $reserva->total_hospedaje;
            $totalConsumos = $reserva->total_consumos;
            $adelantos = $reserva->detalles->sum('PagosAdelantados');
            $descuentos = $reserva->detalles->sum('Descuento');
            
            $reserva->update([
                'TotalReserva' => $totalHospedaje + $totalConsumos - $adelantos - $descuentos
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Consumo eliminado correctamente. Stock devuelto: ' . $consumo->Cantidad . ' unidad(es) de ' . $producto->Nombre . '.',
                'producto' => $producto->Nombre,
                'cantidad_devuelta' => $consumo->Cantidad,
                'nuevo_stock' => $producto->StockActual,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar consumo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vista de liquidación previa al checkout
     */
    public function liquidacion($id)
    {
        $user = Auth::user();
        
        $reserva = Reserva::with([
            'huesped', 
            'canal', 
            'detalles.habitacion.tipo', 
            'detalles.acompanantes', 
            'consumos.producto'
        ])->findOrFail($id);
        
        // Verificar acceso
        if ($user->IdRol != 4) {
            $habitacionHotel = $reserva->detalles->first()->habitacion->IdHotel ?? null;
            if ($habitacionHotel != $user->IdHotel) {
                return redirect()->route('reservas.index')
                                ->with('error', 'No tienes permiso para acceder a esta reserva.');
            }
        }
        
        // Verificar que la reserva esté en estado Check-in
        if ($reserva->Estado !== 'Check-in') {
            return redirect()->route('reservas.edit', $reserva->IdReserva)
                            ->with('error', 'Solo se puede liquidar una reserva en estado Check-in.');
        }
        
        // Obtener formas de pago activas
        $formasPago = \App\Models\FormaPago::where('activo', true)->get();
        
        // Calcular totales
        $totalHospedaje = $reserva->total_hospedaje;
        $totalConsumos = $reserva->total_consumos;
        $subtotal = $totalHospedaje + $totalConsumos;
        $igv = $subtotal * 0.18;
        $adelantos = $reserva->detalles->sum('PagosAdelantados');
        $descuentos = $reserva->detalles->sum('Descuento');
        $totalPagar = $subtotal - $adelantos - $descuentos;
        
        // Consumos pendientes
        $consumosPendientes = $reserva->consumos->where('EstadoPago', false);
        $consumosPagados = $reserva->consumos->where('EstadoPago', true);
        
        return view('reservas.liquidacion', compact(
            'reserva',
            'formasPago',
            'totalHospedaje',
            'totalConsumos',
            'subtotal',
            'igv',
            'adelantos',
            'descuentos',
            'totalPagar',
            'consumosPendientes',
            'consumosPagados'
        ));
    }

    /**
     * Procesar el checkout y generar comprobante
     */
    public function procesarCheckout(Request $request, $id)
    {
        $user = Auth::user();
        $reserva = Reserva::with(['detalles', 'consumos'])->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'TipoComprobante' => 'required|in:Boleta,Factura',
            'IdFormaPago' => 'required|exists:formas_pago,IdFormaPago',
            'MontoRecibido' => 'required|numeric|min:0',
            'NumeroDocumento' => 'nullable|string|max:20',
            'RazonSocial' => 'nullable|string|max:200',
            'Direccion' => 'nullable|string|max:200',
            'Comentario' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Calcular totales
            $totalHospedaje = $reserva->total_hospedaje;
            $totalConsumos = $reserva->total_consumos;
            $subtotal = $totalHospedaje + $totalConsumos;
            $igv = $subtotal * 0.18;
            $adelantos = $reserva->detalles->sum('PagosAdelantados');
            $descuentos = $reserva->detalles->sum('Descuento');
            $totalPagar = $subtotal - $adelantos - $descuentos;
            
            $montoRecibido = $request->MontoRecibido;
            $vuelto = max(0, $montoRecibido - $totalPagar);
            
            // Generar número de comprobante
            $ultimoComprobante = Comprobante::where('Tipo', $request->TipoComprobante)
                ->orderBy('IdComprobante', 'desc')
                ->first();
            $numero = $ultimoComprobante ? intval($ultimoComprobante->Numero) + 1 : 1;
            
            // Crear comprobante
            $comprobante = Comprobante::create([
                'IdReserva' => $reserva->IdReserva,
                'IdFormaPago' => $request->IdFormaPago,
                'IdUsuario' => $user->IdUsuario,
                'Tipo' => $request->TipoComprobante,
                'Serie' => $request->TipoComprobante === 'Factura' ? 'F001' : 'B001',
                'Numero' => str_pad($numero, 8, '0', STR_PAD_LEFT),
                'FechaEmision' => now(),
                'Subtotal' => $montoRecibido,
                'IGV' => $igv,
                'Total' => $totalPagar,
            ]);
            
            // Registrar movimiento en caja (ingreso)
            MovimientoCaja::create([
                'IdUsuario' => $user->IdUsuario,
                'IdComprobante' => $comprobante->IdComprobante,
                'tipo' => 'ingreso',
                'concepto' => 'Pago de reserva #' . $reserva->IdReserva . ' - ' . $request->TipoComprobante . ' ' . $comprobante->Serie . '-' . $comprobante->Numero,
                'monto' => $montoRecibido,
                'fecha_movimiento' => now(),
                'referencia' => $comprobante->Serie . '-' . $comprobante->Numero,
                'observacion' => 'Checkout reserva #' . $reserva->IdReserva . '. Vuelto: S/ ' . number_format($vuelto, 2) . '. ' . ($request->Comentario ?? ''),
            ]);
            
            // Si hay vuelto, registrar egreso
            if ($vuelto > 0) {
                MovimientoCaja::create([
                    'IdUsuario' => $user->IdUsuario,
                    'IdComprobante' => $comprobante->IdComprobante,
                    'tipo' => 'egreso',
                    'concepto' => 'Vuelto reserva #' . $reserva->IdReserva,
                    'monto' => $vuelto,
                    'fecha_movimiento' => now(),
                    'referencia' => $comprobante->Serie . '-' . $comprobante->Numero,
                    'observacion' => 'Vuelto entregado al cliente',
                ]);
            }
            
            // Cambiar estado de la reserva
            $reserva->update([
                'Estado' => 'Finalizada',
                'TotalReserva' => $totalPagar
            ]);
            
            // Cambiar estado de las habitaciones a Limpieza (4)
            foreach ($reserva->detalles as $detalle) {
                $detalle->habitacion->update(['IdEstadoHabitacion' => 4]);
            }
            
            // Marcar consumos como pagados
            foreach ($reserva->consumos as $consumo) {
                $consumo->update(['EstadoPago' => true]);
            }
            
            DB::commit();
            
            return redirect()->route('comprobantes.show', $comprobante->IdComprobante)
                     ->with('success', 'Checkout procesado correctamente.');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', 'Error al procesar checkout: ' . $e->getMessage())
                            ->withInput();
        }
    }


    /**
     * Mostrar comprobante después del checkout
     */
    public function verComprobante($id)
    {
        $comprobante = Comprobante::with([
            'reserva.huesped',
            'reserva.detalles.habitacion.hotel',
            'reserva.consumos.producto',
            'formaPago',
            'usuario'
        ])->findOrFail($id);
        
        return view('reservas.comprobante', compact('comprobante'));
    }

    /**
     * Descargar comprobante en PDF
     */
    public function descargarComprobantePDF($id)
    {
        $comprobante = Comprobante::with([
            'reserva.huesped',
            'reserva.detalles.habitacion.hotel',
            'reserva.consumos.producto',
            'formaPago',
            'usuario'
        ])->findOrFail($id);
        
        $pdf = Pdf::loadView('reservas.pdf.comprobante', compact('comprobante'));
        
        // TAMAÑO TICKET 80mm x altura automática
        // 80mm = 226.77 puntos
        // La altura se ajustará automáticamente según el contenido
        $pdf->setPaper([0, 0, 226.77, 500], 'portrait');
        
        return $pdf->download('comprobante-' . $comprobante->Serie . '-' . $comprobante->Numero . '.pdf');
    }
}