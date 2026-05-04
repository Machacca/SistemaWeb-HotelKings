@extends('layouts.app')

@section('title', 'Gestión de Estancia')

@section('content')
<style>
    .consumo-item {
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .consumo-item:hover {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .acompanante-item {
        background: white;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .acompanante-item:hover {
        background: #f8f9fa;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a;">Gestión de Estancia</h1>
            <p class="text-muted mb-0">Reserva #{{ $reserva->IdReserva }} - {{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</p>
        </div>
        <div>
            <a href="{{ route('reservas.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
                <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Mapa
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Columna izquierda: Gestión --}}
        <div class="col-lg-8">
            {{-- Habitaciones --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-bed mr-2 text-warning"></i> Habitaciones</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Habitación</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Precio/Noche</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reserva->detalles as $detalle)
                                <tr>
                                    <td>
                                        <strong>{{ $detalle->habitacion->Numero }}</strong>
                                        <br>
                                        <small class="text-muted">Piso {{ $detalle->habitacion->Piso }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($detalle->FechaCheckIn)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($detalle->FechaCheckOut)->format('d/m/Y') }}</td>
                                    <td>S/ {{ number_format($detalle->PrecioNoche, 2) }}</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning btn-extender" 
                                                data-toggle="modal" 
                                                data-target="#modalExtenderEstadia" 
                                                data-checkout="{{ $detalle->FechaCheckOut }}">
                                            <i class="fas fa-calendar-plus mr-1"></i> Extender
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Consumos --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center" style="border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart mr-2 text-warning"></i> Consumos</h5>
                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalAgregarConsumo">
                        <i class="fas fa-plus mr-1"></i> Agregar Consumo
                    </button>
                </div>
                <div class="card-body p-0">
                    @if($reserva->consumos->where('EstadoPago', false)->count() > 0 || $reserva->consumos->where('EstadoPago', true)->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unit.</th>
                                        <th>Subtotal</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reserva->consumos as $consumo)
                                    <tr>
                                        <td>
                                            <strong>{{ $consumo->producto->Nombre }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $consumo->FechaConsumo->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>{{ $consumo->Cantidad }}</td>
                                        <td>S/ {{ number_format($consumo->PrecioVenta, 2) }}</td>
                                        <td><strong>S/ {{ number_format($consumo->Cantidad * $consumo->PrecioVenta, 2) }}</strong></td>
                                        <td>
                                            @if($consumo->EstadoPago)
                                                <span class="badge badge-success">Pagado</span>
                                            @else
                                                <span class="badge badge-warning text-dark">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$consumo->EstadoPago)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger btn-eliminar-consumo" 
                                                        data-id="{{ $consumo->IdConsumo }}"
                                                        data-producto="{{ $consumo->producto->Nombre }}"
                                                        data-cantidad="{{ $consumo->Cantidad }}"
                                                        data-precio="{{ $consumo->PrecioVenta }}"
                                                        data-subtotal="{{ number_format($consumo->Cantidad * $consumo->PrecioVenta, 2) }}"
                                                        data-stock="{{ $consumo->producto->StockActual }}"
                                                        data-fecha="{{ $consumo->FechaConsumo->format('d/m/Y H:i') }}"
                                                        title="Eliminar consumo">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @else
                                                <small class="text-muted">Pagado</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay consumos registrados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Columna derecha: Resumen y Acciones --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 20px;">
                <div class="card-header bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-calculator mr-2 text-warning"></i> Resumen de Cuenta</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase mb-1">Subtotal Hospedaje</label>
                        <p class="h5 mb-0">S/ {{ number_format($reserva->total_hospedaje, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase mb-1">Consumos</label>
                        <p class="h5 mb-0">S/ {{ number_format($reserva->total_consumos, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase mb-1">Adelantos / Abonos</label>
                        <p class="h5 mb-0 text-danger">- S/ {{ number_format($reserva->detalles->sum('PagosAdelantados'), 2) }}</p>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase mb-1">SALDO PENDIENTE</label>
                        <h2 class="mb-0 {{ $reserva->saldo_pendiente > 0 ? 'text-success' : 'text-danger' }}">
                            S/ {{ number_format($reserva->saldo_pendiente, 2) }}
                        </h2>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase mb-1">Registrar Abono</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">S/</span>
                            </div>
                            <input type="number" id="montoAbono" class="form-control" placeholder="0.00" step="0.01" min="0.01">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="btnRegistrarAbono">
                                    <i class="fas fa-check mr-1"></i> Aplicar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg btn-block" id="btnProcesarCheckout">
                            <i class="fas fa-cash-register mr-2"></i> PROCESAR CHECKOUT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: AGREGAR CONSUMO --}}
<div class="modal fade" id="modalAgregarConsumo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-cart-plus mr-2"></i> Agregar Consumo</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAgregarConsumo" method="POST" action="{{ route('reservas.update', $reserva->IdReserva) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Producto</label>
                        <select name="consumos[0][IdProducto]" class="form-control form-control-lg" required>
                            <option value="">Seleccionar producto...</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->IdProducto }}" {{ $producto->StockActual <= 0 ? 'disabled' : '' }}>
                                    {{ $producto->Nombre }} - S/ {{ number_format($producto->PrecioVenta, 2) }} (Stock: {{ $producto->StockActual }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Cantidad</label>
                        <input type="number" name="consumos[0][Cantidad]" class="form-control form-control-lg" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-lg">Agregar Consumo</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: EXTENDER ESTANCIA --}}
<div class="modal fade" id="modalExtenderEstadia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus mr-2"></i> Extender Estancia
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formExtenderEstadia" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Check-Out Actual:</strong> 
                        <span id="infoCheckOutActual" class="h5 ml-2">-</span>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Nueva Fecha de Check-Out</label>
                        <input type="date" name="ExtenderCheckOut" id="nuevaFechaCheckOut" 
                               class="form-control form-control-lg" required>
                        <small class="text-muted">Seleccione una fecha posterior al check-out actual</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="fas fa-calendar-check mr-2"></i> Confirmar Extensión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: ELIMINAR CONSUMO --}}
<div class="modal fade" id="modalEliminarConsumo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt mr-2"></i> Eliminar Consumo
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{-- Información del producto (solo lectura) --}}
                <div class="card bg-light mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-box mr-2"></i> Información del Producto
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase">Producto</small>
                                <p class="font-weight-bold h5 mb-0" id="infoProductoNombre">-</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase">Fecha de Consumo</small>
                                <p class="font-weight-bold mb-0" id="infoFechaConsumo">-</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <small class="text-muted text-uppercase">Cantidad</small>
                                <p class="font-weight-bold h5 mb-0" id="infoCantidad">-</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <small class="text-muted text-uppercase">Precio Unit.</small>
                                <p class="font-weight-bold mb-0" id="infoPrecioUnitario">-</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <small class="text-muted text-uppercase">Subtotal</small>
                                <p class="font-weight-bold text-danger h5 mb-0" id="infoSubtotal">-</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <small class="text-muted text-uppercase">Stock Actual</small>
                                <p class="font-weight-bold mb-0" id="infoStockActual">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Cantidad a devolver --}}
                <div class="alert alert-success mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-undo fa-2x mr-3"></i>
                        <div>
                            <h6 class="mb-1">Cantidad a devolver al inventario</h6>
                            <h4 class="mb-0" id="cantidadDevolver">-</h4>
                        </div>
                    </div>
                </div>
                
                {{-- Stock después de devolución --}}
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-boxes fa-2x mr-3"></i>
                        <div>
                            <h6 class="mb-1">Stock después de la devolución</h6>
                            <h4 class="mb-0" id="nuevoStock">-</h4>
                        </div>
                    </div>
                </div>
                
                {{-- Impacto en la reserva --}}
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Impacto:</strong> El saldo de la reserva se reducirá en 
                    <strong id="impactoSaldo">S/ 0.00</strong>
                </div>
                
                {{-- Motivo de eliminación --}}
                <div class="form-group">
                    <label class="font-weight-bold">
                        <i class="fas fa-comment-dots mr-2"></i> Motivo de Eliminación
                        <span class="text-danger">*</span>
                    </label>
                    <textarea id="motivoEliminacion" 
                              class="form-control form-control-lg" 
                              rows="3" 
                              placeholder="Explique detalladamente por qué se elimina este consumo (mínimo 10 caracteres)..." 
                              required></textarea>
                    <small class="text-muted">
                        <span id="contadorCaracteres">0</span>/500 caracteres - mínimo: 10
                    </small>
                </div>
                
                <input type="hidden" id="consumoIdEliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger btn-lg" id="btnConfirmarEliminar" disabled>
                    <i class="fas fa-trash-alt mr-2"></i> Confirmar Eliminación
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // ============================================
    // MODAL EXTENDER ESTANCIA
    // ============================================
    $('#modalExtenderEstadia').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const checkoutActual = button.data('checkout');
        const modal = $(this);
        
        // Actualizar información en el modal
        modal.find('#infoCheckOutActual').text(checkoutActual);
        modal.find('#nuevaFechaCheckOut').val(checkoutActual);
        
        // Establecer fecha mínima (la fecha actual de checkout)
        modal.find('#nuevaFechaCheckOut').attr('min', checkoutActual);
        
        // Usar la URL correcta para actualizar la reserva
        const url = '{{ route("reservas.update", $reserva->IdReserva) }}';
        modal.find('#formExtenderEstadia').attr('action', url);
    });
    
    // Envío del formulario de extensión
    $('#formExtenderEstadia').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const formData = new FormData(this);
        
        Swal.fire({
            title: '¿Confirmar extensión?',
            text: 'Se actualizará la fecha de check-out y se recalculará el saldo pendiente.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, extender estancia',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ffc107'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modalExtenderEstadia').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Estancia Extendida!',
                            text: 'La fecha de check-out ha sido actualizada correctamente.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 2000);
                    },
                    error: function(xhr) {
                        let message = 'Error al extender la estancia';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }
        });
    });
    
    // ============================================
    // BOTÓN REGISTRAR ABONO
    // ============================================
    $('#btnRegistrarAbono').on('click', function() {
        const monto = parseFloat($('#montoAbono').val());
        
        if (!monto || monto <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Ingrese un monto válido mayor a 0'
            });
            return;
        }
        
        Swal.fire({
            title: 'Confirmar Abono',
            text: `¿Registrar abono de S/ ${monto.toFixed(2)}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, registrar abono',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#198754'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("reservas.update", $reserva->IdReserva) }}',
                    method: 'POST',
                    data: {
                        _method: 'PUT',
                        PagosAdelantados: monto,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Abono Registrado!',
                            text: 'El abono se ha registrado correctamente.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        let message = 'Error al registrar el abono';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }
        });
    });
    
    // ============================================
    // BOTÓN PROCESAR CHECKOUT
    // ============================================
    $('#btnProcesarCheckout').on('click', function() {
        window.location.href = '{{ route("reservas.liquidacion", $reserva->IdReserva) }}';
    });
    
    // ============================================
    // ABRIR MODAL DE ELIMINACIÓN DE CONSUMO
    // ============================================
    $('.btn-eliminar-consumo').on('click', function() {
        const btn = $(this);
        
        // Obtener datos del consumo
        const consumoId = btn.data('id');
        const producto = btn.data('producto');
        const cantidad = btn.data('cantidad');
        const precio = parseFloat(btn.data('precio'));
        const subtotal = btn.data('subtotal');
        const stockActual = parseInt(btn.data('stock'));
        const fecha = btn.data('fecha');
        
        // Calcular nuevo stock
        const nuevoStock = stockActual + cantidad;
        
        // Calcular impacto en saldo
        const impacto = cantidad * precio;
        
        // Llenar el modal
        $('#consumoIdEliminar').val(consumoId);
        $('#infoProductoNombre').text(producto);
        $('#infoFechaConsumo').text(fecha);
        $('#infoCantidad').text(cantidad + ' unidad(es)');
        $('#infoPrecioUnitario').text('S/ ' + precio.toFixed(2));
        $('#infoSubtotal').text('S/ ' + subtotal);
        $('#infoStockActual').text(stockActual + ' unidad(es)');
        $('#cantidadDevolver').text(cantidad + ' unidad(es)');
        $('#nuevoStock').text(nuevoStock + ' unidad(es)');
        $('#impactoSaldo').text('S/ ' + impacto.toLocaleString('es-PE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        
        // Limpiar motivo
        $('#motivoEliminacion').val('');
        $('#contadorCaracteres').text('0');
        $('#btnConfirmarEliminar').prop('disabled', true);
        
        // Mostrar modal
        $('#modalEliminarConsumo').modal('show');
    });
    
    // ============================================
    // VALIDAR MOTIVO EN TIEMPO REAL
    // ============================================
    $('#motivoEliminacion').on('input', function() {
        const texto = $(this).val();
        const longitud = texto.length;
        
        $('#contadorCaracteres').text(longitud);
        
        // Validar longitud mínima
        if (longitud >= 10 && longitud <= 500) {
            $('#btnConfirmarEliminar').prop('disabled', false);
            $('#contadorCaracteres').removeClass('text-danger').addClass('text-success');
        } else {
            $('#btnConfirmarEliminar').prop('disabled', true);
            if (longitud < 10) {
                $('#contadorCaracteres').addClass('text-danger').removeClass('text-success');
            }
        }
    });
    
    // ============================================
    // CONFIRMAR ELIMINACIÓN
    // ============================================
    $('#btnConfirmarEliminar').on('click', function() {
        const consumoId = $('#consumoIdEliminar').val();
        const motivo = $('#motivoEliminacion').val().trim();
        
        if (!motivo || motivo.length < 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Motivo requerido',
                text: 'Debe ingresar un motivo de al menos 10 caracteres.'
            });
            return;
        }
        
        // Confirmación final
        Swal.fire({
            title: '¿Confirmar eliminación?',
            html: `
                <div class="text-left">
                    <p>Está a punto de eliminar un consumo. Esta acción:</p>
                    <ul>
                        <li>Devolverá <strong>${$('#cantidadDevolver').text()}</strong> al inventario</li>
                        <li>Reducirá el saldo en <strong>${$('#impactoSaldo').text()}</strong></li>
                        <li>Registrará un movimiento de devolución</li>
                    </ul>
                    <p class="text-danger mb-0"><strong>Esta acción no se puede deshacer.</strong></p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar consumo',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Eliminando consumo...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '/consumos/' + consumoId + '/eliminar',
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        motivo: motivo,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modalEliminarConsumo').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: '¡Consumo Eliminado!',
                            html: `
                                <div class="text-left">
                                    <p>${response.message}</p>
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-box mr-2"></i>
                                        <strong>Nuevo stock de ${response.producto}:</strong> 
                                        ${response.nuevo_stock} unidad(es)
                                    </div>
                                </div>
                            `,
                            timer: 3500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Error al eliminar el consumo';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }
        });
    });
    
    // ============================================
    // LIMPIAR MODAL AL CERRAR
    // ============================================
    $('#modalEliminarConsumo').on('hidden.bs.modal', function() {
        $('#motivoEliminacion').val('');
        $('#contadorCaracteres').text('0');
        $('#btnConfirmarEliminar').prop('disabled', true);
    });
    
});
</script>
@endpush