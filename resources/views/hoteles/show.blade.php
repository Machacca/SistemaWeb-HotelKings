@extends('layouts.app')

@section('title', 'Detalles del Hotel')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">{{ $hotel->Nombre }}</h1>
            <p class="text-muted mb-0">Información completa de la sede</p>
        </div>
        
        <div>
            @if(auth()->user()->IdRol == 4)
                <a href="{{ route('hoteles.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold mr-2">
                    <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver a Hoteles
                </a>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- Tarjeta destacada del hotel --}}
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card border-0 shadow-lg" style="border-radius: 25px; overflow: hidden; background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);">
            
            {{-- Encabezado de la tarjeta --}}
            <div class="px-4 pt-4 pb-2 text-center">
                <span class="badge px-4 py-2 mb-3" style="background: linear-gradient(135deg, #d4af37 0%, #b38f2a 100%); color: #1a1a1a; font-size: 1.1rem; font-weight: 700;">
                    <i class="fas fa-code-branch mr-2"></i> Código: {{ $hotel->codigo ?? 'N/A' }}
                </span>
                <h2 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #d4af37; font-size: 2.2rem;">
                    {{ $hotel->Nombre }}
                </h2>
                <div class="mt-2">
                    @if($hotel->activo)
                        <span class="badge px-3 py-2" style="background-color: #2e7d32; color: white; border-radius: 50px;">
                            <i class="fas fa-check-circle mr-1"></i> ACTIVO
                        </span>
                    @else
                        <span class="badge px-3 py-2" style="background-color: #6c757d; color: white; border-radius: 50px;">
                            <i class="fas fa-ban mr-1"></i> INACTIVO
                        </span>
                    @endif
                </div>
            </div>

            {{-- Cuerpo de la tarjeta --}}
            <div class="card-body p-4">
                <div class="row">
                    {{-- Dirección --}}
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-start">
                            <div class="icon-circle me-3" style="background: rgba(212, 175, 55, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-map-marker-alt" style="color: #d4af37; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase">DIRECCIÓN</h6>
                                <p class="mb-0 text-white">{{ $hotel->Direccion ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Teléfono --}}
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-start">
                            <div class="icon-circle me-3" style="background: rgba(212, 175, 55, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-phone" style="color: #d4af37; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase">TELÉFONO</h6>
                                <p class="mb-0 text-white">{{ $hotel->Telefono ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estadísticas --}}
                <div class="row mt-2 pt-3 border-top border-secondary">
                    <div class="col-md-4 text-center mb-3">
                        <div class="stat-box p-3 rounded" style="background: rgba(255, 255, 255, 0.05);">
                            <i class="fas fa-users fa-2x mb-2" style="color: #d4af37;"></i>
                            <div class="h2 mb-0 font-weight-bold text-white">{{ $totalUsuarios ?? $hotel->usuarios()->count() }}</div>
                            <div class="small text-muted">Usuarios activos</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div class="stat-box p-3 rounded" style="background: rgba(255, 255, 255, 0.05);">
                            <i class="fas fa-bed fa-2x mb-2" style="color: #d4af37;"></i>
                            <div class="h2 mb-0 font-weight-bold text-white">{{ $totalHabitaciones ?? $hotel->habitaciones()->count() }}</div>
                            <div class="small text-muted">Habitaciones totales</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div class="stat-box p-3 rounded" style="background: rgba(255, 255, 255, 0.05);">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #d4af37;"></i>
                            <div class="h2 mb-0 font-weight-bold text-white">{{ $hotel->created_at ? $hotel->created_at->format('d/m/Y') : 'N/A' }}</div>
                            <div class="small text-muted">Fecha de registro</div>
                        </div>
                    </div>
                </div>

                {{-- Información adicional si está inactivo --}}
                @if(!$hotel->activo)
                    <div class="alert alert-warning text-center mt-4" style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); color: #ffc107;">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Este hotel está INACTIVO
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-center gap-3 mt-4 pt-3">
                    @if(auth()->user()->IdRol == 4 || (auth()->user()->IdRol == 1 && $hotel->IdHotel == auth()->user()->IdHotel))
                        <a href="{{ route('hoteles.edit', $hotel->IdHotel) }}" 
                           class="btn btn-warning rounded-pill px-5 py-2 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Hotel
                        </a>
                    @endif
                    
                    @if(auth()->user()->IdRol == 1 && $hotel->IdHotel == auth()->user()->IdHotel)
                        <a href="{{ route('usuarios.index') }}" 
                           class="btn btn-outline-light rounded-pill px-5 py-2">
                            <i class="fas fa-user-shield mr-2"></i> Gestionar Personal
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
    
    .stat-box {
        transition: transform 0.3s ease;
    }
    
    .stat-box:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.1) !important;
    }
    
    .gap-3 {
        gap: 1rem;
    }
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
</style>

@endsection