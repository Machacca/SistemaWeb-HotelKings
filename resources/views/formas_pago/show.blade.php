@extends('layouts.app')

@section('title', 'Detalles de Forma de Pago')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Detalles de Forma de Pago</h1>
            <p class="text-muted mb-0">Información completa del método de pago</p>
        </div>
        
        <a href="{{ route('formas-pago.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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
                        <i class="fas fa-credit-card mr-2 text-warning"></i> {{ $formaPago->Nombre }}
                    </h5>
                    <small class="text-muted">Información del método de pago</small>
                </div>
                <span class="badge px-3 py-2 {{ $formaPago->activo ? 'badge-success' : 'badge-secondary' }}">
                    {{ $formaPago->activo ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </div>

            {{-- Cuerpo de la tarjeta --}}
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">NOMBRE</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $formaPago->Nombre }}
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN</label>
                        <div class="form-control rounded-15 bg-light border-0 px-4 py-3" style="background-color: #e9ecef; height: auto;">
                            {{ $formaPago->descripcion ?? 'Sin descripción' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE REGISTRO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $formaPago->created_at ? $formaPago->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ÚLTIMA ACTUALIZACIÓN</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $formaPago->updated_at ? $formaPago->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Información adicional si está inactivo --}}
                @if(!$formaPago->activo)
                    <div class="alert alert-warning text-center mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Esta forma de pago está INACTIVA
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('formas-pago.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-times mr-2 text-danger"></i> Cerrar
                    </a>
                    
                    @if(in_array(auth()->user()->IdRol, [1, 4]) && $formaPago->activo)
                        <a href="{{ route('formas-pago.edit', $formaPago->IdFormaPago) }}" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-15 {
        border-radius: 15px !important;
    }
    
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