@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Editar Usuario</h1>
            <p class="text-muted mb-0">Actualice los datos del usuario</p>
        </div>
        
        <a href="{{ route('usuarios.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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
                    <i class="fas fa-user-edit mr-2 text-warning"></i> Editar Usuario
                </h5>
                <small class="text-muted">Modifique los datos del usuario</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('usuarios.update', $usuario->IdUsuario) }}" id="formUsuarioEdit">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Nombre de Usuario --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRE DE USUARIO</label>
                            <input type="text" name="Username" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Username', $usuario->Username) }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CORREO ELECTRÓNICO</label>
                            <input type="email" name="Email" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Email', $usuario->Email) }}" required>
                        </div>

                        {{-- Contraseña (opcional) con ojo --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NUEVA CONTRASEÑA</label>
                            <div class="input-group">
                                <input type="password" name="Password" id="password" class="form-control rounded-pill-left bg-light border-0 px-4" 
                                       placeholder="Dejar en blanco para mantener la actual" style="border-radius: 50px 0 0 50px;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary border-0 toggle-password" style="border-radius: 0 50px 50px 0; background-color: #f8f9fa;" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted ml-2">Solo si desea cambiar la contraseña</small>
                        </div>

                        {{-- Confirmar Contraseña con ojo --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CONFIRMAR CONTRASEÑA</label>
                            <div class="input-group">
                                <input type="password" name="Password_confirmation" id="password_confirmation" class="form-control rounded-pill-left bg-light border-0 px-4" 
                                       placeholder="Repita la nueva contraseña" style="border-radius: 50px 0 0 50px;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary border-0 toggle-password" style="border-radius: 0 50px 50px 0; background-color: #f8f9fa;" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Rol con descripción --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">ROL</label>
                            <select name="IdRol" id="selectRol" class="form-control rounded-pill bg-light border-0 px-4" required>
                                <option value="">Seleccionar Rol...</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->IdRol }}" {{ old('IdRol', $usuario->IdRol) == $rol->IdRol ? 'selected' : '' }}>
                                        {{ $rol->NombreRol }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="rolDescripcion" class="small text-muted mt-2 ml-2" style="display: none;">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="rolDescripcionTexto"></span>
                            </div>
                        </div>

                        {{-- Hotel (solo para Master) --}}
                        @if(auth()->user()->IdRol == 4)
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                                <select name="IdHotel" class="form-control rounded-pill bg-light border-0 px-4" required>
                                    <option value="">Seleccionar Hotel...</option>
                                    @foreach($hoteles as $hotel)
                                        <option value="{{ $hotel->IdHotel }}" {{ old('IdHotel', $usuario->IdHotel) == $hotel->IdHotel ? 'selected' : '' }}>
                                            {{ $hotel->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{-- Admin: hotel fijo (solo el suyo) --}}
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                                <div class="p-3 bg-light d-flex align-items-center" style="border-radius: 15px;">
                                    <i class="fas fa-hotel mr-2 text-warning"></i>
                                    <span class="font-weight-bold">{{ $usuario->hotel->Nombre ?? 'Sede Asignada' }}</span>
                                    <input type="hidden" name="IdHotel" value="{{ $usuario->IdHotel }}">
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!$usuario->activo)
                        <div class="alert alert-warning text-center mt-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Este usuario está INACTIVO
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
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

<script>
    // Descripciones de roles
    const descripciones = {
        4: 'Acceso total al sistema. Puede ver y gestionar todos los hoteles, usuarios y configuraciones.',
        1: 'Gestiona su propio hotel: usuarios, habitaciones, reservas, productos y facturación. No puede ver otros hoteles.',
        2: 'Atención al cliente: puede crear reservas, hacer check-in/out, gestionar huéspedes y ver el calendario.',
        3: 'Facturación y caja: puede gestionar productos, consumos y comprobantes de pago.'
    };

    // Mostrar descripción según rol seleccionado
    const selectRol = document.getElementById('selectRol');
    const descripcionDiv = document.getElementById('rolDescripcion');
    const descripcionTexto = document.getElementById('rolDescripcionTexto');

    function actualizarDescripcion() {
        const rolId = selectRol.value;
        if (rolId && descripciones[rolId]) {
            descripcionTexto.textContent = descripciones[rolId];
            descripcionDiv.style.display = 'block';
        } else {
            descripcionDiv.style.display = 'none';
        }
    }

    selectRol.addEventListener('change', actualizarDescripcion);
    actualizarDescripcion();

    // Toggle mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Validación de contraseñas coincidentes (solo si se ingresó una nueva)
    document.getElementById('formUsuarioEdit').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        
        if (password !== confirm) {
            e.preventDefault();
            SwalError('Error', 'Las contraseñas no coinciden');
        }
    });
</script>

<style>
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .rounded-pill-left {
        border-radius: 50px 0 0 50px !important;
    }
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
    
    .toggle-password:hover {
        background-color: #e2e6ea !important;
    }
</style>

@endsection