<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\Huesped;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cálculo de Ocupación
        $totalHabitaciones = Habitacion::count();
        $ocupadas = Habitacion::where('IdEstadoHabitacion', 2)->count(); // 2: Ocupada según tu migración
        $porcentajeOcupacion = $totalHabitaciones > 0 ? round(($ocupadas / $totalHabitaciones) * 100) : 0;

        // 2. Próximas Entradas (Reservas para hoy)
        // Usamos eager loading con 'huesped' definido en tu modelo Reserva
        $proximasEntradas = Reserva::with('huesped') 
            ->whereDate('FechaReserva', now()) 
            ->where('Estado', 'Confirmada')
            ->get();

        // 3. Estado de Habitaciones para el panel derecho
        $habitacionesDisponibles = Habitacion::where('IdEstadoHabitacion', 1)->count();
        $habitacionesMantenimiento = Habitacion::where('IdEstadoHabitacion', 3)->count();

        return view('dashboard', compact(
            'porcentajeOcupacion', 
            'proximasEntradas', 
            'habitacionesDisponibles', 
            'ocupadas', 
            'habitacionesMantenimiento'
        ));
    }
}