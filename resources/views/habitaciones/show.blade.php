@extends('layouts.app')

@section('title', 'Detalles de Habitación')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Detalles de Habitación</h1>
            <p class="text-muted mb-0">Información completa de la unidad</p>
        </div>
        
        <a href="{{ route('habitaciones.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Listado
        </a>
    </div>

    {{-- Tarjeta con Encabezado Negro --}}
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 25px 25px 0 0;">
                <div>
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-info-circle mr-2 text-warning"></i> Habitación {{ $habitacion->Numero }}
                    </h5>
                    <small class="text-muted">Información registrada en el sistema</small>
                </div>
                @php
                    $estados = [
                        1 => ['nombre' => 'Disponible', 'color' => '#28a745'],
                        2 => ['nombre' => 'Ocupada', 'color' => '#dc3545'],
                        3 => ['nombre' => 'Limpieza', 'color' => '#f1c40f'],
                        4 => ['nombre' => 'Mantenimiento', 'color' => '#6c757d'],
                    ];
                    $infoEstado = $estados[$habitacion->IdEstadoHabitacion] ?? ['nombre' => 'Desconocido', 'color' => '#343a40'];
                @endphp
                <span class="badge px-3 py-2" style="background-color: {{ $infoEstado['color'] }}; color: {{ $habitacion->IdEstadoHabitacion == 3 ? '#000000' : '#ffffff' }};">
                    {{ strtoupper($infoEstado['nombre']) }}
                </span>
            </div>

            {{-- Cuerpo de la tarjeta (modo solo lectura) --}}
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NÚMERO DE HABITACIÓN</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $habitacion->Numero }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">PISO / NIVEL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $habitacion->Piso }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">CATEGORÍA</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $habitacion->tipo->Nombre ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">TARIFA POR NOCHE</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            S/. {{ number_format($habitacion->tipo->Tarifa_base ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">SEDE / HOTEL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $habitacion->hotel->Nombre ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ESTADO ACTUAL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $infoEstado['nombre'] }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ESTADO ACTIVIDAD</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            @if($habitacion->activo)
                                <span class="text-success font-weight-bold">ACTIVA</span>
                            @else
                                <span class="text-danger font-weight-bold">INACTIVA</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE REGISTRO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $habitacion->created_at ? $habitacion->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Información adicional si está inactiva --}}
                @if(!$habitacion->activo)
                    <div class="alert alert-warning text-center mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Esta habitación está INACTIVA (dada de baja)
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('habitaciones.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-times mr-2 text-danger"></i> Cerrar
                    </a>
                    
                    @if(in_array(auth()->user()->IdRol, [1, 4]) && $habitacion->activo)
                        <a href="{{ route('habitaciones.edit', $habitacion->IdHabitacion) }}" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Habitación
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
    
    .rounded-pill {
        border-radius: 50px !important;
    }
</style>

@endsection