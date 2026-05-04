<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCaja;
use App\Models\Comprobante;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filtros
        $fechaInicio = $request->get('fecha_inicio', date('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', date('Y-m-d'));
        $usuarioId = $request->get('usuario_id');
        
        // Movimientos de caja (ingresos y egresos)
        $query = MovimientoCaja::with(['usuario', 'comprobante.reserva.huesped'])
            ->whereDate('fecha_movimiento', '>=', $fechaInicio)
            ->whereDate('fecha_movimiento', '<=', $fechaFin);
        
        if ($usuarioId) {
            $query->where('IdUsuario', $usuarioId);
        }
        
        $movimientos = $query->orderBy('fecha_movimiento', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->get();
        
        // Totales
        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('tipo', 'egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;
        
        // Ingresos por hospedaje (desde comprobantes)
        $ingresosHospedaje = Comprobante::with(['reserva.huesped', 'reserva.detalles.habitacion', 'formaPago', 'usuario'])
            ->whereDate('FechaEmision', '>=', $fechaInicio)
            ->whereDate('FechaEmision', '<=', $fechaFin)
            ->orderBy('FechaEmision', 'desc')
            ->get();
        
        // Gastos de caja (egresos sin comprobante)
        $gastosCaja = $movimientos->where('tipo', 'egreso');
        
        // Usuarios para filtro
        $usuarios = Usuario::where('activo', true)->orderBy('Username')->get();
        
        return view('caja.index', compact(
            'movimientos',
            'ingresosHospedaje',
            'gastosCaja',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'fechaInicio',
            'fechaFin',
            'usuarioId',
            'usuarios'
        ));
    }
    
    /**
     * Registrar nuevo movimiento manual (egreso o ingreso extra)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:200',
            'monto' => 'required|numeric|min:0.01',
            'fecha_movimiento' => 'required|date',
            'referencia' => 'nullable|string|max:100',
            'observacion' => 'nullable|string|max:500',
        ]);
        
        MovimientoCaja::create([
            'IdUsuario' => $user->IdUsuario,
            'tipo' => $validated['tipo'],
            'concepto' => $validated['concepto'],
            'monto' => $validated['monto'],
            'fecha_movimiento' => $validated['fecha_movimiento'],
            'referencia' => $validated['referencia'] ?? null,
            'observacion' => $validated['observacion'] ?? null,
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Movimiento registrado correctamente.'
            ]);
        }
        
        return redirect()->route('caja.index')
                         ->with('success', 'Movimiento registrado correctamente.');
    }

    /**
     * Vista de impresión del reporte de caja
     */
    public function imprimir(Request $request)
    {
        $user = Auth::user();
        
        $fechaInicio = $request->get('fecha_inicio', date('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', date('Y-m-d'));
        $usuarioId = $request->get('usuario_id');
        
        // Movimientos de caja
        $query = MovimientoCaja::with(['usuario', 'comprobante.reserva.huesped'])
            ->whereDate('fecha_movimiento', '>=', $fechaInicio)
            ->whereDate('fecha_movimiento', '<=', $fechaFin);
        
        if ($usuarioId) {
            $query->where('IdUsuario', $usuarioId);
        }
        
        $movimientos = $query->orderBy('fecha_movimiento', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('tipo', 'egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;
        
        // Ingresos por hospedaje
        $ingresosHospedaje = Comprobante::with(['reserva.huesped', 'reserva.detalles.habitacion', 'formaPago', 'usuario'])
            ->whereDate('FechaEmision', '>=', $fechaInicio)
            ->whereDate('FechaEmision', '<=', $fechaFin)
            ->orderBy('FechaEmision', 'desc')
            ->get();
        
        $gastosCaja = $movimientos->where('tipo', 'egreso');
        
        $hotel = null;
        if ($user->IdRol != 4) {
            $hotel = \App\Models\Hotel::find($user->IdHotel);
        }
        
        return view('caja.imprimir', compact(
            'movimientos',
            'ingresosHospedaje',
            'gastosCaja',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'fechaInicio',
            'fechaFin',
            'hotel'
        ));
    }
}