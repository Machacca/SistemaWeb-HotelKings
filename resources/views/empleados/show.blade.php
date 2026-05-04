@extends('layouts.app')

@section('title', 'Detalles del Empleado')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Detalles del Empleado</h1>
            <p class="text-muted mb-0">Información completa del empleado</p>
        </div>
        
        <a href="{{ route('empleados.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Listado
        </a>
    </div>

    {{-- Tarjeta con Encabezado Negro --}}
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 25px 25px 0 0;">
                <div>
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-circle mr-2 text-warning"></i> {{ $empleado->nombres }} {{ $empleado->apellidos }}
                    </h5>
                    <small class="text-muted">Información del empleado registrado</small>
                </div>
                <span class="badge px-3 py-2 {{ $empleado->activo ? 'badge-success' : 'badge-secondary' }}">
                    {{ $empleado->activo ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </div>

            {{-- Cuerpo de la tarjeta --}}
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NOMBRES</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->nombres }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">APELLIDOS</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->apellidos }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">PUESTO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            <span class="badge badge-light px-3 py-2">
                                <i class="fas fa-briefcase mr-1 text-warning"></i>
                                {{ $empleado->puesto }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE INGRESO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">TIPO DE DOCUMENTO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->tipo_documento ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NÚMERO DE DOCUMENTO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->numero_documento ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">TELÉFONO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->telefono ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">EMAIL PERSONAL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->email_personal ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">USUARIO ASOCIADO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            @if($empleado->usuario)
                                <span class="badge badge-info" style="background-color: #17a2b8;">
                                    <i class="fas fa-key mr-1"></i> {{ $empleado->usuario->Username }}
                                </span>
                                <small class="text-muted ml-2">({{ $empleado->usuario->rol->NombreRol ?? 'Sin rol' }})</small>
                            @else
                                <span class="text-muted">No tiene usuario asignado</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            <i class="fas fa-building mr-1 text-warning"></i> {{ $empleado->hotel->Nombre ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE REGISTRO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->created_at ? $empleado->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ÚLTIMA ACTUALIZACIÓN</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $empleado->updated_at ? $empleado->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Información adicional si está inactivo --}}
                @if(!$empleado->activo)
                    <div class="alert alert-warning text-center mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Este empleado está INACTIVO
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('empleados.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-times mr-2 text-danger"></i> Cerrar
                    </a>
                    
                    @if(in_array(auth()->user()->IdRol, [1, 4]) && $empleado->activo)
                        <a href="{{ route('empleados.edit', $empleado->IdEmpleado) }}" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Empleado
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .badge-success {
        background-color: #2e7d32;
        color: white;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection