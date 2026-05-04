<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    /**
     * Muestra el calendario visual de habitaciones
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener mes y año (por defecto el actual)
        $mes = $request->get('mes', date('m'));
        $anio = $request->get('anio', date('Y'));
        
        // Validar mes y año
        $mes = max(1, min(12, intval($mes)));
        $anio = max(2020, min(2099, intval($anio)));
        
        // Crear fecha de inicio y fin del mes
        $fechaInicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
        
        // Obtener habitaciones
        $habitaciones = $this->getHabitaciones($user);
        
        // Obtener reservas del mes
        $reservas = $this->getReservas($fechaInicio, $fechaFin, $user);
        
        // Crear array de días del mes
        $diasMes = $this->getDiasMes($mes, $anio);
        
        // Construir estructura del calendario
        $calendario = $this->buildCalendario($habitaciones, $reservas, $diasMes);
        
        // Meses para el selector
        $meses = $this->getMesesArray();
        
        // Meses rápidos para navegación
        $mesesRapidos = $this->getMesesRapidos($mes, $anio);
        
        return view('calendario.index', compact(
            'calendario',
            'diasMes',
            'mes',
            'anio',
            'meses',
            'fechaInicio',
            'fechaFin',
            'mesesRapidos'
        ));
    }
    
    /**
     * Obtiene las habitaciones según el rol del usuario
     */
    private function getHabitaciones($user)
    {
        $query = Habitacion::with(['tipo', 'hotel']);
        
        if ($user->IdRol != 4) {
            $query->where('IdHotel', $user->IdHotel);
        } elseif (session('hotel_id')) {
            $query->where('IdHotel', session('hotel_id'));
        }
        
        return $query->where('activo', true)
                     ->orderBy('Piso')
                     ->orderBy('Numero')
                     ->get();
    }
    
    /**
     * Obtiene las reservas del mes
     */
    private function getReservas($fechaInicio, $fechaFin, $user)
    {
        return Reserva::whereIn('Estado', ['Reserva', 'Check-in'])
            ->whereHas('detalles', function($q) use ($fechaInicio, $fechaFin, $user) {
                $q->where(function($query) use ($fechaInicio, $fechaFin) {
                    $query->whereBetween('FechaCheckIn', [$fechaInicio, $fechaFin])
                          ->orWhereBetween('FechaCheckOut', [$fechaInicio, $fechaFin])
                          ->orWhere(function($subQuery) use ($fechaInicio, $fechaFin) {
                              $subQuery->where('FechaCheckIn', '<=', $fechaInicio)
                                       ->where('FechaCheckOut', '>=', $fechaFin);
                          });
                });
                
                // Filtrar por hotel según rol
                if ($user->IdRol != 4) {
                    $q->whereHas('habitacion', function($hq) use ($user) {
                        $hq->where('IdHotel', $user->IdHotel);
                    });
                } elseif (session('hotel_id')) {
                    $q->whereHas('habitacion', function($hq) {
                        $hq->where('IdHotel', session('hotel_id'));
                    });
                }
            })
            ->with(['detalles.habitacion', 'huesped'])
            ->get();
    }
    
    /**
     * Genera array de días del mes
     */
    private function getDiasMes($mes, $anio)
    {
        $dias = [];
        $totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        
        for ($i = 1; $i <= $totalDias; $i++) {
            $dias[] = Carbon::create($anio, $mes, $i);
        }
        
        return $dias;
    }
    
    /**
     * Construye la estructura del calendario
     */
    private function buildCalendario($habitaciones, $reservas, $diasMes)
    {
        $calendario = [];
        
        // Inicializar estructura
        foreach ($habitaciones as $habitacion) {
            $calendario[$habitacion->IdHabitacion] = [
                'habitacion' => $habitacion,
                'dias' => [],
            ];
            
            foreach ($diasMes as $dia) {
                $calendario[$habitacion->IdHabitacion]['dias'][$dia->day] = null;
            }
        }
        
        // Llenar con reservas
        foreach ($reservas as $reserva) {
            foreach ($reserva->detalles as $detalle) {
                $habId = $detalle->IdHabitacion;
                
                if (isset($calendario[$habId])) {
                    $checkIn = Carbon::parse($detalle->FechaCheckIn);
                    $checkOut = Carbon::parse($detalle->FechaCheckOut);
                    
                    foreach ($diasMes as $dia) {
                        if ($dia->between($checkIn, $checkOut)) {
                            $calendario[$habId]['dias'][$dia->day] = [
                                'reserva_id' => $reserva->IdReserva,
                                'huesped' => $reserva->huesped->Nombre . ' ' . $reserva->huesped->Apellido,
                                'estado' => $reserva->Estado,
                                'check_in' => $checkIn->format('Y-m-d'),
                                'check_out' => $checkOut->format('Y-m-d'),
                                'es_checkin' => $dia->format('Y-m-d') === $checkIn->format('Y-m-d'),
                                'es_checkout' => $dia->format('Y-m-d') === $checkOut->format('Y-m-d'),
                            ];
                        }
                    }
                }
            }
        }
        
        return $calendario;
    }
    
    /**
     * Array de meses en español
     */
    private function getMesesArray()
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
    }
    
    /**
     * Genera meses rápidos para navegación
     */
    private function getMesesRapidos($mes, $anio)
    {
        $meses = $this->getMesesArray();
        $rapidos = [];
        
        $fechaBase = Carbon::create($anio, $mes, 1);
        
        for ($i = -2; $i <= 3; $i++) {
            $fecha = $fechaBase->copy()->addMonths($i);
            $rapidos[] = [
                'mes' => $fecha->month,
                'anio' => $fecha->year,
                'nombre' => substr($meses[$fecha->month], 0, 3) . ' ' . $fecha->year,
                'activo' => ($fecha->month == $mes && $fecha->year == $anio)
            ];
        }
        
        return $rapidos;
    }
}