@extends('layouts.app')

@section('title', 'Detalles del Huésped')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Detalles del Huésped</h1>
            <p class="text-muted mb-0">Información completa del huésped</p>
        </div>
        
        <a href="{{ route('huespedes.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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
                        <i class="fas fa-user-circle mr-2 text-warning"></i> Información del Huésped
                    </h5>
                    <small class="text-muted">Datos registrados en el sistema</small>
                </div>
                <span class="badge {{ $huesped->activo ? 'badge-success' : 'badge-secondary' }} px-3 py-2">
                    {{ $huesped->activo ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </div>

            {{-- Cuerpo de la tarjeta (modo solo lectura) --}}
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NOMBRES</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->Nombre }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">APELLIDOS</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->Apellido }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">TIPO DOCUMENTO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->TipoDocumento }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NÚMERO DOCUMENTO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->NroDocumento }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">CORREO ELECTRÓNICO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->Email ?? 'No registrado' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">TELÉFONO / CELULAR</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->Telefono ?? 'No registrado' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NACIONALIDAD</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->Nacionalidad ?? 'No especificada' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE REGISTRO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $huesped->created_at ? $huesped->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Información adicional: Total de reservas --}}
                <div class="bg-light p-3 rounded mt-3" style="border-left: 4px solid #d4af37;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line text-warning mr-3 fa-lg"></i>
                        <div>
                            <h6 class="mb-0 font-weight-bold">Total de reservas</h6>
                            <small class="text-muted">{{ $huesped->reservas->count() }} reserva(s) realizadas por este huésped</small>
                        </div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('huespedes.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-times mr-2 text-danger"></i> Cerrar
                    </a>
                    
                    @if(in_array(auth()->user()->IdRol, [1, 4]) && $huesped->activo)
                        <a href="{{ route('huespedes.edit', $huesped->IdHuesped) }}" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Huésped
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-pill-left { border-radius: 50px 0 0 50px !important; }
    .rounded-pill-right { border-radius: 0 50px 50px 0 !important; }
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
    
    .badge-success {
        background-color: #2e7d32;
        color: white;
        border-radius: 4px;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: white;
        border-radius: 4px;
    }
</style>

@endsection