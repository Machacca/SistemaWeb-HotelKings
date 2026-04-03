<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\DetalleReserva;
use App\Models\Habitacion;
use App\Models\Huesped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * INDEX: Lista las reservas actuales.
     */
    public function index()
    {
        // Traemos la reserva con su huésped y los detalles de las habitaciones
        $reservas = Reserva::with(['huesped', 'detalles.habitacion'])->get();
        return view('reservas.index', compact('reservas'));
    }

    /**
     * CREATE: Prepara los datos para el formulario de reserva.
     */
    public function create()
    {
        $huespedes = Huesped::all();
        // Solo mostramos habitaciones que estén "Disponibles" (Estado 1)
        $habitaciones = Habitacion::where('IdEstadoHabitacion', 1)->get();
        
        return view('reservas.create', compact('huespedes', 'habitaciones'));
    }

    /**
     * STORE: La lógica más importante del sistema.
     */
    public function store(Request $request)
    {
        $request->validate([
            'IdHuesped' => 'required',
            'IdHabitacion' => 'required',
            'FechaEntrada' => 'required|date',
            'FechaSalida' => 'required|date|after:FechaEntrada',
            'PrecioNoche' => 'required|numeric'
        ]);

        // Iniciamos una transacción: Todo se guarda o nada se guarda
        DB::beginTransaction();

        try {
            // 1. Crear la Reserva (Cabecera)
            $reserva = Reserva::create([
                'IdHuesped' => $request->IdHuesped,
                'IdHotel' => 1, 
                'IdCanal' => 1,
                'FechaReserva' => now(),
                'Estado' => 'Activa',
                'TotalReserva' => 0 
            ]);

            // 2. Crear el Detalle de la Reserva
            DetalleReserva::create([
                'IdReserva' => $reserva->IdReserva,
                'IdHabitacion' => $request->IdHabitacion,
                'FechaEntrada' => $request->FechaEntrada,
                'FechaSalida' => $request->FechaSalida,
                'PrecioNoche' => $request->PrecioNoche,
            ]);

            // 3. CAMBIAR ESTADO DE HABITACIÓN: De Disponible (1) a Ocupada (2)
            $habitacion = Habitacion::find($request->IdHabitacion);
            $habitacion->IdEstadoHabitacion = 2; 
            $habitacion->save();

            DB::commit(); // Confirmamos los cambios en la DB
            return redirect()->route('reservas.index')->with('success', '¡Reserva creada y habitación ocupada!');

        } catch (\Exception $e) {
            DB::rollback(); // Si algo falló, deshacemos todo
            return back()->with('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    /**
     * SHOW: Ver el "Check-out" o resumen de cuenta.
     */
    public function show($id)
    {
        $reserva = Reserva::with(['huesped', 'detalles.habitacion', 'consumos.producto'])->findOrFail($id);
        return view('reservas.show', compact('reserva'));
    }
}