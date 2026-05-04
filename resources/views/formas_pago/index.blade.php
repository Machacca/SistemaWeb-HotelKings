@extends('layouts.app')

@section('title', 'Formas de Pago')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Formas de Pago</h1>
            <p class="text-muted mb-0">Gestión de métodos de pago</p>
        </div>
        
        @if(in_array(auth()->user()->IdRol, [1, 4]))
            <a href="{{ route('formas-pago.create') }}" 
               class="btn btn-dark rounded-pill px-5 py-3 shadow-sm font-weight-bold" 
               style="font-size: 1rem;">
                <i class="fas fa-plus mr-2 text-warning"></i> Nueva Forma de Pago
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
                           placeholder="Nombre de la forma de pago...">
                </div>
                
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                    <select name="activo" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="1" {{ request('activo', '1') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                        <option value="" {{ request('activo') == '' && request('activo') !== '1' ? 'selected' : '' }}>Todos</option>
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

    {{-- TABLA DE FORMAS DE PAGO --}}
    <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="px-4 py-3 border-0">NOMBRE</th>
                        <th class="py-3 border-0">DESCRIPCIÓN</th>
                        <th class="py-3 border-0 text-center">ESTADO</th>
                        <th class="px-4 py-3 border-0 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($formasPago as $forma)
                    <tr class="{{ !$forma->activo ? 'bg-light text-muted' : '' }}">
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle mr-3 bg-light text-dark font-weight-bold border-warning" style="border-width: 2px; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($forma->Nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold {{ $forma->activo ? 'text-dark' : 'text-muted' }}">
                                        {{ $forma->Nombre }}
                                    </div>
                                    @if(!$forma->activo)
                                        <span class="text-muted small">(INACTIVO)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            {{ $forma->descripcion ?? '—' }}
                        </td>
                        <td class="py-3 text-center">
                            @if($forma->activo)
                                <span class="badge badge-success px-3 py-2" style="background-color: #2e7d32;">ACTIVO</span>
                            @else
                                <span class="badge badge-secondary px-3 py-2">INACTIVO</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <a href="{{ route('formas-pago.show', $forma->IdFormaPago) }}" 
                                   class="btn btn-light btn-sm px-3" 
                                   title="Ver detalles"
                                   style="color: #d4af37;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(in_array(auth()->user()->IdRol, [1, 4]))
                                    <a href="{{ route('formas-pago.edit', $forma->IdFormaPago) }}" 
                                       class="btn btn-light btn-sm px-3" 
                                       title="Editar"
                                       style="color: #26a53b;">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($forma->activo)
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-eliminar"
                                                data-url="{{ route('formas-pago.destroy', $forma->IdFormaPago) }}"
                                                data-nombre="{{ $forma->Nombre }}"
                                                title="Desactivar"
                                                style="color: #dc3545;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-reactivar"
                                                data-url="{{ route('formas-pago.reactivar', $forma->IdFormaPago) }}"
                                                data-nombre="{{ $forma->Nombre }}"
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
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="fas fa-credit-card fa-3x mb-3 d-block"></i>
                            No se encontraron formas de pago
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $formasPago->withQueryString()->links() }}
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
                '¿Desactivar forma de pago "' + nombre + '"?',
                'Las formas de pago desactivadas no podrán seleccionarse en comprobantes.',
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
                        SwalError('Error', 'Ocurrió un error al desactivar la forma de pago');
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
                '¿Reactivar forma de pago "' + nombre + '"?',
                'La forma de pago estará nuevamente disponible para seleccionar.',
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
                        SwalError('Error', 'Ocurrió un error al reactivar la forma de pago');
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
    
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        background-color: #f8f9fa;
        border: 2px solid #ffc107 !important;
    }
    
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