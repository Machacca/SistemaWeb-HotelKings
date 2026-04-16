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
        padding: 30px 20px;
        text-align: center;
    }
    .numero-habitacion {
        font-size: 4.5rem;
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
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('reservas.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-chevron-left mr-1"></i> REGRESAR AL PANEL
        </a>
        <h2 style="font-family: 'Playfair Display', serif;" class="font-weight-bold mt-2">Check-in & Reservas</h2>
    </div>

    <form action="{{ route('reservas.store') }}" method="POST" id="form-reserva">
        @csrf
        <input type="hidden" name="IdHabitacion" value="{{ $habitacionSeleccionada->IdHabitacion }}">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        
                        <div class="btn-group btn-group-toggle d-flex mb-5 shadow-sm rounded-pill overflow-hidden" data-toggle="buttons">
                            <label class="btn btn-outline-dark active w-100 py-3 border-0">
                                <input type="radio" name="TipoRegistro" value="Reserva" checked> RESERVA
                            </label>
                            <label class="btn btn-outline-dark w-100 py-3 border-0">
                                <input type="radio" name="TipoRegistro" value="Ingreso"> CHECK-IN
                            </label>
                        </div>

                        <h6 class="text-uppercase gold-text font-weight-bold mb-3 small" style="letter-spacing: 1px;">Huésped Principal</h6>
                        <div class="row mb-5">
                            <div class="col-md-10 col-9">
                                <select name="IdHuesped" id="IdHuesped" class="form-control form-control-custom select2" required>
                                    <option value="">Seleccione el Huésped...</option>
                                    @foreach($huespedes as $h)
                                        <option value="{{ $h->IdHuesped }}">{{ $h->Nombre }} {{ $h->Apellido }} — {{ $h->NroDocumento }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-3">
                                <button type="button" class="btn btn-dark btn-block" style="border-radius: 12px; height: 52px;" data-toggle="modal" data-target="#modalNuevoHuesped">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-uppercase gold-text font-weight-bold mb-0 small">Acompañantes Adicionales</h6>
                            <button type="button" id="btn-add-acompanante" class="btn btn-link text-dark font-weight-bold p-0 text-decoration-none">
                                <i class="fas fa-plus-circle mr-1"></i> AGREGAR
                            </button>
                        </div>
                        <div id="contenedor-acompanantes" class="mb-5">
                            </div>

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

            <div class="col-lg-4">
                <div class="card card-resumen shadow-lg sticky-top" style="top: 20px;">
                    <div class="resumen-header">
                        <div class="tipo-habitacion-label">{{ $habitacionSeleccionada->tipo->Nombre }}</div>
                        <div class="numero-habitacion">{{ $habitacionSeleccionada->Numero }}</div>
                        <div class="text-muted small">PISO {{ $habitacionSeleccionada->Piso }}</div>
                    </div>

                    <div class="card-body p-4 pt-0">
                        <div class="row no-gutters mb-4 bg-dark rounded p-3" style="border: 1px solid #333;">
                            <div class="col-6 border-right border-secondary px-2 text-center">
                                <label class="small text-muted d-block text-uppercase mb-1" style="font-size: 0.65rem;">Check-In</label>
                                <input type="date" name="FechaEntrada" id="fecha_entrada" class="bg-transparent border-0 text-white w-100 text-center font-weight-bold" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6 px-2 text-center">
                                <label class="small text-muted d-block text-uppercase mb-1" style="font-size: 0.65rem;">Check-Out</label>
                                <input type="date" name="FechaSalida" id="fecha_salida" class="bg-transparent border-0 text-white w-100 text-center font-weight-bold" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small gold-text text-uppercase font-weight-bold mb-1">Precio x Noche (S/)</label>
                            <input type="number" name="PrecioNoche" id="precio_venta" class="form-control bg-dark text-white border-0 font-weight-bold" value="{{ $habitacionSeleccionada->tipo->Tarifa_base }}" step="0.01" style="font-size: 1.3rem; border-radius: 10px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small text-muted text-uppercase mb-1">Descuento aplicado</label>
                            <input type="number" name="Descuento" id="descuento" class="form-control bg-dark text-white border-0" value="0" style="border-radius: 10px;">
                        </div>

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
    </form>
</div>

@include('huespedes.modal_crear')

<script>
    let prodCount = 0;
    let acompCount = 0;

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
                    <select name="productos[${prodCount}][IdProducto]" class="form-control border-0 select-producto" required onchange="calcular()">
                        <option value="" data-precio="0">Seleccionar...</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->IdProducto }}" data-precio="{{ $p->PrecioVenta }}">{{ $p->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-4">
                    <input type="number" name="productos[${prodCount}][Cantidad]" class="form-control border-0 bg-light text-center" value="1" min="1" oninput="calcular()">
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

    // --- AGREGAR ACOMPAÑANTES ---
    document.getElementById('btn-add-acompanante').addEventListener('click', function() {
        const contenedor = document.getElementById('contenedor-acompanantes');
        const div = document.createElement('div');
        div.className = 'row mb-3 bg-white p-3 shadow-sm border rounded-lg mx-0 align-items-center';
        div.innerHTML = `
            <div class="col-md-4 mb-2 mb-md-0"><input type="text" name="acompanantes[${acompCount}][Nombre]" class="form-control form-control-custom" placeholder="Nombre" required></div>
            <div class="col-md-4 mb-2 mb-md-0"><input type="text" name="acompanantes[${acompCount}][Apellido]" class="form-control form-control-custom" placeholder="Apellido" required></div>
            <div class="col-md-3 mb-2 mb-md-0"><input type="text" name="acompanantes[${acompCount}][NroDocumento]" class="form-control form-control-custom" placeholder="DNI/RUC" required></div>
            <div class="col-md-1 text-center"><button type="button" class="btn btn-link text-danger" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times-circle fa-lg"></i></button></div>
        `;
        contenedor.appendChild(div);
        acompCount++;
    });

    // --- CÁLCULOS DINÁMICOS ---
    function calcular() {
        let precioHab = parseFloat(document.getElementById('precio_venta').value) || 0;
        let desc = parseFloat(document.getElementById('descuento').value) || 0;
        let f1 = document.getElementById('fecha_entrada').value;
        let f2 = document.getElementById('fecha_salida').value;
        
        let totalConsumos = 0;
        document.querySelectorAll('.item-producto').forEach(item => {
            let sel = item.querySelector('.select-producto');
            let precio = parseFloat(sel.options[sel.selectedIndex].getAttribute('data-precio')) || 0;
            let cant = parseFloat(item.querySelector('input[type="number"]').value) || 0;
            let sub = precio * cant;
            item.querySelector('.subtotal-producto').innerText = sub.toFixed(2);
            totalConsumos += sub;
        });

        if (f1 && f2) {
            let inicio = new Date(f1);
            let fin = new Date(f2);
            let diff = fin - inicio;
            let dias = Math.ceil(diff / (1000 * 60 * 60 * 24));
            
            if (dias <= 0) dias = 1;

            let totalFinal = (precioHab * dias) + totalConsumos - desc;
            
            document.getElementById('label_dias').innerText = dias;
            document.getElementById('total_final').innerText = 'S/ ' + totalFinal.toLocaleString('es-PE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    // Listeners para recálculo inmediato
    ['precio_venta', 'descuento', 'fecha_entrada', 'fecha_salida'].forEach(id => {
        document.getElementById(id).addEventListener('input', calcular);
    });

    // Refuerzo para abrir modal si hay problemas de JS
    $(document).ready(function() {
        $('[data-target="#modal_crear_huesped"]').on('click', function() {
            $('#modal_crear_huesped').modal('show');
        });
        calcular(); // Cálculo inicial
    });
</script>
@endsection