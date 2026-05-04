@extends('layouts.app')

@section('title', 'Nuevo Canal de Reserva')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Nuevo Canal de Reserva</h1>
            <p class="text-muted mb-0">Registre un nuevo origen de reservas</p>
        </div>
        
        <a href="{{ route('canales-reserva.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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
                    <i class="fas fa-channels mr-2 text-warning"></i> Registrar Canal
                </h5>
                <small class="text-muted">Complete los datos del nuevo canal</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('canales-reserva.store') }}" id="formCanal">
                    @csrf

                    <div class="row">
                        {{-- Nombre del Canal --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRE DEL CANAL</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Nombre') }}" placeholder="Ej: Recepción, Teléfono, Web, Booking.com" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN (OPCIONAL)</label>
                            <textarea name="descripcion" class="form-control rounded-15 bg-light border-0 px-4 py-3" 
                                      rows="3" placeholder="Descripción del canal...">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('canales-reserva.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2 text-warning"></i> Guardar Canal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formCanal').addEventListener('submit', function(e) {
        // Validaciones adicionales si son necesarias
    });
</script>

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