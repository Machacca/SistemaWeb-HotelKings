@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Empleados</h1>
            <p class="text-muted mb-0">Gestión de personal del hotel</p>
        </div>
        
        @if(in_array(auth()->user()->IdRol, [1, 4]))
            <a href="{{ route('empleados.create') }}" 
               class="btn btn-dark rounded-pill px-5 py-3 shadow-sm font-weight-bold" 
               style="font-size: 1rem;">
                <i class="fas fa-user-plus mr-2 text-warning"></i> Nuevo Empleado
            </a>
        @endif
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" id="formFiltros" class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">BUSCAR</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           class="form-control rounded-pill border-0 bg-light px-4" 
                           placeholder="Nombre, apellido o documento...">
                </div>
                
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">PUESTO</label>
                    <select name="puesto" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="">Todos los puestos</option>
                        @foreach($puestos as $puesto)
                            <option value="{{ $puesto }}" {{ request('puesto') == $puesto ? 'selected' : '' }}>
                                {{ $puesto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                    <select name="activo" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="1" {{ request('activo', '1') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                        <option value="" {{ request('activo') == '' && request('activo') !== '1' ? 'selected' : '' }}>Todos</option>
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

    {{-- TABLA DE EMPLEADOS --}}
    <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="px-4 py-3 border-0">EMPLEADO</th>
                        <th class="py-3 border-0">PUESTO</th>
                        <th class="py-3 border-0">DOCUMENTO</th>
                        <th class="py-3 border-0">CONTACTO</th>
                        @if(auth()->user()->IdRol == 4)
                            <th class="py-3 border-0">HOTEL</th>
                        @endif
                        <th class="py-3 border-0 text-center">FECHA INGRESO</th>
                        <th class="py-3 border-0 text-center">ESTADO</th>
                        <th class="px-4 py-3 border-0 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($empleados as $empleado)
                    <tr class="{{ !$empleado->activo ? 'bg-light text-muted' : '' }}">
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle mr-3 bg-light text-dark font-weight-bold border-warning" style="border-width: 2px; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($empleado->nombres, 0, 1)) }}{{ strtoupper(substr($empleado->apellidos, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold {{ $empleado->activo ? 'text-dark' : 'text-muted' }}">
                                        {{ $empleado->nombres }} {{ $empleado->apellidos }}
                                    </div>
                                    @if($empleado->usuario)
                                        <span class="badge badge-info mt-1" style="background-color: #17a2b8; font-size: 0.7rem;">
                                            <i class="fas fa-key mr-1"></i> Tiene usuario
                                        </span>
                                    @endif
                                    @if(!$empleado->activo)
                                        <span class="text-muted small">(INACTIVO)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="badge badge-light px-3 py-2">
                                <i class="fas fa-briefcase mr-1 text-warning"></i>
                                {{ $empleado->puesto }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($empleado->tipo_documento && $empleado->numero_documento)
                                {{ $empleado->tipo_documento }}: {{ $empleado->numero_documento }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($empleado->telefono)
                                <div><i class="fas fa-phone text-muted mr-1 small"></i> {{ $empleado->telefono }}</div>
                            @endif
                            @if($empleado->email_personal)
                                <div class="small text-muted"><i class="fas fa-envelope text-muted mr-1"></i> {{ $empleado->email_personal }}</div>
                            @endif
                        </td>
                        @if(auth()->user()->IdRol == 4)
                            <td class="py-3">
                                {{ $empleado->hotel->Nombre ?? 'N/A' }}
                            </td>
                        @endif
                        <td class="py-3 text-center">
                            {{ $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="py-3 text-center">
                            @if($empleado->activo)
                                <span class="badge badge-success px-3 py-2" style="background-color: #2e7d32;">ACTIVO</span>
                            @else
                                <span class="badge badge-secondary px-3 py-2">INACTIVO</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <a href="{{ route('empleados.show', $empleado->IdEmpleado) }}" 
                                   class="btn btn-light btn-sm px-3" 
                                   title="Ver detalles"
                                   style="color: #d4af37;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(in_array(auth()->user()->IdRol, [1, 4]))
                                    <a href="{{ route('empleados.edit', $empleado->IdEmpleado) }}" 
                                       class="btn btn-light btn-sm px-3" 
                                       title="Editar"
                                       style="color: #26a53b;">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($empleado->activo)
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-eliminar"
                                                data-url="{{ route('empleados.destroy', $empleado->IdEmpleado) }}"
                                                data-nombre="{{ $empleado->nombres }} {{ $empleado->apellidos }}"
                                                title="Desactivar"
                                                style="color: #dc3545;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-light btn-sm px-3 btn-reactivar"
                                                data-url="{{ route('empleados.reactivar', $empleado->IdEmpleado) }}"
                                                data-nombre="{{ $empleado->nombres }} {{ $empleado->apellidos }}"
                                                title="Reactivar"
                                                style="color: #28a745;">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </tr>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->IdRol == 4 ? '9' : '8' }}" class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                            No se encontraron empleados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $empleados->withQueryString()->links() }}
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
                '¿Desactivar empleado "' + nombre + '"?',
                'El empleado quedará como INACTIVO en el sistema.',
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
                        SwalError('Error', 'Ocurrió un error al desactivar el empleado');
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
                '¿Reactivar empleado "' + nombre + '"?',
                'El empleado volverá a estar activo en el sistema.',
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
                        SwalError('Error', 'Ocurrió un error al reactivar el empleado');
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
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
</style>

@endsection