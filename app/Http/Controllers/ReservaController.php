<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\DetalleReserva;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function index()
    {
        $habitacionesPorPiso = Habitacion::with('tipo')->get()->groupBy('Piso');
        return view('reservas.index', compact('habitacionesPorPiso'));
    }

    public function create($id)
    {
        // Cargamos la relación 'tipo' para obtener 'Tarifa_base'
        $habitacionSeleccionada = Habitacion::with('tipo')->find($id);

        if (!$habitacionSeleccionada) {
            return redirect()->route('reservas.index')->with('error', 'Habitación no encontrada.');
        }

        $huespedes = Huesped::all();
        $productos = Producto::all(); 

        return view('reservas.create', compact('huespedes', 'habitacionSeleccionada', 'productos'));
    }

    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'IdHuesped' => 'required',
            'IdHabitacion' => 'required',
            'FechaEntrada' => 'required|date',
            'FechaSalida' => 'required|date|after_or_equal:FechaEntrada',
            'PrecioNoche' => 'required|numeric',
            'TipoRegistro' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $esReserva = $request->TipoRegistro === 'Reserva';

            // 2. Crear la Reserva (Cabecera)
            // Nota: Asegúrate que IdCanal e IdHotel tengan valores por defecto o pasarlos aquí
            $reserva = Reserva::create([
                'IdCanal' => 1, // Valor por defecto o del request
                'IdHuesped' => $request->IdHuesped,
                'FechaReserva' => now(),
                'Estado' => $esReserva ? 'Confirmada' : 'En Curso',
                'TotalReserva' => 0, // Se puede actualizar después del cálculo
            ]);

            // 3. Crear el Detalle de la Reserva
            $detalle = DetalleReserva::create([
                'IdReserva' => $reserva->IdReserva,
                'IdHabitacion' => $request->IdHabitacion,
                'FechaCheckIn' => $request->FechaEntrada,
                'FechaCheckOut' => $request->FechaSalida,
                'PrecioNoche' => $request->PrecioNoche,
                'PagosAdelantados' => $request->Adelanto ?? 0,
            ]);

            // 4. Guardar Acompañantes
            if ($request->has('acompanantes')) {
                foreach ($request->acompanantes as $acomp) {
                    if (!empty($acomp['Nombre'])) {
                        DB::table('acompanantes')->insert([
                            'IdDetalleReserva' => $detalle->IdDetalle,
                            'Nombre' => $acomp['Nombre'],
                            'Apellido' => $acomp['Apellido'],
                            'TipoDocumento' => 'DNI', // Valor por defecto
                            'NroDocumento' => $acomp['NroDocumento'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // 5. Guardar Consumos (Productos)
            if ($request->has('productos')) {
                foreach ($request->productos as $prod) {
                    if (!empty($prod['IdProducto'])) {
                        DB::table('consumo_reserva')->insert([
                            'IdReserva' => $reserva->IdReserva,
                            'IdProducto' => $prod['IdProducto'],
                            'Cantidad' => $prod['Cantidad'],
                            'FechaConsumo' => now(),
                            'EstadoPago' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // 6. Actualizar Estado de la Habitación
            // 2: Ocupada (Ingreso), 3: Reservada (Reserva)
            $habitacion = Habitacion::find($request->IdHabitacion);
            $habitacion->IdEstadoHabitacion = $esReserva ? 3 : 2; 
            $habitacion->save();

            DB::commit();
            return redirect()->route('reservas.index')->with('success', 'Registro guardado correctamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }


}