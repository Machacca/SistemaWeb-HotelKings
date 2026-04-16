@extends('layouts.app')

@section('title', 'Panel de Operaciones - Hotel Inka Kings')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #1a1a1a;">Panel de Operaciones</h1>
            <p class="text-muted">Resumen diario del Hotel Inka Kings • {{ now()->translatedFormat('d \d\e F, Y') }}</p>
        </div>
        <div class="text-right">
            <span class="badge badge-light border p-2" style="letter-spacing: 1px; color: #8a6d1a;">ESTADO: <span class="text-success">OPERATIVO</span></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="row mb-4 text-center">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <small class="text-muted font-weight-bold" style="font-size: 0.7rem;">OCUPACIÓN TOTAL</small>
                        <h2 class="m-0" style="font-size: 2.2rem;">{{ $porcentajeOcupacion }}%</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <small class="text-muted font-weight-bold" style="font-size: 0.7rem;">LLEGADAS HOY</small>
                        <h2 class="m-0" style="font-size: 2.2rem;">{{ $proximasEntradas->count() }}</h2>
                    </div>
                </div>
                </div>

            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 style="font-family: 'Playfair Display', serif;">Próximas Entradas</h4>
                    <a href="{{ route('reservas.index') }}" class="text-warning font-weight-bold" style="font-size: 0.7rem;">VER TODAS</a>
                </div>
                
                <div class="list-group list-group-flush">
                    @forelse($proximasEntradas as $reserva)
                    <div class="list-group-item d-flex align-items-center border-0 px-0 mb-3 bg-light rounded p-3">
                        <div class="text-center mr-4" style="width: 80px; border-right: 1px solid #ddd;">
                            <small class="text-muted d-block">ID</small>
                            <span class="font-weight-bold">#{{ $reserva->IdReserva }}</span>
                        </div>
                        <div class="flex-grow-1 ml-3">
                            <h6 class="mb-0">{{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</h6>
                            <small class="text-muted text-uppercase">Reserva: {{ $reserva->FechaReserva }}</small>
                        </div>
                        <div class="text-right mr-4">
                            <small class="text-muted d-block">ESTADO</small>
                            <span class="badge badge-warning px-3">{{ strtoupper($reserva->Estado) }}</span>
                        </div>
                        <button class="btn btn-outline-dark font-weight-bold px-4">REGISTRAR</button>
                    </div>
                    @empty
                    <p class="text-center text-muted py-4">No hay entradas programadas para hoy.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="background-color: #fcfcfc;">
                <h5 style="font-family: 'Playfair Display', serif;">Estado Habitaciones</h5>
                <hr>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Disponibles</span>
                        <span class="font-weight-bold">{{ $habitacionesDisponibles }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Ocupadas</span>
                        <span class="font-weight-bold">{{ $ocupadas }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 text-danger">
                        <span>Mantenimiento</span>
                        <span class="font-weight-bold">{{ $habitacionesMantenimiento }}</span>
                    </div>
                </div>
                <button class="btn btn-dark btn-block mt-auto font-weight-bold py-3" style="background-color: #121212;">PLANTA DE HABITACIONES</button>
            </div>
        </div>
    </div>
</div>
@endsection