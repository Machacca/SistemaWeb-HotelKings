<?php

namespace App\Features\Habitaciones\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\Hotel;
use App\Features\Habitaciones\Requests\StoreHabitacionRequest;
use App\Features\Habitaciones\Requests\UpdateHabitacionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HabitacionController extends Controller
{
    /**
     * Listado de habitaciones con filtros básicos
     */
    public function index(Request $request)
    {
        $query = Habitacion::with(['tipo', 'hotel']);

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('IdEstadoHabitacion', $request->estado);
        }
        if ($request->filled('hotel')) {
            $query->where('IdHotel', $request->hotel);
        }
        if ($request->filled('buscar')) {
            $query->where('Numero', 'like', "%{$request->buscar}%");
        }

        $habitaciones = $query->orderBy('Numero')->paginate(15);
        $tipos = TipoHabitacion::all();
        $hoteles = Hotel::all();

        return view('habitaciones.index', compact('habitaciones', 'tipos', 'hoteles'));
    }

    /**
     * Formulario para crear nueva habitación
     */
    public function create()
    {
        $tipos = TipoHabitacion::all();
        $hoteles = Hotel::all();
        $estados = [
            1 => 'Disponible',
            2 => 'Ocupada',
            3 => 'Mantenimiento'
        ];
        
        return view('habitaciones.create', compact('tipos', 'hoteles', 'estados'));
    }

    /**
     * Guardar nueva habitación
     */
    public function store(StoreHabitacionRequest $request)
    {
        $habitacion = Habitacion::create([
            'IdTipo' => $request->IdTipo,
            'IdHotel' => $request->IdHotel,
            'Numero' => strtoupper($request->Numero),
            'Piso' => $request->Piso,
            'IdEstadoHabitacion' => 1, // Siempre inicia como Disponible
        ]);

        // Auditoría básica
        \App\Models\Auditoria::create([
            'IdUsuario' => Auth::id(),
            'Accion' => 'Creó Habitación',
            'TablaAfectada' => 'habitaciones',
            'FechaHora' => now(),
            'IP' => $request->ip(),
        ]);

        return redirect()->route('habitaciones.index')
            ->with('success', "Habitación {$habitacion->Numero} creada correctamente.");
    }

    /**
     * Mostrar detalle de una habitación
     */
    public function show(Habitacion $habitacion)
    {
        $habitacion->load(['tipo', 'hotel']);
        return view('habitaciones.show', compact('habitacion'));
    }

    /**
     * Formulario para editar habitación
     */
    public function edit(Habitacion $habitacion)
    {
        $habitacion->load(['tipo', 'hotel']);
        $tipos = TipoHabitacion::all();
        $hoteles = Hotel::all();
        $estados = [
            1 => 'Disponible',
            2 => 'Ocupada',
            3 => 'Mantenimiento'
        ];
        
        return view('habitaciones.edit', compact('habitacion', 'tipos', 'hoteles', 'estados'));
    }

    /**
     * Actualizar habitación
     */
    public function update(UpdateHabitacionRequest $request, Habitacion $habitacion)
    {
        $habitacion->update([
            'IdTipo' => $request->IdTipo,
            'IdHotel' => $request->IdHotel,
            'Numero' => strtoupper($request->Numero),
            'Piso' => $request->Piso,
            'IdEstadoHabitacion' => $request->IdEstadoHabitacion,
        ]);

        // Auditoría
        \App\Models\Auditoria::create([
            'IdUsuario' => Auth::id(),
            'Accion' => 'Actualizó Habitación',
            'TablaAfectada' => 'habitaciones',
            'FechaHora' => now(),
            'IP' => $request->ip(),
        ]);

        return redirect()->route('habitaciones.index')
            ->with('success', "Habitación {$habitacion->Numero} actualizada.");
    }

    /**
     * Eliminar habitación (soft logic: solo si no tiene reservas activas)
     */
    public function destroy(Habitacion $habitacion)
    {
        // Validar que no tenga reservas activas
        $tieneReservas = $habitacion->detalleReservas()
            ->whereHas('reserva', fn($q) => $q->where('Estado', '!=', 'Finalizada'))
            ->exists();

        if ($tieneReservas) {
            return back()->with('error', 'No se puede eliminar: la habitación tiene reservas activas.');
        }

        $habitacion->delete();

        \App\Models\Auditoria::create([
            'IdUsuario' => Auth::id(),
            'Accion' => 'Eliminó Habitación',
            'TablaAfectada' => 'habitaciones',
            'FechaHora' => now(),
            'IP' => request()->ip(),
        ]);

        return redirect()->route('habitaciones.index')
            ->with('success', 'Habitación eliminada correctamente.');
    }

    /**
     * Acción rápida: Cambiar estado (Housekeeping)
     */
    public function updateEstado(Request $request, Habitacion $habitacion)
    {
        $request->validate([
            'IdEstadoHabitacion' => 'required|in:1,2,3'
        ]);

        $estadoAnterior = $habitacion->IdEstadoHabitacion;
        $habitacion->update(['IdEstadoHabitacion' => $request->IdEstadoHabitacion]);

        $nombres = [1 => 'Disponible', 2 => 'Ocupada', 3 => 'Mantenimiento'];
        
        \App\Models\Auditoria::create([
            'IdUsuario' => Auth::id(),
            'Accion' => "Cambio estado: {$nombres[$estadoAnterior]} → {$nombres[$request->IdEstadoHabitacion]}",
            'TablaAfectada' => 'habitaciones',
            'FechaHora' => now(),
            'IP' => $request->ip(),
        ]);

        return back()->with('success', "Estado actualizado a: {$nombres[$request->IdEstadoHabitacion]}");
    }
}