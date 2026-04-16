@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;"> Habitaciones</h1>
            <p class="text-muted mb-0">Gestión de inventario y configuración de estados</p>
        </div>
        <a href="{{ route('habitaciones.create') }}" 
           class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-plus mr-2 text-warning"></i> Nueva Habitación
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">BUSCAR NÚMERO</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           class="form-control rounded-pill border-0 bg-light px-4" placeholder="Ej: 101...">
                </div>
                
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                    <select name="estado" class="form-control rounded-pill border-0 bg-light px-4 custom-select-pill">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('estado')=='1'?'selected':'' }}>Disponible</option>
                        <option value="2" {{ request('estado')=='2'?'selected':'' }}>Ocupada</option>
                        <option value="3" {{ request('estado')=='3'?'selected':'' }}>Mantenimiento</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                    <select name="hotel" class="form-control rounded-pill border-0 bg-light px-4 custom-select-pill">
                        <option value="">Todos los hoteles</option>
                        @foreach($hoteles as $hotel)
                            <option value="{{ $hotel->IdHotel }}" {{ request('hotel')==$hotel->IdHotel?'selected':'' }}>
                                {{ $hotel->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 text-right">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-filter mr-2"></i> FILTRAR
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="px-4 py-3 border-0">NÚMERO</th>
                        <th class="py-3 border-0">TIPO</th>
                        <th class="py-3 border-0 text-center">PISO</th>
                        <th class="py-3 border-0">HOTEL</th>
                        <th class="py-3 border-0 text-center">ESTADO</th>
                        <th class="px-4 py-3 border-0 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($habitaciones as $hab)
                    <tr>
                        <td class="px-4 py-4">
                            <span class="h5 font-weight-bold text-dark mb-0">{{ $hab->Numero }}</span>
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
                        <td class="py-4 text-muted font-weight-medium">
                            {{ $hab->hotel->Nombre ?? 'N/A' }}
                        </td>
                        <td class="py-4 text-center">
                            @php
                                $estados = [
                                    1 => ['Disponible', 'bg-success-gradient'], 
                                    2 => ['Ocupada', 'bg-danger-gradient'], 
                                    3 => ['Mantenimiento', 'bg-secondary-gradient']
                                ];
                                $est = $estados[$hab->IdEstadoHabitacion] ?? ['Desconocido', 'bg-dark'];
                            @endphp
                            <span class="badge {{ $est[1] }} text-white px-3 py-2 shadow-sm" style="border-radius: 10px; font-size: 0.75rem;">
                                {{ $est[0] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <a href="{{ route('habitaciones.show', $hab) }}" class="btn btn-light btn-sm px-3" title="Ver">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                                <a href="{{ route('habitaciones.edit', $hab) }}" class="btn btn-light btn-sm px-3" title="Editar">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                <form method="POST" action="{{ route('habitaciones.destroy', $hab) }}" 
                                      class="d-inline" onsubmit="return confirm('¿Eliminar habitación {{$hab->Numero}}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm px-3" title="Eliminar">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center text-muted">
                            <i class="fas fa-bed fa-3x mb-3 opacity-20"></i>
                            <p>No se encontraron habitaciones registradas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $habitaciones->withQueryString()->links() }}
    </div>
</div>

<style>
    /* Degradados de la paleta original */
    .bg-success-gradient { background: linear-gradient(135deg, #1d976c 0%, #93f9b9 100%); }
    .bg-danger-gradient { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .bg-secondary-gradient { background: linear-gradient(135deg, #485563 0%, #29323c 100%); }

    /* Select con estilo de píldora */
    .custom-select-pill {
        -webkit-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }

    /* Estilo de la tabla */
    .table thead th {
        letter-spacing: 1px;
        font-size: 0.75rem;
        font-weight: 800;
    }
    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: scale(1.002);
    }
</style>
@endsection