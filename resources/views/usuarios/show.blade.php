@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Detalles del Usuario</h1>
            <p class="text-muted mb-0">Información completa del usuario</p>
        </div>
        
        <a href="{{ route('usuarios.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Listado
        </a>
    </div>

    {{-- Tarjeta con Encabezado Negro --}}
    <div style="max-width: 700px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 25px 25px 0 0;">
                <div>
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-circle mr-2 text-warning"></i> {{ $usuario->Username }}
                    </h5>
                    <small class="text-muted">Información del usuario registrado</small>
                </div>
                <span class="badge px-3 py-2 {{ $usuario->activo ? 'badge-success' : 'badge-secondary' }}">
                    {{ $usuario->activo ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </div>

            {{-- Cuerpo de la tarjeta --}}
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NOMBRE DE USUARIO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $usuario->Username }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">CORREO ELECTRÓNICO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $usuario->Email }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ROL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            <span class="badge badge-light px-3 py-2">
                                <i class="fas fa-tag mr-1 text-warning"></i>
                                {{ $usuario->rol->NombreRol ?? 'N/A' }}
                            </span>
                        </div>
                        @php
                            $descripcionesRol = [
                                4 => 'Acceso total al sistema. Puede ver y gestionar todos los hoteles, usuarios y configuraciones.',
                                1 => 'Gestiona su propio hotel: usuarios, habitaciones, reservas, productos y facturación. No puede ver otros hoteles.',
                                2 => 'Atención al cliente: puede crear reservas, hacer check-in/out, gestionar huéspedes y ver el calendario.',
                                3 => 'Facturación y caja: puede gestionar productos, consumos y comprobantes de pago.'
                            ];
                        @endphp
                        @if(isset($descripcionesRol[$usuario->IdRol]))
                            <div class="small text-muted mt-2 ml-2">
                                <i class="fas fa-info-circle mr-1"></i> {{ $descripcionesRol[$usuario->IdRol] }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            @if($usuario->hotel)
                                <i class="fas fa-building mr-1 text-warning"></i> {{ $usuario->hotel->Nombre }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            @if($usuario->activo)
                                <span class="text-success font-weight-bold">ACTIVO</span>
                            @else
                                <span class="text-danger font-weight-bold">INACTIVO</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE REGISTRO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ÚLTIMA ACTUALIZACIÓN</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Información adicional si está inactivo --}}
                @if(!$usuario->activo)
                    <div class="alert alert-warning text-center mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Este usuario está INACTIVO. No puede iniciar sesión.
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-times mr-2 text-danger"></i> Cerrar
                    </a>
                    
                    @if(in_array(auth()->user()->IdRol, [1, 4]) && $usuario->activo && auth()->user()->IdUsuario != $usuario->IdUsuario)
                        <a href="{{ route('usuarios.edit', $usuario->IdUsuario) }}" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Usuario
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
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection