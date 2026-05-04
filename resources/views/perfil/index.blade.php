@extends('layouts.app')

@section('title', 'Mi Cuenta')

@section('content')
<div class="container py-4">
    
    {{-- Encabezado destacado --}}
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="d-inline-block p-3 rounded-circle mb-3" style="background: linear-gradient(135deg, #d4af37 0%, #8a6d1a 100%);">
                <i class="fas fa-user-circle fa-4x text-white"></i>
            </div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a;">Mi Cuenta</h1>
            <p class="text-muted">Gestiona tu información personal y seguridad</p>
            <div class="w-25 mx-auto" style="height: 3px; background: linear-gradient(90deg, transparent, #d4af37, transparent);"></div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Mensajes de éxito/error --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(135deg, #d4edda, #c3e6cb);">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

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

            {{-- Información del usuario --}}
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-id-card fa-lg mr-3" style="color: #d4af37;"></i>
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">Información Personal</h4>
                </div>
                <div class="p-4 bg-white rounded-3 shadow-sm" style="border-radius: 20px;">
                    <form method="POST" action="{{ route('perfil.update') }}" id="formPerfil">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">NOMBRE DE USUARIO</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-dark border-0" style="border-radius: 15px 0 0 15px; color: #d4af37;"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="Username" class="form-control bg-light border-0 px-3" 
                                           value="{{ old('Username', $usuario->Username) }}" required style="border-radius: 0 15px 15px 0;">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">CORREO ELECTRÓNICO</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-dark border-0" style="border-radius: 15px 0 0 15px; color: #d4af37;"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="Email" class="form-control bg-light border-0 px-3" 
                                           value="{{ old('Email', $usuario->Email) }}" required style="border-radius: 0 15px 15px 0;">
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            {{-- Información del Hotel y Rol --}}
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-building fa-lg mr-3" style="color: #d4af37;"></i>
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">Información Institucional</h4>
                </div>
                <div class="p-4 bg-white rounded-3 shadow-sm" style="border-radius: 20px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">ROL</label>
                            <div class="p-3 bg-light rounded-3" style="border-radius: 15px;">
                                <i class="fas fa-tag mr-2" style="color: #d4af37;"></i>
                                <span class="font-weight-bold">{{ $usuario->rol->NombreRol ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">HOTEL / SEDE</label>
                            <div class="p-3 bg-light rounded-3" style="border-radius: 15px;">
                                @if($usuario->hotel)
                                    <i class="fas fa-hotel mr-2" style="color: #d4af37;"></i>
                                    <span class="font-weight-bold">{{ $usuario->hotel->Nombre }}</span>
                                @else
                                    <i class="fas fa-globe mr-2" style="color: #d4af37;"></i>
                                    <span class="font-weight-bold">Administración Global</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cambiar Contraseña --}}
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-shield-alt fa-lg mr-3" style="color: #d4af37;"></i>
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">Seguridad</h4>
                </div>
                <div class="p-4 bg-white rounded-3 shadow-sm" style="border-radius: 20px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NUEVA CONTRASEÑA</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark border-0" style="border-radius: 15px 0 0 15px; color: #d4af37;"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="Password" id="password" class="form-control bg-light border-0 px-3" 
                                       placeholder="Dejar en blanco para mantener" style="border-radius: 0 15px 15px 0;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary border-0 toggle-password" style="border-radius: 0 15px 15px 0; background-color: #f8f9fa;" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted ml-2">Mínimo 6 caracteres</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CONFIRMAR CONTRASEÑA</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark border-0" style="border-radius: 15px 0 0 15px; color: #d4af37;"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="Password_confirmation" id="password_confirmation" class="form-control bg-light border-0 px-3" 
                                       placeholder="Repite la nueva contraseña" style="border-radius: 0 15px 15px 0;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary border-0 toggle-password" style="border-radius: 0 15px 15px 0; background-color: #f8f9fa;" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Estadísticas de la cuenta --}}
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-chart-line fa-lg mr-3" style="color: #d4af37;"></i>
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">Estadísticas de la Cuenta</h4>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-4 bg-white rounded-3 shadow-sm text-center" style="border-radius: 20px;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #d4af37;"></i>
                            <div class="small text-muted">Miembro desde</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-4 bg-white rounded-3 shadow-sm text-center" style="border-radius: 20px;">
                            <i class="fas fa-clock fa-2x mb-2" style="color: #d4af37;"></i>
                            <div class="small text-muted">Última actualización</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
                </a>
                <button type="submit" class="btn btn-dark rounded-pill px-5 py-2 font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #1a1a1a, #0d0d0d);">
                    <i class="fas fa-save mr-2 text-warning"></i> Guardar Cambios
                </button>
            </div>

            </form>
        </div>
    </div>
</div>

<script>
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

    // Validación de contraseñas coincidentes
    document.getElementById('formPerfil').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        
        if (password !== confirm) {
            e.preventDefault();
            SwalError('Error', 'Las contraseñas no coinciden');
        }
    });
</script>

<style>
    .rounded-3 {
        border-radius: 20px !important;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-outline-secondary:hover {
        background-color: transparent;
        border-color: #d4af37;
        color: #d4af37;
    }
</style>

@endsection