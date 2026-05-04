@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Habitaciones</h1>
            <p class="text-muted mb-0">
                @if(session('hotel_nombre'))
                    Sede: <span class="font-weight-bold text-dark">{{ session('hotel_nombre') }}</span>
                @else
                    Gestión global de inventario
                @endif
            </p>
        </div>
        
        @if(in_array(auth()->user()->IdRol, [1, 4]))
            <a href="{{ route('habitaciones.create') }}" 
               class="btn btn-dark rounded-pill px-5 py-3 shadow-sm font-weight-bold" 
               style="font-size: 1rem;">
                <i class="fas fa-plus mr-2 text-warning"></i> Nueva Habitación
            </a>
        @endif
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" id="formFiltros" class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">BUSCAR NÚMERO</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           class="form-control rounded-pill border-0 bg-light px-4" placeholder="Ej: 101...">
                </div>
                
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO OPERATIVO</label>
                    <select name="estado" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('estado')=='1'?'selected':'' }}>Disponible</option>
                        <option value="2" {{ request('estado')=='2'?'selected':'' }}>Ocupada</option>
                        <option value="3" {{ request('estado')=='3'?'selected':'' }}>Limpieza</option>
                        <option value="4" {{ request('estado')=='4'?'selected':'' }}>Mantenimiento</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO ACTIVIDAD</label>
                    <select name="activo" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="1" {{ request('activo', '1') == '1' ? 'selected' : '' }}>Activas</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivas</option>
                        <option value="" {{ request('activo') == '' && request('activo') !== '1' ? 'selected' : '' }}>Todas</option>
                    </select>
                </div>

                @if(auth()->user()->IdRol == 4)
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                    <select name="hotel" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="">Todos los hoteles</option>
                        @foreach($hoteles as $hotel)
                            <option value="{{ $hotel->IdHotel }}" {{ request('hotel') == $hotel->IdHotel ? 'selected' : '' }}>
                                {{ $hotel->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 text-right">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
                @else
                <div class="col-md-3 text-right">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="px-4 py-3 border-0">NÚMERO</th>
                        <th class="py-3 border-0">TIPO</th>
                        <th class="py-3 border-0 text-center">PISO</th>
                        @if(auth()->user()->IdRol == 4)
                            <th class="py-3 border-0">HOTEL</th>
                        @endif
                        <th class="py-3 border-0 text-center">ESTADO</th>
                        <th class="px-4 py-3 border-0 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($habitaciones as $hab)
                    @php
                        $estados = [
                            1 => ['Disponible', '#28a745', '#ffffff'],
                            2 => ['Ocupada', '#dc3545', '#ffffff'],
                            3 => ['Limpieza', '#f1c40f', '#000000'],
                            4 => ['Mantenimiento', '#6c757d', '#ffffff'],
                        ];
                        $est = $estados[$hab->IdEstadoHabitacion] ?? ['Desconocido', '#343a40', '#ffffff'];
                    @endphp
                    <tr class="{{ !$hab->activo ? 'bg-light text-muted' : '' }}">
                        <td class="px-4 py-4">
                            <span class="h5 font-weight-bold {{ $hab->activo ? 'text-dark' : 'text-muted' }} mb-0">{{ $hab->Numero }}</span>
                            @if(!$hab->activo)
                                <span class="badge badge-secondary ml-2">INACTIVA</span>
                            @endif
                        </td>
                        <td class="py-4">
                            <span class="badge badge-light px-3 py-2 text-uppercase" style="border-radius: 8px;">
                                {{ $hab->tipo->Nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-4 text-center">
                            <div class="d-inline-block bg-light rounded-circle font-weight-bold text-dark" style="width: 35px; height: 35px; line-height: 35px;">
                                {{ $hab->Piso }}
                            </div>
                        </td>
                        @if(auth()->user()->IdRol == 4)
                            <td class="py-4 text-muted font-weight-medium">
                                {{ $hab->hotel->Nombre ?? 'N/A' }}
                            </td>
                        @endif
                        <td class="py-4 text-center">
                            <span class="badge shadow-sm" 
                                style="background-color: {{ $est[1] }} !important; 
                                       color: {{ $est[2] }} !important; 
                                       border-radius: 12px; 
                                       padding: 8px 15px; 
                                       font-size: 0.75rem; 
                                       font-weight: 700;">
                                <i class="fas fa-circle mr-1" style="font-size: 0.5rem;"></i>
                                {{ strtoupper($est[0]) }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-right">
                            <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <a href="{{ route('habitaciones.show', $hab->IdHabitacion) }}" 
                                   class="btn btn-light btn-sm px-3" 
                                   title="Ver detalles"
                                   style="color: #d4af37;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(in_array(auth()->user()->IdRol, [1, 4]) && $hab->activo)
                                    <a href="{{ route('habitaciones.edit', $hab->IdHabitacion) }}" 
                                       class="btn btn-light btn-sm px-3" 
                                       title="Editar"
                                       style="color: #26a53b;">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if(in_array($hab->IdEstadoHabitacion, [3, 4]))
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-liberar"
                                                data-url="{{ route('habitaciones.liberar', $hab->IdHabitacion) }}"
                                                data-numero="{{ $hab->Numero }}"
                                                title="Habilitar habitación"
                                                style="color: #28a745;">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif

                                    <button type="button" 
                                            class="btn btn-light btn-sm px-3 btn-eliminar"
                                            data-url="{{ route('habitaciones.destroy', $hab->IdHabitacion) }}"
                                            data-numero="{{ $hab->Numero }}"
                                            title="Desactivar habitación"
                                            style="color: #dc3545;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif

                                @if(in_array(auth()->user()->IdRol, [1, 4]) && !$hab->activo)
                                    <button type="button" 
                                            class="btn btn-light btn-sm px-3 btn-reactivar"
                                            data-url="{{ route('habitaciones.reactivar', $hab->IdHabitacion) }}"
                                            data-numero="{{ $hab->Numero }}"
                                            title="Reactivar habitación"
                                            style="color: #28a745;">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->IdRol == 4 ? '7' : '5' }}" class="text-center text-muted py-5">
                            <i class="fas fa-bed fa-3x mb-3 d-block"></i>
                            No se encontraron habitaciones
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación Personalizada --}}
    @if($habitaciones->hasPages())
    <div class="d-flex justify-content-center align-items-center mt-5">
        <nav aria-label="Navegación de páginas">
            <ul class="pagination-luxury">
                {{-- Botón Anterior --}}
                @if($habitaciones->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $habitaciones->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif
                
                {{-- Números de página --}}
                @foreach($habitaciones->getUrlRange(1, $habitaciones->lastPage()) as $page => $url)
                    @if($page == $habitaciones->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
                
                {{-- Botón Siguiente --}}
                @if($habitaciones->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $habitaciones->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    {{-- Contador de resultados --}}
    <div class="text-center mt-2">
        <small class="text-muted">
            Mostrando {{ $habitaciones->firstItem() ?? 0 }} - {{ $habitaciones->lastItem() ?? 0 }} 
            de {{ $habitaciones->total() }} resultados
        </small>
    </div>
@endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // ELIMINAR (DESACTIVAR)
    // ============================================
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let numero = this.dataset.numero;
            
            SwalConfirmacion(
                '¿Desactivar habitación ' + numero + '?',
                'La habitación quedará como INACTIVA.',
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
                        SwalError('Error', 'Ocurrió un error');
                    });
                }
            });
        });
    });

    // ============================================
    // REACTIVAR
    // ============================================
    document.querySelectorAll('.btn-reactivar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let numero = this.dataset.numero;
            
            SwalConfirmacionVerde(
                '¿Reactivar habitación ' + numero + '?',
                'La habitación volverá a estar activa.',
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
                        SwalError('Error', 'Ocurrió un error');
                    });
                }
            });
        });
    });

    // ============================================
    // LIBERAR (Cambiar a Disponible)
    // ============================================
    document.querySelectorAll('.btn-liberar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let numero = this.dataset.numero;
            
            SwalConfirmacion(
                '¿Habilitar habitación ' + numero + '?',
                'La habitación pasará a estado DISPONIBLE.',
                'warning',
                'Sí, habilitar'
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
                        SwalError('Error', 'Ocurrió un error');
                    });
                }
            });
        });
    });

    // Auto-submit al cambiar filtro
    document.querySelectorAll('#formFiltros select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('formFiltros').submit();
        });
    });

});
</script>

