@extends('layouts.app')

@section('title', 'Editar Tipo de Habitación')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Editar Tipo de Habitación</h1>
            <p class="text-muted mb-0">Actualice los datos de la categoría</p>
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
                    <i class="fas fa-edit mr-2 text-warning"></i> Editar Tipo de Habitación
                </h5>
                <small class="text-muted">Modifique los datos de la categoría</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tiposhabitacion.update', $tipo->IdTipo) }}" id="formTipoEdit">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted ml-2">NOMBRE DEL TIPO</label>
                        <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                               value="{{ old('Nombre', $tipo->Nombre) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="small font-weight-bold text-muted ml-2">TARIFA BASE (S/)</label>
                            <input type="number" step="0.01" name="Tarifa_base" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Tarifa_base', $tipo->Tarifa_base) }}" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="small font-weight-bold text-muted ml-2">CAPACIDAD (PERSONAS)</label>
                            <input type="number" name="Capacidad" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Capacidad', $tipo->Capacidad) }}" min="1" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN (OPCIONAL)</label>
                        <textarea name="Descripcion" class="form-control rounded-15 bg-light border-0 px-4 py-3" 
                                  rows="3">{{ old('Descripcion', $tipo->Descripcion) }}</textarea>
                    </div>

                    @if(!$tipo->activo)
                        <div class="alert alert-warning text-center mt-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Este tipo de habitación está INACTIVO
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('tiposhabitacion.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2"></i> Actualizar
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