@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="mb-4 px-3 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('huespedes.index') }}" class="text-muted text-decoration-none small">
                        <i class="fas fa-arrow-left"></i> VOLVER AL LISTADO
                    </a>
                    <h2 style="font-family: 'Playfair Display', serif;" class="font-weight-bold mt-2 text-dark">
                        Editar Perfil: <span class="text-warning">{{ $huesped->Nombre }}</span>
                    </h2>
                </div>
                <div class="text-right">
                    <span class="badge badge-dark p-3 rounded-pill shadow-sm">ID HUÉSPED: #{{ $huesped->IdHuesped }}</span>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger shadow-sm mb-4 mx-3" style="border-radius: 20px; border: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-3 fa-2x"></i>
                        <ul class="mb-0 font-weight-bold">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-lg" style="border-radius: 30px;">
                <div class="card-body p-5">
                    <form action="{{ route('huespedes.update', $huesped->IdHuesped) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-0">Información de Identidad</h5>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Nombres Completos</label>
                                <input type="text" name="Nombre" value="{{ old('Nombre', $huesped->Nombre) }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Apellidos Completos</label>
                                <input type="text" name="Apellido" value="{{ old('Apellido', $huesped->Apellido) }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" required>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Documento de Identidad</label>
                                <select name="TipoDocumento" class="form-control rounded-pill bg-light border-0 px-4 custom-select-pill text-dark font-weight-bold shadow-none">
                                    <option value="DNI" {{ (old('TipoDocumento', $huesped->TipoDocumento) == 'DNI') ? 'selected' : '' }}>DNI - Documento Nacional</option>
                                    <option value="PAS" {{ (old('TipoDocumento', $huesped->TipoDocumento) == 'PAS') ? 'selected' : '' }}>PAS - Pasaporte</option>
                                    <option value="CE" {{ (old('TipoDocumento', $huesped->TipoDocumento) == 'CE') ? 'selected' : '' }}>CE - Carnet de Extranjería</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Número de Identificación</label>
                                <input type="text" name="NroDocumento" value="{{ old('NroDocumento', $huesped->NroDocumento) }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" required>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4 border-top pt-4">
                            <div class="bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-0">Contacto y Procedencia</h5>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">País / Nacionalidad</label>
                                <select name="Nacionalidad" id="selectPais" onchange="actualizarPrefijo()"
                                        class="form-control rounded-pill bg-light border-0 px-4 custom-select-pill text-dark font-weight-bold shadow-none">
                                    <option value="Perú" data-prefijo="+51" {{ $huesped->Nacionalidad == 'Perú' ? 'selected' : '' }}>🇵🇪 Perú</option>
                                    <option value="Argentina" data-prefijo="+54" {{ $huesped->Nacionalidad == 'Argentina' ? 'selected' : '' }}>🇦🇷 Argentina</option>
                                    <option value="Bolivia" data-prefijo="+591" {{ $huesped->Nacionalidad == 'Bolivia' ? 'selected' : '' }}>🇧🇴 Bolivia</option>
                                    <option value="Chile" data-prefijo="+56" {{ $huesped->Nacionalidad == 'Chile' ? 'selected' : '' }}>🇨🇱 Chile</option>
                                    <option value="España" data-prefijo="+34" {{ $huesped->Nacionalidad == 'España' ? 'selected' : '' }}>🇪🇸 España</option>
                                    <option value="México" data-prefijo="+52" {{ $huesped->Nacionalidad == 'México' ? 'selected' : '' }}>🇲🇽 México</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Correo Electrónico</label>
                                <input type="email" name="Email" value="{{ old('Email', $huesped->Email) }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                       placeholder="ejemplo@correo.com">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Número de Celular</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-left-pill bg-dark text-warning border-0 font-weight-bold px-4" id="prefijoPais" style="height: 55px;">+51</span>
                                    </div>
                                    <input type="text" name="Telefono" id="txtTelefono" value="{{ old('Telefono', $huesped->Telefono) }}" 
                                           class="form-control rounded-right-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                           style="height: 55px;">
                                </div>
                            </div>
                        </div>

                        <div class="row pt-4 border-top">
                            <div class="col-md-12 d-flex justify-content-end">
                                <a href="{{ route('huespedes.index') }}" class="btn btn-light rounded-pill font-weight-bold py-3 px-5 text-muted mr-3 shadow-sm">
                                    CANCELAR
                                </a>
                                <button type="submit" class="btn btn-warning rounded-pill font-weight-bold shadow py-3 px-5 text-dark transition-hover">
                                    <i class="fas fa-save mr-2"></i> GUARDAR CAMBIOS
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sincronización del prefijo al cambiar país
    function actualizarPrefijo() {
        const select = document.getElementById('selectPais');
        const prefijo = select.options[select.selectedIndex].getAttribute('data-prefijo');
        document.getElementById('prefijoPais').innerText = prefijo;
    }

    // Al cargar la página, ejecutar el prefijo correcto
    window.onload = function() {
        actualizarPrefijo();
    };
</script>

<style>
    /* Estilos para texto negro y negrita */
    .text-dark { color: #000000 !important; }
    .form-control { color: #000000 !important; font-weight: 700 !important; }
    .form-control::placeholder { color: #adb5bd !important; font-weight: 400 !important; }
    
    .custom-select-pill {
        height: 55px !important;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1.5rem center;
        background-size: 1.2em;
    }

    .input-group-text {
        border-top-left-radius: 50px !important;
        border-bottom-left-radius: 50px !important;
    }

    .transition-hover:hover {
        transform: scale(1.02);
        transition: 0.3s ease;
    }
</style>
@endsection