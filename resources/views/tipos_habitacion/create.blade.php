@extends('layouts.app')

@section('title', 'Nuevo Tipo de Habitación')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Nuevo Tipo de Habitación</h1>
            <p class="text-muted mb-0">Registre una nueva categoría con su tarifa base</p>
        </div>
        
        <a href="{{ route('tiposhabitacion.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Listado
        </a>
    </div>

    {{-- Manejo de Errores --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="d-flex">
                <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                <ul class="list-unstyled mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Tarjeta con Encabezado Negro --}}
    <div style="max-width: 700px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3" style="border-radius: 25px 25px 0 0;">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-bed mr-2 text-warning"></i> Registrar Tipo de Habitación
                </h5>
                <small class="text-muted">Complete los datos de la nueva categoría</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tiposhabitacion.store') }}" id="formTipo">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted ml-2">NOMBRE DEL TIPO</label>
                        <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                               value="{{ old('Nombre') }}" placeholder="Ej: Simple, Doble, Suite" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="small font-weight-bold text-muted ml-2">TARIFA BASE (S/)</label>
                            <input type="number" step="0.01" name="Tarifa_base" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Tarifa_base') }}" placeholder="0.00" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="small font-weight-bold text-muted ml-2">CAPACIDAD (PERSONAS)</label>
                            <input type="number" name="Capacidad" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Capacidad') }}" placeholder="2" min="1" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN (OPCIONAL)</label>
                        <textarea name="Descripcion" class="form-control rounded-15 bg-light border-0 px-4 py-3" 
                                  rows="3" placeholder="Descripción del tipo de habitación...">{{ old('Descripcion') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('tiposhabitacion.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2 text-warning"></i> Guardar Tipo
                        </button>
                    </div>
                </form>
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
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection