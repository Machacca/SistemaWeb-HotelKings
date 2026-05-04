@extends('layouts.app')

@section('content')
<style>
    /* Estética de Inputs Luxury */
    .form-control-custom {
        background-color: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        border-radius: 12px !important;
        padding: 12px 15px !important;
        font-size: 1rem !important;
        height: auto !important;
        font-weight: 500 !important;
        transition: all 0.3s ease;
    }
    .form-control-custom:focus {
        background-color: #ffffff !important;
        border-color: #c9a45c !important;
        box-shadow: 0 0 0 0.2rem rgba(201, 164, 92, 0.15) !important;
    }

    /* Tarjeta de Resumen */
    .card-resumen {
        background: #1e1e1e;
        color: #f1f1f1;
        border-radius: 25px;
        border: 1px solid #333;
    }
    .resumen-header {
        background: linear-gradient(180deg, rgba(201, 164, 92, 0.2) 0%, rgba(30, 30, 30, 0) 100%);
        padding: 25px 20px;
        text-align: center;
    }
    .hotel-info {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 10px;
        margin-top: 15px;
        text-align: left;
    }
    .hotel-info-item {
        font-size: 0.7rem;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }
    .hotel-info-item i {
        width: 20px;
        margin-right: 8px;
        color: #c9a45c;
    }
    .numero-habitacion {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        color: #ffffff;
    }
    .gold-text { color: #c9a45c; }
    .btn-luxury {
        background: #c9a45c;
        color: white;
        border-radius: 15px;
        font-weight: 700;
        padding: 15px;
        transition: 0.3s;
        border: none;
    }
    .btn-luxury:hover { background: #b38f4a; color: white; transform: translateY(-1px); }
    
    .item-producto {
        background: #fff;
        border-left: 4px solid #c9a45c !important;
    }

    /* Estilos para el modal de búsqueda */
    .modal-busqueda .modal-content {
        border-radius: 20px;
        border: none;
    }
    .modal-busqueda .modal-header {
        background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 20px 25px;
        border-bottom: 1px solid #c9a45c;
    }
    .modal-busqueda .modal-body {
        padding: 25px;
        max-height: 500px;
        overflow-y: auto;
    }
    .huesped-item {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border: 1px solid #e9ecef;
        background: white;
    }
    .huesped-item:hover {
        background: #f8f9fa;
        border-color: #c9a45c;
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .huesped-item.selected {
        background: #c9a45c;
        color: white;
        border-color: #c9a45c;
    }
    .huesped-item.selected .text-muted {
        color: rgba(255,255,255,0.8) !important;
    }
    .search-box {
        position: relative;
        margin-bottom: 20px;
    }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #c9a45c;
    }
    .search-box input {
        padding-left: 45px;
        border-radius: 50px;
        border: 1px solid #e9ecef;
        height: 50px;
        font-size: 1rem;
    }
    .search-box input:focus {
        border-color: #c9a45c;
        box-shadow: 0 0 0 0.2rem rgba(201, 164, 92, 0.15);
    }
    
    /* Estilos para Flatpickr personalizados */
    .flatpickr-calendar {
        background: #1e1e1e !important;
        border: 1px solid #c9a45c !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
    }
    .flatpickr-calendar .flatpickr-month {
        background: #1e1e1e !important;
        color: #c9a45c !important;
    }
    .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: #1e1e1e !important;
        color: #c9a45c !important;
    }
    .flatpickr-calendar .flatpickr-weekday {
        color: #c9a45c !important;
    }
    .flatpickr-calendar .flatpickr-day {
        color: #f1f1f1 !important;
    }
    .flatpickr-calendar .flatpickr-day.selected {
        background: #c9a45c !important;
        border-color: #c9a45c !important;
        color: #1e1e1e !important;
    }
    .flatpickr-calendar .flatpickr-day:hover {
        background: rgba(201, 164, 92, 0.3) !important;
    }
    .flatpickr-calendar .flatpickr-day.today {
        border-color: #c9a45c !important;
    }
    .date-input {
        background: #2a2a2a !important;
        border: 1px solid #c9a45c !important;
        color: white !important;
        border-radius: 10px !important;
        padding: 10px !important;
        text-align: center !important;
        cursor: pointer !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('reservas.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-chevron-left mr-1"></i> REGRESAR AL PANEL
        </a>
        <h2 style="font-family: 'Playfair Display', serif;" class="font-weight-bold mt-2">Nueva Reserva</h2>
    </div>

    <form action="{{ route('reservas.store') }}" method="POST" id="form-reserva">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        
                        {{-- Selector de Tipo de Registro --}}
                        <div class="btn-group btn-group-toggle d-flex mb-5 shadow-sm rounded-pill overflow-hidden" data-toggle="buttons">
                            <label class="btn btn-outline-dark active w-100 py-3 border-0">
                                <input type="radio" name="TipoRegistro" value="Reserva" checked> RESERVA
                            </label>
                            <label class="btn btn-outline-dark w-100 py-3 border-0">
                                <input type="radio" name="TipoRegistro" value="Ingreso"> CHECK-IN DIRECTO
                            </label>
                        </div>

                        <div class="row mb-5">
                            {{-- Canal de Reserva --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-uppercase gold-text font-weight-bold mb-3 small" style="letter-spacing: 1px;">Canal de Reserva</h6>
                                <select name="IdCanal" id="IdCanal" class="form-control form-control-custom" required>
                                    <option value="">Seleccione origen...</option>
                                    @foreach($canales as $canal)
                                        <option value="{{ $canal->IdCanal }}" {{ $canal->IdCanal == 1 ? 'selected' : '' }}>
                                            {{ $canal->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Huésped Principal con botón de búsqueda --}}
                            <div class="col-md-6">
                                <h6 class="text-uppercase gold-text font-weight-bold mb-3 small" style="letter-spacing: 1px;">Huésped Principal</h6>
                                <div class="d-flex">
                                    <input type="text" id="huesped_seleccionado_display" class="form-control form-control-custom mr-2" placeholder="Seleccione un huésped..." readonly required>
                                    <input type="hidden" name="IdHuesped" id="IdHuesped" value="">
                                    <button type="button" class="btn btn-dark" style="border-radius: 12px; min-width: 52px;" data-toggle="modal" data-target="#modalBuscarHuesped">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark ml-2" style="border-radius: 12px; min-width: 52px;" data-toggle="modal" data-target="#modalNuevoHuesped">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Acompañantes --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-uppercase gold-text font-weight-bold mb-0 small">Acompañantes Adicionales</h6>
                            <button type="button" id="btn-add-acompanante" class="btn btn-link text-dark font-weight-bold p-0 text-decoration-none">
                                <i class="fas fa-plus-circle mr-1"></i> AGREGAR
                            </button>
                        </div>
                        <div id="contenedor-acompanantes" class="mb-5"></div>

                        {{-- Consumos --}}
                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-uppercase text-success font-weight-bold mb-0 small">Consumos Extra / Servicios</h6>
                                <button type="button" id="btn-add-producto" class="btn btn-link text-success font-weight-bold p-0 text-decoration-none">
                                    <i class="fas fa-cart-plus mr-1"></i> AÑADIR PRODUCTO
                                </button>
                            </div>
                            <div id="contenedor-productos">
                                <div class="text-center p-4 bg-light rounded-lg text-muted mb-4" id="msg-sin-productos" style="border: 1px dashed #ddd;">
                                    <small>No se han registrado consumos adicionales.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar de Resumen --}}
            <div class="col-lg-4">
                <div class="card card-resumen shadow-lg sticky-top" style="top: 20px;">
                    <div class="resumen-header">
                        <div class="numero-habitacion">{{ $habitacion->Numero }}</div>
                        <div class="text-uppercase small font-weight-bold gold-text mt-1">
                            {{ $habitacion->tipo->Nombre ?? 'Habitación' }}
                        </div>
                        <div class="small text-muted">{{ $habitacion->tipo->Capacidad ?? '1' }} persona(s)</div>
                        
                        {{-- Información del hotel elegante --}}
                        <div class="hotel-info">
                            <div class="hotel-info-item">
                                <i class="fas fa-hotel"></i>
                                <span>{{ $habitacion->hotel->Nombre ?? 'Hotel Inka Kings' }}</span>
                            </div>
                            @if($habitacion->hotel->Direccion)
                            <div class="hotel-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $habitacion->hotel->Direccion }}</span>
                            </div>
                            @endif
                            @if($habitacion->hotel->Telefono)
                            <div class="hotel-info-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $habitacion->hotel->Telefono }}</span>
                            </div>
                            @endif
                            <div class="hotel-info-item">
                                <i class="fas fa-code-branch"></i>
                                <span>Código: {{ $habitacion->hotel->codigo ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-0">
                        {{-- Fechas con Flatpickr --}}
                        <div class="row no-gutters mb-4 bg-dark rounded p-3" style="border: 1px solid #333;">
                            <div class="col-6 border-right border-secondary px-2 text-center">
                                <label class="small text-muted d-block text-uppercase mb-1" style="font-size: 0.65rem;">Check-In</label>
                                <input type="text" name="FechaCheckIn" id="fecha_entrada" class="date-input w-100" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6 px-2 text-center">
                                <label class="small text-muted d-block text-uppercase mb-1" style="font-size: 0.65rem;">Check-Out</label>
                                <input type="text" name="FechaCheckOut" id="fecha_salida" class="date-input w-100" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>

                        {{-- Precio por noche (editable) --}}
                        <div class="form-group mb-3">
                            <label class="small gold-text text-uppercase font-weight-bold mb-1">Precio por Noche (S/)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark text-warning border-0">S/</span>
                                </div>
                                <input type="number" name="habitaciones[0][PrecioNoche]" id="precio_noche" 
                                       class="form-control bg-dark text-white border-0 font-weight-bold" 
                                       value="{{ $habitacion->tipo->Tarifa_base }}" step="0.01" required>
                            </div>
                            <small class="text-muted">Precio de referencia: S/ {{ number_format($habitacion->tipo->Tarifa_base, 2) }}</small>
                        </div>

                        {{-- Adelanto / Abono inicial --}}
                        <div class="form-group mb-4">
                            <label class="small text-muted text-uppercase mb-1">Adelanto (S/)</label>
                            <input type="number" name="PagosAdelantados" id="adelanto" class="form-control bg-dark text-white border-0" value="0" step="0.01">
                            <small class="text-muted">Pago inicial del cliente (opcional)</small>
                        </div>

                        {{-- Totales --}}
                        <div class="border-top border-secondary pt-4 mt-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Días de estancia:</span>
                                <span class="font-weight-bold text-white"><span id="label_dias">1</span> Noche(s)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <span class="gold-text h6 font-weight-bold mb-2">TOTAL A PAGAR</span>
                                <h2 class="font-weight-bold mb-0 text-white" id="total_final">S/ 0.00</h2>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-luxury btn-lg btn-block mt-4 shadow border-0 py-3">
                            PROCESAR REGISTRO
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenedor para la habitación (única) --}}
        <div id="habitaciones-container">
            <input type="hidden" name="habitaciones[0][IdHabitacion]" value="{{ $habitacion->IdHabitacion }}">
        </div>
    </form>
</div>

{{-- Modales de búsqueda y creación de huéspedes --}}
<div class="modal fade modal-busqueda" id="modalBuscarHuesped" tabindex="-1" role="dialog" aria-labelledby="modalBuscarHuespedLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarHuespedLabel">
                    <i class="fas fa-users mr-2"></i>Buscar Huésped
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="busquedaHuespedInput" class="form-control" placeholder="Buscar por nombre, apellido o documento...">
                </div>
                <div id="resultadosBusqueda">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <p>Ingrese un término de búsqueda para encontrar huéspedes</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-luxury" id="btnSeleccionarHuesped" disabled>Seleccionar Huésped</button>
            </div>
        </div>
    </div>
</div>

@include('huespedes.modal_crear')

@push('scripts')
{{-- Flatpickr CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
    let prodCount = 0;
    let acompCount = 0;
    let huespedSeleccionado = null;
    
    // Precio de la habitación
    let precioNoche = {{ $habitacion->tipo->Tarifa_base }};
    
    // Inicializar Flatpickr para fechas
    const fechaEntrada = flatpickr("#fecha_entrada", {
        locale: "es",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates[0]) {
                fechaSalida.set("minDate", selectedDates[0]);
            }
            calcular();
        }
    });

    const fechaSalida = flatpickr("#fecha_salida", {
        locale: "es",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function() {
            calcular();
        }
    });
    
    // Actualizar el precio cuando se modifica
    document.getElementById('precio_noche')?.addEventListener('input', function() {
        precioNoche = parseFloat(this.value) || 0;
        calcular();
    });

    // --- AGREGAR PRODUCTOS ---
    document.getElementById('btn-add-producto').addEventListener('click', function() {
        const msg = document.getElementById('msg-sin-productos');
        if(msg) msg.classList.add('d-none');
        
        const contenedor = document.getElementById('contenedor-productos');
        const div = document.createElement('div');
        div.className = 'item-producto mb-2 shadow-sm p-3 rounded-lg border';
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-6 col-12 mb-2 mb-md-0">
                    <select name="consumos[${prodCount}][IdProducto]" class="form-control border-0 select-producto" required onchange="calcular()">
                        <option value="" data-precio="0">Seleccionar...</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->IdProducto }}" data-precio="{{ $p->PrecioVenta }}">{{ $p->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-4">
                    <input type="number" name="consumos[${prodCount}][Cantidad]" class="form-control border-0 bg-light text-center" value="1" min="1" oninput="calcular()">
                </div>
                <div class="col-md-3 col-6 text-right">
                    <span class="small text-muted">S/ </span><span class="font-weight-bold subtotal-producto">0.00</span>
                </div>
                <div class="col-md-1 col-2 text-right">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('.item-producto').remove(); calcular();"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        `;
        contenedor.appendChild(div);
        prodCount++;
    });

    // --- AGREGAR ACOMPAÑANTES (asociados a la habitación principal) ---
    document.getElementById('btn-add-acompanante').addEventListener('click', function() {
        const contenedor = document.getElementById('contenedor-acompanantes');
        const div = document.createElement('div');
        div.className = 'row mb-3 bg-white p-3 shadow-sm border rounded-lg mx-0 align-items-center';
        div.innerHTML = `
            <div class="col-md-5 mb-2 mb-md-0">
                <input type="text" name="habitaciones[0][acompanantes][${acompCount}][Nombre]" class="form-control form-control-custom" placeholder="Nombre" required>
            </div>
            <div class="col-md-5 mb-2 mb-md-0">
                <input type="text" name="habitaciones[0][acompanantes][${acompCount}][Apellido]" class="form-control form-control-custom" placeholder="Apellido" required>
            </div>
            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-link text-danger" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times-circle fa-lg"></i>
                </button>
            </div>
        `;
        contenedor.appendChild(div);
        acompCount++;
    });

    // --- CÁLCULOS DINÁMICOS ---
    function calcular() {
        let f1 = document.getElementById('fecha_entrada').value;
        let f2 = document.getElementById('fecha_salida').value;
        let adelanto = parseFloat(document.getElementById('adelanto').value) || 0;
        
        let totalConsumos = 0;
        document.querySelectorAll('.item-producto').forEach(item => {
            let sel = item.querySelector('.select-producto');
            if(sel) {
                let precio = parseFloat(sel.options[sel.selectedIndex].getAttribute('data-precio')) || 0;
                let cant = parseFloat(item.querySelector('input[type="number"]').value) || 0;
                let sub = precio * cant;
                let subtotalSpan = item.querySelector('.subtotal-producto');
                if(subtotalSpan) subtotalSpan.innerText = sub.toFixed(2);
                totalConsumos += sub;
            }
        });

        if (f1 && f2) {
            let inicio = new Date(f1);
            let fin = new Date(f2);
            let diff = fin - inicio;
            let dias = Math.ceil(diff / (1000 * 60 * 60 * 24));
            
            if (dias <= 0) dias = 1;

            let totalFinal = (precioNoche * dias) + totalConsumos - adelanto;
            
            document.getElementById('label_dias').innerText = dias;
            document.getElementById('total_final').innerHTML = 'S/ ' + totalFinal.toLocaleString('es-PE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    // Event listeners para cálculos
    document.getElementById('adelanto')?.addEventListener('input', calcular);
    
    // Calcular inicial
    calcular();

    // ================================================
    // BÚSQUEDA DE HUÉSPEDES EN MODAL
    // ================================================
    $(document).ready(function() {
        let timeoutId;
        
        $('#busquedaHuespedInput').on('input', function() {
            clearTimeout(timeoutId);
            const term = $(this).val();
            
            if (term.length < 2) {
                $('#resultadosBusqueda').html(`
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <p>Ingrese al menos 2 caracteres para buscar</p>
                    </div>
                `);
                return;
            }
            
            $('#resultadosBusqueda').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2 text-muted">Buscando huéspedes...</p>
                </div>
            `);
            
            timeoutId = setTimeout(function() {
                $.ajax({
                    url: "{{ url('/buscar-huespedes') }}",
                    method: 'GET',
                    data: { q: term },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length === 0) {
                            $('#resultadosBusqueda').html(`
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash fa-2x mb-3"></i>
                                    <p>No se encontraron huéspedes con "${term}"</p>
                                </div>
                            `);
                            return;
                        }
                        
                        let html = '';
                        data.forEach(function(huesped) {
                            html += `
                                <div class="huesped-item" data-id="${huesped.id}" data-nombre="${huesped.text}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">${huesped.text}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card mr-1"></i>ID: ${huesped.id}
                                            </small>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            `;
                        });
                        
                        $('#resultadosBusqueda').html(html);
                        
                        $('.huesped-item').off('click').on('click', function() {
                            $('.huesped-item').removeClass('selected');
                            $(this).addClass('selected');
                            
                            huespedSeleccionado = {
                                id: $(this).data('id'),
                                nombre: $(this).data('nombre')
                            };
                            
                            $('#btnSeleccionarHuesped').prop('disabled', false);
                        });
                    },
                    error: function() {
                        $('#resultadosBusqueda').html(`
                            <div class="alert alert-danger">
                                Error al buscar huéspedes. Intente nuevamente.
                            </div>
                        `);
                    }
                });
            }, 300);
        });

        $('#btnSeleccionarHuesped').off('click').on('click', function() {
            if (huespedSeleccionado) {
                $('#IdHuesped').val(huespedSeleccionado.id);
                $('#huesped_seleccionado_display').val(huespedSeleccionado.nombre);
                $('#modalBuscarHuesped').modal('hide');
                
                $('#busquedaHuespedInput').val('');
                $('#resultadosBusqueda').html(`
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <p>Ingrese al menos 2 caracteres para buscar</p>
                    </div>
                `);
                $('#btnSeleccionarHuesped').prop('disabled', true);
                huespedSeleccionado = null;
            }
        });

        $('#modalBuscarHuesped').on('hidden.bs.modal', function() {
            $('#busquedaHuespedInput').val('');
            $('#resultadosBusqueda').html(`
                <div class="text-center text-muted py-4">
                    <i class="fas fa-search fa-2x mb-3"></i>
                    <p>Ingrese al menos 2 caracteres para buscar</p>
                </div>
            `);
            $('#btnSeleccionarHuesped').prop('disabled', true);
            huespedSeleccionado = null;
        });
        
        $('#modalBuscarHuesped').on('shown.bs.modal', function() {
            $('#busquedaHuespedInput').focus();
        });
    });
</script>
@endpush
@endsection