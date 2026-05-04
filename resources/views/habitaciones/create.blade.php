@extends('layouts.app')

@section('title', 'Nueva Habitación')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Nueva Habitación</h1>
            <p class="text-muted mb-0">Complete los datos para registrar una nueva unidad</p>
        </div>
        
        <a href="{{ route('habitaciones.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
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
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3" style="border-radius: 25px 25px 0 0;">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-bed mr-2 text-warning"></i> Configuración de Habitación
                </h5>
                <small class="text-muted">Complete los detalles para registrar la unidad</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('habitaciones.store') }}" id="formHabitacion">
                    @csrf

                    <div class="row">
                        {{-- Número de Habitación con prefijo --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NÚMERO DE HABITACIÓN</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark text-warning border-0 rounded-pill-left" id="prefijoHotel" style="border-radius: 50px 0 0 50px;">
                                        {{-- Prefijo por defecto --}}
                                        @php
                                            $primerHotel = $hoteles->first();
                                            $prefijoDefault = $primerHotel ? ($primerHotel->codigo ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $primerHotel->Nombre), 0, 3))) : 'HOT';
                                        @endphp
                                        {{ $prefijoDefault }}
                                    </span>
                                </div>
                                <input type="text" name="Numero_temp" id="inputNumero" class="form-control rounded-pill-right bg-light border-0 px-4" 
                                       placeholder="Ej: 101" style="border-radius: 0 50px 50px 0;" required>
                            </div>
                            <small class="text-muted ml-2">El código del hotel se agregará automáticamente (ej: {{ $prefijoDefault }}-101)</small>
                        </div>

                        {{-- Piso --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">PISO / NIVEL</label>
                            <input type="text" name="Piso" value="{{ old('Piso') }}" required
                                   class="form-control rounded-pill bg-light border-0 px-4" 
                                   placeholder="Ej: 1, 2, 3">
                        </div>

                        {{-- Tipo de Habitación --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CATEGORÍA</label>
                            <select name="IdTipo" id="selectTipo" required class="form-control rounded-pill bg-light border-0 px-4 custom-select-pill">
                                <option value="" disabled selected>Seleccionar...</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->IdTipo }}" {{ old('IdTipo')==$tipo->IdTipo?'selected':'' }}>
                                        {{ $tipo->Nombre }} (S/.{{ number_format($tipo->Tarifa_base, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sede / Hotel con código visible --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">SEDE / HOTEL</label>
                            @if(auth()->user()->IdRol == 4)
                                <select name="IdHotel" id="selectHotel" required class="form-control rounded-pill bg-light border-0 px-4 custom-select-pill">
                                    <option value="" disabled selected>Seleccionar Hotel...</option>
                                    @foreach($hoteles as $hotel)
                                        @php
                                            $hotelPrefijo = $hotel->codigo ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $hotel->Nombre), 0, 3));
                                        @endphp
                                        <option value="{{ $hotel->IdHotel }}" data-prefijo="{{ $hotelPrefijo }}" {{ old('IdHotel')==$hotel->IdHotel?'selected':'' }}>
                                            {{ $hotel->Nombre }} ({{ $hotelPrefijo }})
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                @php
                                    $hotelUsuario = auth()->user()->hotel;
                                    $prefijoUsuario = $hotelUsuario->codigo ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $hotelUsuario->Nombre), 0, 3));
                                @endphp
                                <div class="p-3 bg-light d-flex align-items-center justify-content-between" style="border-radius: 15px;">
                                    <div>
                                        <i class="fas fa-hotel mr-2 text-warning"></i>
                                        <span class="font-weight-bold">{{ $hotelUsuario->Nombre ?? 'Sede Asignada' }}</span>
                                        <span class="badge badge-dark ml-2">{{ $prefijoUsuario }}</span>
                                    </div>
                                    <input type="hidden" name="IdHotel" value="{{ auth()->user()->IdHotel }}">
                                    <input type="hidden" id="hiddenPrefijo" value="{{ $prefijoUsuario }}">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('habitaciones.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2 text-warning"></i> Guardar Habitación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ============================================
    // ACTUALIZAR PREFIJO SEGÚN HOTEL SELECCIONADO
    // ============================================
    const selectHotel = document.getElementById('selectHotel');
    const prefijoSpan = document.getElementById('prefijoHotel');
    
    // Para usuarios no Master, el prefijo ya está fijo
    @if(auth()->user()->IdRol != 4)
        const hiddenPrefijo = document.getElementById('hiddenPrefijo');
        if (hiddenPrefijo) {
            prefijoSpan.textContent = hiddenPrefijo.value;
        }
    @endif
    
    function actualizarPrefijo() {
        if (selectHotel) {
            const selectedOption = selectHotel.options[selectHotel.selectedIndex];
            let prefijo = selectedOption.getAttribute('data-prefijo');
            if (prefijo) {
                prefijoSpan.textContent = prefijo;
            }
        }
    }
    
    if (selectHotel) {
        selectHotel.addEventListener('change', actualizarPrefijo);
        // Ejecutar al cargar para establecer el prefijo inicial
        actualizarPrefijo();
    }
    
    // ============================================
    // ENVIAR FORMULARIO CON NÚMERO COMPLETO
    // ============================================
    document.getElementById('formHabitacion').addEventListener('submit', function(e) {
        const prefijo = prefijoSpan.textContent;
        let numero = document.getElementById('inputNumero').value.trim();
        
        // Limpiar el número (eliminar espacios y guiones)
        numero = numero.replace(/[\s-]/g, '');
        
        if (numero === '') {
            e.preventDefault();
            SwalError('Error', 'El número de habitación es obligatorio');
            return;
        }
        
        // Concatenar prefijo y número
        const numeroCompleto = prefijo + '-' + numero;
        
        // Crear un campo oculto con el número completo
        const campoOculto = document.createElement('input');
        campoOculto.type = 'hidden';
        campoOculto.name = 'Numero';
        campoOculto.value = numeroCompleto;
        
        // Eliminar el campo original para que no se envíe duplicado
        const inputNumero = document.getElementById('inputNumero');
        inputNumero.removeAttribute('name');
        
        this.appendChild(campoOculto);
        
        console.log('Número de habitación a guardar:', numeroCompleto);
    });
</script>

<style>
    .custom-select-pill {
        -webkit-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23f1c40f' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1.2rem center;
        background-size: 1em;
    }
    
    .rounded-pill-left {
        border-radius: 50px 0 0 50px !important;
    }
    
    .rounded-pill-right {
        border-radius: 0 50px 50px 0 !important;
    }

    .form-control:focus, select:focus {
        background-color: #fcfcfc !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15) !important;
        border: 1px solid #ffc107 !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection