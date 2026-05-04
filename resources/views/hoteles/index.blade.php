@extends('layouts.app')

@section('title', 'Hoteles')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Hoteles</h1>
            <p class="text-muted mb-0">Gestión de sedes y sucursales</p>
        </div>
        
        @if(auth()->user()->IdRol == 4)
            <a href="{{ route('hoteles.create') }}" 
               class="btn btn-dark rounded-pill px-5 py-3 shadow-sm font-weight-bold" 
               style="font-size: 1rem;">
                <i class="fas fa-plus mr-2 text-warning"></i> Nuevo Hotel
            </a>
        @endif
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" id="formFiltros" class="row align-items-end">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">BUSCAR</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           class="form-control rounded-pill border-0 bg-light px-4" 
                           placeholder="Nombre del hotel...">
                </div>
                
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                    <select name="estado" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="activos" {{ request('estado', 'activos') == 'activos' ? 'selected' : '' }}>✅ Activos</option>
                        <option value="inactivos" {{ request('estado') == 'inactivos' ? 'selected' : '' }}>❌ Inactivos</option>
                        <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>📋 Todos</option>
                    </select>
                </div>

                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold shadow-sm w-100">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TARJETAS DE HOTELES --}}
    <div class="row">
        @forelse($hoteles as $hotel)
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card hotel-card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%); transition: transform 0.3s ease, box-shadow 0.3s ease; {{ !$hotel->activo ? 'opacity: 0.75;' : '' }}">
                    <div class="card-body p-4">
                        {{-- Código del hotel --}}
                        <div class="text-center mb-3">
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #d4af37 0%, #b38f2a 100%); color: #1a1a1a; font-size: 1rem; font-weight: 700;">
                                {{ $hotel->codigo ?? 'N/A' }}
                            </span>
                        </div>
                        
                        {{-- Nombre del hotel --}}
                        <h3 class="text-center mb-3" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #d4af37;">
                            {{ $hotel->Nombre }}
                        </h3>
                        
                        {{-- Estado --}}
                        <div class="text-center mb-3">
                            @if($hotel->activo)
                                <span class="badge px-3 py-2" style="background-color: #2e7d32; color: white;">ACTIVO</span>
                            @else
                                <span class="badge px-3 py-2" style="background-color: #6c757d; color: white;">INACTIVO</span>
                            @endif
                        </div>
                        
                        {{-- Dirección --}}
                        @if($hotel->Direccion)
                            <div class="d-flex align-items-center mb-2 text-white-50">
                                <i class="fas fa-map-marker-alt mr-3" style="color: #d4af37; width: 20px;"></i>
                                <span style="font-size: 0.85rem;">{{ $hotel->Direccion }}</span>
                            </div>
                        @endif
                        
                        {{-- Teléfono --}}
                        @if($hotel->Telefono)
                            <div class="d-flex align-items-center mb-2 text-white-50">
                                <i class="fas fa-phone mr-3" style="color: #d4af37; width: 20px;"></i>
                                <span style="font-size: 0.85rem;">{{ $hotel->Telefono }}</span>
                            </div>
                        @endif
                        
                        {{-- Estadísticas --}}
                        <div class="row mt-4 pt-2 border-top border-secondary">
                            <div class="col-6 text-center">
                                <div class="small text-muted">Usuarios</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $hotel->usuarios()->count() }}</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="small text-muted">Habitaciones</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $hotel->habitaciones()->count() }}</div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Botones de acción --}}
                    <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('hoteles.show', $hotel->IdHotel) }}" 
                               class="btn btn-sm px-4 py-2" 
                               style="background: rgba(212, 175, 55, 0.1); color: #d4af37; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 50px;">
                                <i class="fas fa-eye mr-2"></i> Ver
                            </a>
                            
                            @if(auth()->user()->IdRol == 4)
                                <a href="{{ route('hoteles.edit', $hotel->IdHotel) }}" 
                                   class="btn btn-sm px-4 py-2" 
                                   style="background: rgba(212, 175, 55, 0.1); color: #d4af37; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 50px;">
                                    <i class="fas fa-edit mr-2"></i> Editar
                                </a>
                            @endif
                            
                            @if($hotel->activo)
                                @if(auth()->user()->IdRol == 4)
                                    <button type="button" 
                                            class="btn btn-sm px-4 py-2 btn-eliminar"
                                            data-url="{{ route('hoteles.destroy', $hotel->IdHotel) }}"
                                            data-nombre="{{ $hotel->Nombre }}"
                                            style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); border-radius: 50px;">
                                        <i class="fas fa-trash-alt mr-2"></i> Desactivar
                                    </button>
                                @endif
                            @else
                                @if(auth()->user()->IdRol == 4)
                                    <button type="button" 
                                            class="btn btn-sm px-4 py-2 btn-reactivar"
                                            data-url="{{ route('hoteles.reactivar', $hotel->IdHotel) }}"
                                            data-nombre="{{ $hotel->Nombre }}"
                                            style="background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 50px;">
                                        <i class="fas fa-undo-alt mr-2"></i> Reactivar
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <style>
                .hotel-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
                }
                .hotel-card:hover .btn {
                    background: rgba(212, 175, 55, 0.2) !important;
                }
                .gap-3 {
                    gap: 0.75rem;
                }
            </style>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-hotel fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No hay hoteles que coincidan con los filtros.</p>
                    @if(auth()->user()->IdRol == 4)
                        <a href="{{ route('hoteles.create') }}" class="btn btn-dark rounded-pill px-4">
                            <i class="fas fa-plus mr-2"></i> Crear primer hotel
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // ELIMINAR (DESACTIVAR) - Solo Master
    // ============================================
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let nombre = this.dataset.nombre;
            
            SwalConfirmacion(
                '¿Desactivar hotel "' + nombre + '"?',
                'El hotel quedará como INACTIVO. Puedes reactivarlo desde el filtro de Inactivos.',
                'warning',
                'Sí, desactivar'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            SwalToast('success', data.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            SwalError('Error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        SwalError('Error', 'Ocurrió un error al desactivar el hotel');
                    });
                }
            });
        });
    });

    // ============================================
    // REACTIVAR - Solo Master
    // ============================================
    document.querySelectorAll('.btn-reactivar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let nombre = this.dataset.nombre;
            
            SwalConfirmacionVerde(
                '¿Reactivar hotel "' + nombre + '"?',
                'El hotel volverá a estar disponible en el sistema.',
                'Sí, reactivar'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            SwalToast('success', data.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            SwalError('Error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        SwalError('Error', 'Ocurrió un error al reactivar el hotel');
                    });
                }
            });
        });
    });

});
</script>

<style>
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
</style>

@endsection