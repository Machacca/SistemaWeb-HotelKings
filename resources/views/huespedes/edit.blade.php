@extends('layouts.app')

@section('title', 'Editar Huésped')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Editar Huésped</h1>
            <p class="text-muted mb-0">Actualice los datos del huésped</p>
        </div>
        
        <a href="{{ route('huespedes.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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

    @php
        // Extraer prefijo del teléfono guardado
        $telefonoCompleto = $huesped->Telefono ?? '';
        $prefijoActual = '';
        $numeroActual = '';
        
        if ($telefonoCompleto) {
            if (preg_match('/^(\+\d+)\s*(.*)$/', $telefonoCompleto, $matches)) {
                $prefijoActual = $matches[1];
                $numeroActual = $matches[2];
            } else {
                $numeroActual = $telefonoCompleto;
            }
        }
    @endphp

    {{-- Tarjeta con Encabezado Negro --}}
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3" style="border-radius: 25px 25px 0 0;">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-user-edit mr-2 text-warning"></i> Editar Huésped
                </h5>
                <small class="text-muted">Modifique los datos del huésped</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('huespedes.update', $huesped->IdHuesped) }}" id="formHuespedEdit">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRES</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Nombre', $huesped->Nombre) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">APELLIDOS</label>
                            <input type="text" name="Apellido" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Apellido', $huesped->Apellido) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TIPO DOCUMENTO</label>
                            <select name="TipoDocumento" class="form-control rounded-pill bg-light border-0 px-4" required>
                                <option value="DNI" {{ old('TipoDocumento', $huesped->TipoDocumento) == 'DNI' ? 'selected' : '' }}>DNI</option>
                                <option value="PAS" {{ old('TipoDocumento', $huesped->TipoDocumento) == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                                <option value="CE" {{ old('TipoDocumento', $huesped->TipoDocumento) == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NÚMERO DOCUMENTO</label>
                            <input type="text" name="NroDocumento" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('NroDocumento', $huesped->NroDocumento) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CORREO ELECTRÓNICO</label>
                            <input type="email" name="Email" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Email', $huesped->Email) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NACIONALIDAD</label>
                            <select name="Nacionalidad" id="editSelectNacionalidad" class="form-control rounded-pill bg-light border-0 px-4" required>
                                <option value="">Seleccionar país...</option>
                                <option value="Perú" data-prefijo="+51" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Perú' ? 'selected' : '' }}>🇵🇪 Perú (+51)</option>
                                <option value="Argentina" data-prefijo="+54" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Argentina' ? 'selected' : '' }}>🇦🇷 Argentina (+54)</option>
                                <option value="Bolivia" data-prefijo="+591" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Bolivia' ? 'selected' : '' }}>🇧🇴 Bolivia (+591)</option>
                                <option value="Chile" data-prefijo="+56" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Chile' ? 'selected' : '' }}>🇨🇱 Chile (+56)</option>
                                <option value="Colombia" data-prefijo="+57" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Colombia' ? 'selected' : '' }}>🇨🇴 Colombia (+57)</option>
                                <option value="Ecuador" data-prefijo="+593" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Ecuador' ? 'selected' : '' }}>🇪🇨 Ecuador (+593)</option>
                                <option value="España" data-prefijo="+34" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'España' ? 'selected' : '' }}>🇪🇸 España (+34)</option>
                                <option value="México" data-prefijo="+52" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'México' ? 'selected' : '' }}>🇲🇽 México (+52)</option>
                                <option value="Estados Unidos" data-prefijo="+1" {{ old('Nacionalidad', $huesped->Nacionalidad) == 'Estados Unidos' ? 'selected' : '' }}>🇺🇸 Estados Unidos (+1)</option>
                                <option value="Otro" data-prefijo="" {{ !in_array(old('Nacionalidad', $huesped->Nacionalidad), ['Perú', 'Argentina', 'Bolivia', 'Chile', 'Colombia', 'Ecuador', 'España', 'México', 'Estados Unidos']) && old('Nacionalidad', $huesped->Nacionalidad) ? 'selected' : '' }}>🌐 Otro (ingrese código)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TELÉFONO / CELULAR</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark text-warning border-0 rounded-pill-left" id="editPrefijoTelefono" style="border-radius: 50px 0 0 50px;">{{ $prefijoActual ?: '+51' }}</span>
                                </div>
                                <input type="tel" name="Telefono_temp" id="editInputTelefono" class="form-control rounded-pill-right bg-light border-0 px-4" 
                                       value="{{ old('Telefono', $numeroActual) }}" placeholder="987 654 321" style="border-radius: 0 50px 50px 0;">
                            </div>
                            <small class="text-muted ml-2">El código de país se agregará automáticamente</small>
                        </div>
                    </div>

                    @if(!$huesped->activo)
                        <div class="alert alert-warning text-center mt-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Este huésped está INACTIVO
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('huespedes.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
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
    // Actualizar prefijo según nacionalidad
    const selectNacionalidad = document.getElementById('editSelectNacionalidad');
    const prefijoSpan = document.getElementById('editPrefijoTelefono');
    const inputTelefono = document.getElementById('editInputTelefono');
    
    function actualizarPrefijo() {
        const selectedOption = selectNacionalidad.options[selectNacionalidad.selectedIndex];
        let prefijo = selectedOption.getAttribute('data-prefijo');
        
        if (prefijo && prefijo !== '') {
            prefijoSpan.textContent = prefijo;
        } else if (selectedOption.value === 'Otro') {
            const codigoManual = prompt('Ingrese el código de país (ej: +xx):', prefijoSpan.textContent);
            if (codigoManual && codigoManual !== '') {
                prefijoSpan.textContent = codigoManual;
                selectedOption.setAttribute('data-prefijo', codigoManual);
            }
        }
    }
    
    selectNacionalidad.addEventListener('change', actualizarPrefijo);
    
    // Al enviar, concatenar prefijo + número
    document.getElementById('formHuespedEdit').addEventListener('submit', function(e) {
        const prefijo = prefijoSpan.textContent;
        let numero = inputTelefono.value.trim();
        numero = numero.replace(/[^\d]/g, '');
        
        if (numero !== '') {
            const telefonoCompleto = prefijo + ' ' + numero;
            const campoOculto = document.createElement('input');
            campoOculto.type = 'hidden';
            campoOculto.name = 'Telefono';
            campoOculto.value = telefonoCompleto;
            this.appendChild(campoOculto);
        }
    });
</script>

<style>
    .rounded-pill-left { border-radius: 50px 0 0 50px !important; }
    .rounded-pill-right { border-radius: 0 50px 50px 0 !important; }
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection