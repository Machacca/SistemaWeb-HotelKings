@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="font-family: 'Playfair Display', serif;" class="font-weight-bold text-dark">Directorio de Huéspedes</h2>
            <p class="text-muted">Administra la base de datos de clientes y su historial de estancia.</p>
        </div>
        <a href="{{ route('huespedes.create') }}" class="btn btn-dark rounded-pill px-4 shadow">
            <i class="fas fa-user-plus mr-2 text-warning"></i> NUEVO HUÉSPED
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="input-group bg-light rounded-pill px-3">
                        <div class="input-group-prepend border-0">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="buscarHuesped" class="form-control bg-transparent border-0 shadow-none text-dark font-weight-bold" placeholder="Buscar por Nombre, DNI o Pasaporte...">
                    </div>
                </div>
                <div class="col-md-4 text-right text-muted small font-weight-bold text-uppercase">
                    <i class="fas fa-filter mr-2"></i> {{ $huespedes->count() }} Registros encontrados
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="border-0 px-4">Huésped</th>
                        <th class="border-0">Documento</th>
                        <th class="border-0">Contacto</th>
                        <th class="border-0">Nacionalidad</th>
                        <th class="border-0">Estado</th>
                        <th class="border-0 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($huespedes as $h)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle mr-3 bg-light text-dark font-weight-bold border-warning" style="border-width: 2px;">
                                    {{ substr($h->Nombre, 0, 1) }}{{ substr($h->Apellido, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size: 1.05rem;">{{ $h->Nombre }} {{ $h->Apellido }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light px-3 py-2 rounded-pill font-weight-bold text-dark border">
                                <i class="far fa-file-alt mr-1 text-warning"></i> {{ $h->TipoDocumento }}: {{ $h->NroDocumento }}
                            </span>
                        </td>
                        <td>
                            <div class="small font-weight-bold text-dark"><i class="fas fa-phone mr-1 text-muted"></i> {{ $h->Telefono ?? '---' }}</div>
                            <div class="small text-muted"><i class="fas fa-envelope mr-1 text-muted"></i> {{ $h->Email ?? '---' }}</div>
                        </td>
                        <td>
                            <span class="font-weight-bold text-dark">{{ $h->Nacionalidad ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-success-soft">Activo</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                
                                {{-- Botón Editar (el que ya tienes) --}}
                                <a href="{{ route('huespedes.edit', $h->IdHuesped) }}" 
                                class="btn btn-sm btn-light rounded-circle mr-2 shadow-sm" title="Editar">
                                    <i class="fas fa-edit text-dark"></i>
                                </a>

                                {{-- FORMULARIO DE ELIMINACIÓN --}}
                                <form action="{{ route('huespedes.destroy', $h->IdHuesped) }}" method="POST" 
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar a {{ $h->Nombre }}? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm" title="Eliminar">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados para mantener la paleta de colores */
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .badge-success-soft {
        background-color: #e8f5e9;
        color: #2e7d32;
        font-weight: 700;
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: 1px solid #eee;
    }

    .action-btn:hover {
        background-color: #f0f0f0;
        transform: translateY(-2px);
    }

    .table td { 
        vertical-align: middle; 
        border-top: 1px solid #f2f2f2;
    }

    .text-dark { color: #000000 !important; }
</style>
@endsection