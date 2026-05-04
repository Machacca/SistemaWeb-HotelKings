@extends('layouts.app')

@section('title', 'Nuevo Hotel')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Nuevo Hotel</h1>
            <p class="text-muted mb-0">Registre una nueva sede o sucursal</p>
        </div>
        
        <a href="{{ route('hoteles.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver a Hoteles
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
                    <i class="fas fa-hotel mr-2 text-warning"></i> Registrar Hotel
                </h5>
                <small class="text-muted">Complete los datos de la nueva sede</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('hoteles.store') }}" id="formHotel">
                    @csrf

                    <div class="row">
                        {{-- Nombre del Hotel --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRE DEL HOTEL</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Nombre') }}" placeholder="Ej: Hotel Inka Kings - Cusco" required>
                            <small class="text-muted ml-2">El código se generará automáticamente</small>
                        </div>

                        {{-- Dirección --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">DIRECCIÓN</label>
                            <input type="text" name="Direccion" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Direccion') }}" placeholder="Ej: Av. Sol 123, Cusco">
                        </div>

                        {{-- Teléfono --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TELÉFONO</label>
                            <input type="text" name="Telefono" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Telefono') }}" placeholder="Ej: (084) 123456">
                        </div>
                    </div>

                    {{-- Información del código --}}
                    <div class="alert alert-info mt-3" style="background-color: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 15px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle mr-3" style="color: #d4af37;"></i>
                            <div class="small">
                                <strong class="text-warning">Código automático</strong><br>
                                <span class="text-muted">El código se generará automáticamente a partir del nombre del hotel (primeras 3 letras). Se usará como prefijo en las habitaciones.</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('hoteles.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2 text-warning"></i> Guardar Hotel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Validación adicional si es necesaria
    document.getElementById('formHotel').addEventListener('submit', function(e) {
        // Aquí puedes agregar validaciones extra si lo deseas
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
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection