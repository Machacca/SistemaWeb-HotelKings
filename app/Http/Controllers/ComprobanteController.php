<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprobanteController extends Controller
{
    /**
     * INDEX: Ver historial de facturación.
     */
    public function index()
    {
        $comprobantes = Comprobante::with('reserva.huesped')->orderBy('IdComprobante', 'desc')->get();
        return view('comprobantes.index', compact('comprobantes'));
    }

    /**
     * STORE: Generar el pago final (Check-out).
     */
    public function store(Request $request)
    {
        $request->validate([
            'IdReserva' => 'required',
            'TipoComprobante' => 'required', // Boleta o Factura
        ]);

        // 1. Obtener la reserva con sus noches y consumos
        $reserva = Reserva::with(['detalles', 'consumos'])->findOrFail($request->IdReserva);

        // 2. Calcular Totales
        $totalNoches = $reserva->detalles->sum(function($d) {
            // Diferencia de días entre entrada y salida
            $dias = \Carbon\Carbon::parse($d->FechaEntrada)->diffInDays($d->FechaSalida);
            return ($dias == 0 ? 1 : $dias) * $d->PrecioNoche;
        });

        $totalConsumos = $reserva->consumos->sum(function($c) {
            return $c->Cantidad * $c->PrecioVenta;
        });

        $montoTotal = $totalNoches + $totalConsumos;
        $igv = $montoTotal * 0.18; // 18% de impuesto (ejemplo Perú)
        $subtotal = $montoTotal - $igv;

        // 3. Guardar Comprobante
        DB::transaction(function () use ($reserva, $request, $subtotal, $igv, $montoTotal) {
            Comprobante::create([
                'IdReserva' => $reserva->IdReserva,
                'TipoComprobante' => $request->TipoComprobante,
                'Serie' => ($request->TipoComprobante == 'Factura' ? 'F001' : 'B001'),
                'Correlativo' => Comprobante::count() + 1,
                'FechaEmision' => now(),
                'Subtotal' => $subtotal,
                'IGV' => $igv,
                'Total' => $montoTotal,
                'Estado' => 'Pagado'
            ]);

            // 4. Liberar la habitación (Volver a Estado 1: Disponible)
            foreach ($reserva->detalles as $detalle) {
                $detalle->habitacion->update(['IdEstadoHabitacion' => 1]);
            }

            // 5. Finalizar la Reserva
            $reserva->update(['Estado' => 'Finalizada', 'TotalReserva' => $montoTotal]);
        });

        return redirect()->route('comprobantes.index')->with('success', 'Pago procesado y habitación liberada.');
    }

    /**
     * SHOW: Para generar la vista de impresión (PDF o Ticket).
     */
    public function show($id)
    {
        $comprobante = Comprobante::with(['reserva.huesped', 'reserva.detalles.habitacion', 'reserva.consumos.producto'])->findOrFail($id);
        return view('comprobantes.show', compact('comprobante'));
    }
}