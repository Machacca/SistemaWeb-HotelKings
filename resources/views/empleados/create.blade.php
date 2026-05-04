@extends('layouts.app')

@section('title', 'Nuevo Empleado')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Nuevo Empleado</h1>
            <p class="text-muted mb-0">Registre un nuevo empleado en el sistema</p>
        </div>
        
        <a href="{{ route('empleados.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3" style="border-radius: 25px 25px 0 0;">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-user-plus mr-2 text-warning"></i> Registrar Empleado
                </h5>
                <small class="text-muted">Complete los datos del nuevo empleado</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('empleados.store') }}" id="formEmpleado">
                    @csrf

                    <div class="row">
                        {{-- Nombres --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRES</label>
                            <input type="text" name="nombres" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('nombres') }}" required>
                        </div>

                        {{-- Apellidos --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">APELLIDOS</label>
                            <input type="text" name="apellidos" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('apellidos') }}" required>
                        </div>

                        {{-- Puesto --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">PUESTO</label>
                            <select name="puesto" class="form-control rounded-pill bg-light border-0 px-4" required>
                                <option value="">Seleccionar puesto...</option>
                                @foreach($puestos as $puesto)
                                    <option value="{{ $puesto }}" {{ old('puesto') == $puesto ? 'selected' : '' }}>
                                        {{ $puesto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fecha de Ingreso --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">FECHA DE INGRESO</label>
                            <input type="date" name="fecha_ingreso" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('fecha_ingreso') }}" required>
                        </div>

                        {{-- Tipo de Documento --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TIPO DE DOCUMENTO</label>
                            <select name="tipo_documento" class="form-control rounded-pill bg-light border-0 px-4">
                                <option value="">Seleccionar...</option>
                                @foreach($tiposDocumento as $key => $tipo)
                                    <option value="{{ $key }}" {{ old('tipo_documento') == $key ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Número de Documento --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NÚMERO DE DOCUMENTO</label>
                            <input type="text" name="numero_documento" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('numero_documento') }}" placeholder="Opcional">
                        </div>

                        {{-- Teléfono --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TELÉFONO</label>
                            <input type="text" name="telefono" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('telefono') }}" placeholder="Opcional">
                        </div>

                        {{-- Email Personal --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">EMAIL PERSONAL</label>
                            <input type="email" name="email_personal" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('email_personal') }}" placeholder="Opcional">
                        </div>

                        {{-- Usuario Asociado (solo si tiene acceso al sistema) --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">USUARIO ASOCIADO</label>
                            <select name="IdUsuario" class="form-control rounded-pill bg-light border-0 px-4">
                                <option value="">Ninguno (sin acceso al sistema)</option>
                                @foreach($usuariosDisponibles as $usuario)
                                    <option value="{{ $usuario->IdUsuario }}" {{ old('IdUsuario') == $usuario->IdUsuario ? 'selected' : '' }}>
                                        {{ $usuario->Username }} ({{ $usuario->rol->NombreRol ?? 'Sin rol' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted ml-2">Seleccione un usuario si el empleado tiene acceso al sistema</small>
                        </div>

                        {{-- Hotel (solo para Master) --}}
                        @if(auth()->user()->IdRol == 4)
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                                <select name="IdHotel" class="form-control rounded-pill bg-light border-0 px-4" required>
                                    <option value="">Seleccionar Hotel...</option>
                                    @foreach($hoteles as $hotel)
                                        <option value="{{ $hotel->IdHotel }}" {{ old('IdHotel') == $hotel->IdHotel ? 'selected' : '' }}>
                                            {{ $hotel->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="IdHotel" value="{{ auth()->user()->IdHotel }}">
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('empleados.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2 text-warning"></i> Guardar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formEmpleado').addEventListener('submit', function(e) {
        // Validaciones adicionales si son necesarias
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