<style>
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .table tbody tr.bg-light {
        background-color: #f5f5f5 !important;
    }
    
    /* Hover efectos para botones de acción */
    .btn-light:hover {
        background-color: #e9ecef;
    }
    
    a[style*="color: #d4af37"]:hover {
        color: #b38f2a !important;
    }
    
    button[style*="color: #28a745"]:hover {
        color: #1e6b22 !important;
    }
    
    button[style*="color: #dc3545"]:hover {
        color: #a71d2a !important;
    }
    /* Paginación Luxury */
.pagination-luxury {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
}

.pagination-luxury .page-item {
    margin: 0 2px;
}

.pagination-luxury .page-link {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 2px solid #e9ecef;
    background: white;
    color: #1a1a1a;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}

.pagination-luxury .page-link:hover {
    background: #f8f9fa;
    border-color: #c9a45c;
    color: #c9a45c;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(201, 164, 92, 0.15);
}

.pagination-luxury .page-item.active .page-link {
    background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
    border-color: #1a1a1a;
    color: #c9a45c;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.pagination-luxury .page-item.disabled .page-link {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #ccc;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-luxury .page-item.disabled .page-link:hover {
    transform: none;
    box-shadow: none;
}

/* Animación de carga */
.pagination-luxury .page-link {
    position: relative;
    overflow: hidden;
}

.pagination-luxury .page-link::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(201, 164, 92, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
}

.pagination-luxury .page-link:hover::after {
    width: 100%;
    height: 100%;
}
</style>

@endsection