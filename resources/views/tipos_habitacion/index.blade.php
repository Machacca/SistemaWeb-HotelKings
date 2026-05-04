@extends('layouts.app')

@section('title', 'Tipos de Habitación')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Tipos de Habitación</h1>
            <p class="text-muted mb-0">Gestión de categorías y tarifas base</p>
        </div>
        
        @if(auth()->user()->IdRol == 4)
            <a href="{{ route('tiposhabitacion.create') }}" 
               class="btn btn-dark rounded-pill px-5 py-3 shadow-sm font-weight-bold" 
               style="font-size: 1rem;">
                <i class="fas fa-plus mr-2 text-warning"></i> Nuevo Tipo
            </a>
        @endif
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" id="formFiltros" class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">BUSCAR</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           class="form-control rounded-pill border-0 bg-light px-4" placeholder="Nombre del tipo...">
                </div>
                
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                    <select name="activo" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="1" {{ request('activo', '1') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                        <option value="" {{ request('activo') == '' && request('activo') !== '1' ? 'selected' : '' }}>Todos</option>
                    </select>
                </div>

                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="px-4 py-3 border-0">NOMBRE</th>
                        <th class="py-3 border-0 text-center">TARIFA BASE</th>
                        <th class="py-3 border-0 text-center">CAPACIDAD</th>
                        <th class="py-3 border-0">DESCRIPCIÓN</th>
                        <th class="py-3 border-0 text-center">ESTADO</th>
                        <th class="px-4 py-3 border-0 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($tipos as $tipo)
                    <tr class="{{ !$tipo->activo ? 'bg-light text-muted' : '' }}">
                        <td class="px-4 py-3">
                            <span class="font-weight-bold {{ $tipo->activo ? 'text-dark' : 'text-muted' }}">
                                {{ $tipo->Nombre }}
                            </span>
                            @if(!$tipo->activo)
                                <span class="badge badge-secondary ml-2">INACTIVO</span>
                            @endif
                         </td>
                        <td class="py-3 text-center">
                            <span class="font-weight-bold text-success">S/ {{ number_format($tipo->Tarifa_base, 2) }}</span>
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge badge-light px-3 py-2">
                                <i class="fas fa-users mr-1"></i> {{ $tipo->Capacidad }} persona(s)
                            </span>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $tipo->Descripcion ?? 'Sin descripción' }}</small>
                        </td>
                        <td class="py-3 text-center">
                            @if($tipo->activo)
                                <span class="badge badge-success px-3 py-2" style="background-color: #2e7d32;">ACTIVO</span>
                            @else
                                <span class="badge badge-secondary px-3 py-2">INACTIVO</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <a href="{{ route('tiposhabitacion.show', $tipo->IdTipo) }}" 
                                   class="btn btn-light btn-sm px-3" 
                                   title="Ver detalles"
                                   style="color: #d4af37;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(auth()->user()->IdRol == 4)
                                    <a href="{{ route('tiposhabitacion.edit', $tipo->IdTipo) }}" 
                                       class="btn btn-light btn-sm px-3" 
                                       title="Editar"
                                       style="color: #26a53b;">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($tipo->activo)
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-eliminar"
                                                data-url="{{ route('tiposhabitacion.destroy', $tipo->IdTipo) }}"
                                                data-nombre="{{ $tipo->Nombre }}"
                                                title="Desactivar"
                                                style="color: #dc3545;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-reactivar"
                                                data-url="{{ route('tiposhabitacion.reactivar', $tipo->IdTipo) }}"
                                                data-nombre="{{ $tipo->Nombre }}"
                                                title="Reactivar"
                                                style="color: #28a745;">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-bed fa-3x mb-3 d-block"></i>
                            No se encontraron tipos de habitación
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $tipos->withQueryString()->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // ELIMINAR (DESACTIVAR)
    // ============================================
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let nombre = this.dataset.nombre;
            
            SwalConfirmacion(
                '¿Desactivar tipo "' + nombre + '"?',
                'Los tipos desactivados no podrán seleccionarse en nuevas habitaciones.',
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
                        SwalError('Error', 'Ocurrió un error al desactivar el tipo');
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
            let nombre = this.dataset.nombre;
            
            SwalConfirmacionVerde(
                '¿Reactivar tipo "' + nombre + '"?',
                'El tipo estará nuevamente disponible para seleccionar en habitaciones.',
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
                        SwalError('Error', 'Ocurrió un error al reactivar el tipo');
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
</style>

@endsection