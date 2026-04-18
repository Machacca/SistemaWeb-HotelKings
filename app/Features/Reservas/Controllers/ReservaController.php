<?php
// app/Features/Reservas/Controllers/ReservaController.php

namespace App\Features\Reservas\Controllers;

use App\Models\Reserva;
use App\Models\DetalleReserva;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Acompanante;
use App\Features\Huespedes\Services\HuespedService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservaController extends Controller
{
    protected $huespedService;
    
    public function __construct(HuespedService $huespedService)
    {
        $this->huespedService = $huespedService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $reservas = Reserva::with(['huesped', 'detalles.habitacion'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
        return view('reservas.index', compact('reservas'));
    }
    
    public function create()
    {
        $huespedes = Huesped::orderBy('Nombre')->get();
        $habitaciones = Habitacion::where('IdEstadoHabitacion', 1)->get();
        return view('reservas.create', compact('huespedes', 'habitaciones'));
    }
    

    public function store(Request $request)
{
    // 1. Validación
    $rules = [
        'IdHabitacion' => 'required|exists:habitaciones,IdHabitacion',
        'FechaCheckIn' => 'required|date',
        'FechaCheckOut' => 'required|date|after:FechaCheckIn',
        'PrecioNoche' => 'required|numeric|min:0',
    ];

    if (!$request->filled('IdHuesped')) {
        $rules['huesped_titular.Nombre'] = 'required|string|max:255';
        $rules['huesped_titular.Apellido'] = 'required|string|max:255';
        $rules['huesped_titular.NroDocumento'] = 'required|string|max:20';
    } else {
        $rules['IdHuesped'] = 'exists:huespedes,IdHuesped';
    }

    $request->validate($rules);

    DB::beginTransaction();
    try {
        // 2. Huésped Titular
        $idHuesped = $request->IdHuesped;
        
        if (!$idHuesped && $request->has('huesped_titular')) {
            $huesped = Huesped::create($request->huesped_titular);
            $idHuesped = $huesped->IdHuesped;
        }

        // 3. Crear Reserva
        $reserva = Reserva::create([
            'IdHuesped' => $idHuesped,
            'IdHotel' => 1,
            'IdCanal' => 1,
            'FechaReserva' => now(),
            'Estado' => 'Activa',
            'TotalReserva' => 0
        ]);

        // 4. Crear Detalle (¡IMPORTANTE! Guardar la referencia)
        $detalle = DetalleReserva::create([
            'IdReserva' => $reserva->IdReserva,
            'IdHabitacion' => $request->IdHabitacion,
            'FechaCheckIn' => $request->FechaCheckIn,
            'FechaCheckOut' => $request->FechaCheckOut,
            'PrecioNoche' => $request->PrecioNoche,
        ]);

        // 5. Calcular Total
        $fechaInicio = Carbon::parse($detalle->FechaCheckIn);
        $fechaFin = Carbon::parse($detalle->FechaCheckOut);
        $noches = $fechaInicio->diffInDays($fechaFin);
        $reserva->TotalReserva = $detalle->PrecioNoche * $noches;
        $reserva->save();

        // 6. Cambiar estado habitación
        Habitacion::find($request->IdHabitacion)->update(['IdEstadoHabitacion' => 2]);

        // 7. Guardar Acompañantes - CORREGIDO
        if ($request->has('acompanantes') && is_array($request->acompanantes)) {
            foreach ($request->acompanantes as $data) {
                // Solo si tiene nombre
                if (!empty($data['Nombre'])) { 
                    
                    // Si no tiene documento, usar string vacío para evitar error SQL
                    $nroDocumento = !empty($data['NroDocumento']) ? $data['NroDocumento'] : '';
                    
                    Acompanante::create([
                        'IdDetalleReserva' => $detalle->IdDetalle, // ← USAR IdDetalleReserva, no IdReserva
                        'Nombre' => $data['Nombre'],
                        'Apellido' => $data['Apellido'] ?? '',
                        'TipoDocumento' => $data['TipoDocumento'] ?? 'DNI',
                        'NroDocumento' => $nroDocumento,
                        'Parentesco' => $data['Parentesco'] ?? null,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('reservas.show', $reserva->IdReserva)
                    ->with('success', "Reserva creada exitosamente. Total: S/ {$reserva->TotalReserva}");

    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Error en store:', ['message' => $e->getMessage()]);
        return back()->with('error', 'Error al procesar la reserva: ' . $e->getMessage())->withInput();
    }
}

    public function show($id)
    {
        $reserva = Reserva::with(['huesped', 'detalles.habitacion', 'consumos.producto', 'acompanantes'])
                         ->findOrFail($id);
        return view('reservas.show', compact('reserva'));
    }
    
    public function edit($id)
    {
        $reserva = Reserva::with(['huesped', 'detalles.habitacion', 'acompanantes'])->findOrFail($id);
        
        if (!in_array($reserva->Estado, ['Activa', 'Check-in'])) {
            return redirect()->route('reservas.index')
                           ->with('error', 'No se puede editar una reserva en este estado');
        }
        
        $habitaciones = Habitacion::where('IdEstadoHabitacion', 1)->get();
        return view('reservas.edit', compact('reserva', 'habitaciones'));
    }
    
    public function update(Request $request, $id)
    {
        $reserva = Reserva::with(['detalles'])->findOrFail($id);
        
        if (!in_array($reserva->Estado, ['Activa', 'Check-in'])) {
            return redirect()->route('reservas.index')
                           ->with('error', 'No se puede editar una reserva en este estado');
        }
        
        DB::beginTransaction();
        
        try {
            if ($request->has('detalles')) {
                $totalReserva = 0;
                $detallesActuales = $reserva->detalles->pluck('IdDetalle')->toArray();
                $detallesRecibidos = [];
                
                foreach ($request->detalles as $detalleData) {
                    if (isset($detalleData['IdDetalle']) && !empty($detalleData['IdDetalle'])) {
                        $detalle = DetalleReserva::find($detalleData['IdDetalle']);
                        if ($detalle) {
                            $detalle->update([
                                'FechaCheckIn' => $detalleData['FechaCheckIn'],
                                'FechaCheckOut' => $detalleData['FechaCheckOut'],
                                'PrecioNoche' => $detalleData['PrecioNoche']
                            ]);
                            $detallesRecibidos[] = $detalle->IdDetalle;
                        }
                    } else {
                        $detalle = DetalleReserva::create([
                            'IdReserva' => $reserva->IdReserva,
                            'IdHabitacion' => $detalleData['IdHabitacion'],
                            'FechaCheckIn' => $detalleData['FechaCheckIn'],
                            'FechaCheckOut' => $detalleData['FechaCheckOut'],
                            'PrecioNoche' => $detalleData['PrecioNoche']
                        ]);
                        
                        $habitacion = Habitacion::find($detalleData['IdHabitacion']);
                        if ($habitacion && $habitacion->IdEstadoHabitacion == 1) {
                            $habitacion->IdEstadoHabitacion = 2;
                            $habitacion->save();
                        }
                        
                        $detallesRecibidos[] = $detalle->IdDetalle;
                    }
                    
                    // Calcular subtotal con Carbon
                    $entrada = Carbon::parse($detalleData['FechaCheckIn']);
                    $salida = Carbon::parse($detalleData['FechaCheckOut']);
                    $noches = $entrada->diffInDays($salida);
                    $totalReserva += $detalleData['PrecioNoche'] * $noches;
                }
                
                $detallesAEliminar = array_diff($detallesActuales, $detallesRecibidos);
                if ($request->has('eliminar_detalles')) {
                    $detallesAEliminar = array_merge($detallesAEliminar, $request->eliminar_detalles);
                }
                
                foreach ($detallesAEliminar as $detalleId) {
                    $detalle = DetalleReserva::find($detalleId);
                    if ($detalle) {
                        $habitacion = Habitacion::find($detalle->IdHabitacion);
                        if ($habitacion && $habitacion->IdEstadoHabitacion == 2) {
                            $otraReserva = DetalleReserva::where('IdHabitacion', $detalle->IdHabitacion)
                                ->where('IdReserva', '!=', $reserva->IdReserva)
                                ->whereHas('reserva', function($q) {
                                    $q->whereIn('Estado', ['Activa', 'Check-in']);
                                })->exists();
                            
                            if (!$otraReserva) {
                                $habitacion->IdEstadoHabitacion = 1;
                                $habitacion->save();
                            }
                        }
                        $detalle->delete();
                    }
                }
                
                $reserva->TotalReserva = $totalReserva;
            }
            
            if ($request->has('acompanantes')) {
                Acompanante::whereHas('detalleReserva', function($q) use ($reserva) {
                    $q->where('IdReserva', $reserva->IdReserva);
                })->delete();
                
                // Obtener el primer detalle (o el que corresponda)
                $detalle = $reserva->detalles->first();
                
                if ($detalle) {
                    foreach ($request->acompanantes as $acompananteData) {
                        if (!empty($acompananteData['Nombre'])) {
                            Acompanante::create([
                                'IdDetalleReserva' => $detalle->IdDetalle, // ← CORREGIDO
                                'Nombre' => $acompananteData['Nombre'],
                                'Apellido' => $acompananteData['Apellido'] ?? '',
                                'TipoDocumento' => $acompananteData['TipoDocumento'] ?? 'DNI',
                                'NroDocumento' => !empty($acompananteData['NroDocumento']) ? $acompananteData['NroDocumento'] : '',
                                'Parentesco' => $acompananteData['Parentesco'] ?? null,
                            ]);
                        }
                    }
                }
            }
            
            if ($request->has('Observaciones')) {
                $reserva->Observaciones = $request->Observaciones;
            }
            
            $reserva->save();
            
            DB::commit();
            
            return redirect()->route('reservas.show', $reserva->IdReserva)
                           ->with('success', 'Reserva actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
            
            if (!in_array($reserva->Estado, ['Activa', 'Check-in'])) {
                return back()->with('error', 'No se puede cancelar una reserva en este estado');
            }
            
            DB::beginTransaction();
            
            foreach ($reserva->detalles as $detalle) {
                $habitacion = Habitacion::find($detalle->IdHabitacion);
                if ($habitacion && $habitacion->IdEstadoHabitacion == 2) {
                    $habitacion->IdEstadoHabitacion = 1;
                    $habitacion->save();
                }
            }
            
            $reserva->Estado = 'Cancelada';
            $reserva->save();
            
            DB::commit();
            
            return redirect()->route('reservas.index')
                           ->with('success', 'Reserva cancelada exitosamente');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function checkin($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
            
            if ($reserva->Estado != 'Activa') {
                return response()->json(['success' => false, 'message' => 'La reserva no está activa'], 400);
            }
            
            $reserva->Estado = 'Check-in';
            $reserva->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function checkout($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
            
            if ($reserva->Estado != 'Check-in') {
                return response()->json(['success' => false, 'message' => 'La reserva no está en check-in'], 400);
            }
            
            DB::beginTransaction();
            
            foreach ($reserva->detalles as $detalle) {
                $habitacion = Habitacion::find($detalle->IdHabitacion);
                if ($habitacion) {
                    $habitacion->IdEstadoHabitacion = 1;
                    $habitacion->save();
                }
            }
            
            $totalConsumos = $reserva->consumos()->sum('Total') ?? 0;
            $totalAlojamiento = $reserva->detalles->sum(function($d) {
                $inicio = Carbon::parse($d->FechaCheckIn);
                $fin = Carbon::parse($d->FechaCheckOut);
                $noches = $inicio->diffInDays($fin);
                return $d->PrecioNoche * $noches;
            });
            
            $reserva->TotalReserva = $totalAlojamiento + $totalConsumos;
            $reserva->Estado = 'Check-out';
            $reserva->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'total' => $reserva->TotalReserva]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}