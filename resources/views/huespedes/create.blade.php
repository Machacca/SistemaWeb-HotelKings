@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="mb-4 px-3">
                <a href="{{ route('huespedes.index') }}" class="text-muted text-decoration-none small">
                    <i class="fas fa-arrow-left"></i> VOLVER AL DIRECTORIO
                </a>
                <h2 style="font-family: 'Playfair Display', serif;" class="font-weight-bold mt-2 text-dark">Registrar Huésped Nuevo</h2>
            </div>

            @if($errors->any())
                <div class="alert alert-danger shadow-sm mb-4 mx-3" style="border-radius: 20px; border: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle mr-3 fa-2x"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li class="font-weight-bold">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-lg" style="border-radius: 30px;">
                <div class="card-body p-5">
                    <form action="{{ route('huespedes.store') }}" method="POST">
                        @csrf

                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-0">Identificación Básica</h5>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Nombres</label>
                                <input type="text" name="Nombre" value="{{ old('Nombre') }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                       placeholder="Ej. Juan Manuel" required onkeypress="return soloLetras(event)">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Apellidos</label>
                                <input type="text" name="Apellido" value="{{ old('Apellido') }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                       placeholder="Ej. Pérez García" required onkeypress="return soloLetras(event)">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Tipo de Documento</label>
                                <select name="TipoDocumento" class="form-control rounded-pill bg-light border-0 px-4 custom-select-pill text-dark font-weight-bold shadow-none">
                                    <option value="DNI" {{ old('TipoDocumento') == 'DNI' ? 'selected' : '' }}>Documento Nacional de Identidad (DNI)</option>
                                    <option value="PAS" {{ old('TipoDocumento') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                                    <option value="CE" {{ old('TipoDocumento') == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Número de Documento</label>
                                <input type="text" name="NroDocumento" value="{{ old('NroDocumento') }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                       placeholder="00000000" required onkeypress="return soloNumeros(event)">
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4 border-top pt-4">
                            <div class="bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-globe-americas"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-0">Localización y Contacto</h5>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">País / Nacionalidad</label>
                                <select name="Nacionalidad" id="selectPais" onchange="actualizarPrefijo()"
                                        class="form-control rounded-pill bg-light border-0 px-4 custom-select-pill text-dark font-weight-bold shadow-none">
                                    <option value="Perú" data-prefijo="+51" {{ old('Nacionalidad') == 'Perú' ? 'selected' : '' }}>🇵🇪 Perú</option>
                                    <option value="Argentina" data-prefijo="+54" {{ old('Nacionalidad') == 'Argentina' ? 'selected' : '' }}>🇦🇷 Argentina</option>
                                    <option value="Bolivia" data-prefijo="+591" {{ old('Nacionalidad') == 'Bolivia' ? 'selected' : '' }}>🇧🇴 Bolivia</option>
                                    <option value="Chile" data-prefijo="+56" {{ old('Nacionalidad') == 'Chile' ? 'selected' : '' }}>🇨🇱 Chile</option>
                                    <option value="Colombia" data-prefijo="+57" {{ old('Nacionalidad') == 'Colombia' ? 'selected' : '' }}>🇨🇴 Colombia</option>
                                    <option value="España" data-prefijo="+34" {{ old('Nacionalidad') == 'España' ? 'selected' : '' }}>🇪🇸 España</option>
                                    <option value="México" data-prefijo="+52" {{ old('Nacionalidad') == 'México' ? 'selected' : '' }}>🇲🇽 México</option>
                                    <option value="Otros" data-prefijo="+" {{ old('Nacionalidad') == 'Otros' ? 'selected' : '' }}>🌐 Otros</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Correo Electrónico</label>
                                <input type="email" name="Email" value="{{ old('Email') }}" 
                                       class="form-control rounded-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                       placeholder="correo@ejemplo.com">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="small font-weight-bold text-secondary ml-3 text-uppercase">Teléfono / Celular</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-left-pill bg-dark text-warning border-0 font-weight-bold px-4" id="prefijoPais" style="height: 55px;">+51</span>
                                    </div>
                                    <input type="text" name="Telefono" id="txtTelefono" value="{{ old('Telefono') }}" 
                                           class="form-control rounded-right-pill bg-light border-0 py-4 px-4 text-dark font-weight-bold shadow-none" 
                                           placeholder="999 999 999" onkeypress="return soloNumeros(event)" style="height: 55px;">
                                </div>
                            </div>
                        </div>

                        <div class="row pt-4 border-top">
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-warning btn-block btn-lg rounded-pill font-weight-bold shadow py-3 text-dark transition-all">
                                    <i class="fas fa-save mr-2"></i> FINALIZAR REGISTRO
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('huespedes.index') }}" class="btn btn-light btn-block btn-lg rounded-pill font-weight-bold py-3 text-muted shadow-sm">
                                    CANCELAR
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sincronizar país con prefijo telefónico
    function actualizarPrefijo() {
        const select = document.getElementById('selectPais');
        const prefijo = select.options[select.selectedIndex].getAttribute('data-prefijo');
        document.getElementById('prefijoPais').innerText = prefijo;
    }

    // Validaciones de teclado
    function soloLetras(e) {
        let key = e.keyCode || e.which;
        let tecla = String.fromCharCode(key).toLowerCase();
        let letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
        return letras.indexOf(tecla) != -1;
    }

    function soloNumeros(e) {
        let key = e.keyCode || e.which;
        return (key >= 48 && key <= 57);
    }

    // Ejecutar funciones al cargar por si hay datos viejos (old)
    window.onload = function() {
        actualizarPrefijo();
    };
</script>

<style>
    /* Paleta de colores y visibilidad */
    .text-dark { color: #000000 !important; }
    .bg-light { background-color: #f1f3f5 !important; }
    
    .form-control { 
        color: #000000 !important; 
        font-weight: 700 !important; 
    }

    .form-control::placeholder {
        color: #adb5bd !important;
        font-weight: normal !important;
    }

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

    .transition-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
    }
</style>
@endsection