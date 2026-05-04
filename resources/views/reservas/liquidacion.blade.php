@extends('layouts.app')

@section('title', 'Liquidación de Reserva')

@section('content')
<style>
    .card-luxury {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .card-header-luxury {
        background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 20px 25px;
    }
    .total-box {
        background: linear-gradient(135deg, #198754, #157347);
        color: white;
        border-radius: 20px;
        padding: 25px;
        text-align: center;
    }
    .detail-row {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .badge-luxury {
        font-size: 0.85rem;
        padding: 8px 15px;
        border-radius: 50px;
    }
    .btn-checkout {
        background: linear-gradient(135deg, #198754, #157347);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(25, 135, 84, 0.4);
        color: white;
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .table-luxury th {
        background: #1a1a1a;
        color: #c9a45c;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a;">
                <i class="fas fa-cash-register mr-2 text-success"></i>Liquidación de Cuenta
            </h1>
            <p class="text-muted mb-0">
                Reserva #{{ $reserva->IdReserva }} | 
                Huésped: <strong>{{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</strong>
            </p>
        </div>
        <div>
            <a href="{{ route('reservas.edit', $reserva->IdReserva) }}" class="btn btn-dark rounded-pill px-4">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Gestión
            </a>
        </div>
    </div>

    <form action="{{ route('reservas.procesarCheckout', $reserva->IdReserva) }}" method="POST" id="formCheckout">
        @csrf
        
        <div class="row">
            {{-- Columna izquierda: Detalles de la cuenta --}}
            <div class="col-lg-8">
                {{-- Hospitalidad --}}
                <div class="card card-luxury mb-4">
                    <div class="card-header-luxury">
                        <h5 class="mb-0"><i class="fas fa-bed mr-2 text-warning"></i> Detalle de Hospedaje</h5>
                    </div>
                    <div class="card-body">
                        @foreach($reserva->detalles as $detalle)
                        <div class="info-card">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Habitación</small>
                                    <h5>{{ $detalle->habitacion->Numero }} - {{ $detalle->habitacion->tipo->Nombre ?? 'N/A' }}</h5>
                                    <small class="text-muted">Hotel: {{ $detalle->habitacion->hotel->Nombre ?? 'N/A' }}</small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">Período</small>
                                    <p class="mb-1">
                                        {{ \Carbon\Carbon::parse($detalle->FechaCheckIn)->format('d/m/Y') }} 
                                        <i class="fas fa-arrow-right mx-1"></i> 
                                        {{ \Carbon\Carbon::parse($detalle->FechaCheckOut)->format('d/m/Y') }}
                                    </p>
                                    @php
                                        $noches = \Carbon\Carbon::parse($detalle->FechaCheckIn)->diffInDays(\Carbon\Carbon::parse($detalle->FechaCheckOut)) ?: 1;
                                    @endphp
                                    <small>{{ $noches }} noche(s) x S/ {{ number_format($detalle->PrecioNoche, 2) }}</small>
                                    <h4 class="text-success mt-2">S/ {{ number_format($detalle->PrecioNoche * $noches, 2) }}</h4>
                                </div>
                            </div>
                            @if($detalle->acompanantes->count() > 0)
                            <div class="mt-3">
                                <small class="text-muted">Acompañantes:</small>
                                @foreach($detalle->acompanantes as $acomp)
                                    <span class="badge badge-light mr-1">{{ $acomp->Nombre }} {{ $acomp->Apellido }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Consumos --}}
                <div class="card card-luxury mb-4">
                    <div class="card-header-luxury">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart mr-2 text-warning"></i> Consumos y Servicios</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($reserva->consumos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-luxury">
                                        <tr>
                                            <th>Producto/Servicio</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-right">Precio Unit.</th>
                                            <th class="text-right">Subtotal</th>
                                            <th class="text-center">Estado</th>
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
                                            <td class="text-center">{{ $consumo->Cantidad }}</td>
                                            <td class="text-right">S/ {{ number_format($consumo->PrecioVenta, 2) }}</td>
                                            <td class="text-right font-weight-bold">S/ {{ number_format($consumo->Cantidad * $consumo->PrecioVenta, 2) }}</td>
                                            <td class="text-center">
                                                @if($consumo->EstadoPago)
                                                    <span class="badge badge-success">Pagado</span>
                                                @else
                                                    <span class="badge badge-warning">Pendiente</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">No hay consumos registrados</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Información del Huésped --}}
                <div class="card card-luxury mb-4">
                    <div class="card-header-luxury">
                        <h5 class="mb-0"><i class="fas fa-user mr-2 text-warning"></i> Datos del Huésped</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nombre:</strong> {{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</p>
                                <p class="mb-1"><strong>Documento:</strong> {{ $reserva->huesped->TipoDocumento }}: {{ $reserva->huesped->NroDocumento }}</p>
                                <p class="mb-0"><strong>Email:</strong> {{ $reserva->huesped->Email ?? 'No registrado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Teléfono:</strong> {{ $reserva->huesped->Telefono ?? 'No registrado' }}</p>
                                <p class="mb-1"><strong>Nacionalidad:</strong> {{ $reserva->huesped->Nacionalidad ?? 'No registrada' }}</p>
                                <p class="mb-0"><strong>Canal:</strong> {{ $reserva->canal->Nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna derecha: Resumen y Pago --}}
            <div class="col-lg-4">
                {{-- Resumen de Cuenta --}}
                <div class="card card-luxury mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header-luxury">
                        <h5 class="mb-0"><i class="fas fa-calculator mr-2 text-warning"></i> Resumen de Cuenta</h5>
                    </div>
                    <div class="card-body">
                        <div class="detail-row d-flex justify-content-between">
                            <span>Hospedaje</span>
                            <strong>S/ {{ number_format($totalHospedaje, 2) }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span>Consumos</span>
                            <strong>S/ {{ number_format($totalConsumos, 2) }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>S/ {{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span>IGV (18%)</span>
                            <strong>S/ {{ number_format($igv, 2) }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between text-danger">
                            <span>Adelantos</span>
                            <strong>- S/ {{ number_format($adelantos, 2) }}</strong>
                        </div>
                        @if($descuentos > 0)
                        <div class="detail-row d-flex justify-content-between text-danger">
                            <span>Descuentos</span>
                            <strong>- S/ {{ number_format($descuentos, 2) }}</strong>
                        </div>
                        @endif
                        <hr>
                        <div class="total-box mb-4">
                            <h4 class="mb-1">TOTAL A PAGAR</h4>
                            <h2 class="mb-0" id="totalPagar">S/ {{ number_format($totalPagar, 2) }}</h2>
                        </div>

                        {{-- Datos del Comprobante --}}
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-muted mb-3">Datos del Comprobante</h6>
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Tipo de Comprobante *</label>
                                    <select name="TipoComprobante" id="TipoComprobante" class="form-control form-control-lg" required>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                    </select>
                                </div>

                                <div class="form-group" id="grupoRazonSocial" style="display: none;">
                                    <label class="font-weight-bold">Razón Social</label>
                                    <input type="text" name="RazonSocial" class="form-control" placeholder="Nombre o Razón Social">
                                </div>

                                <div class="form-group" id="grupoNumeroDocumento" style="display: none;">
                                    <label class="font-weight-bold">RUC</label>
                                    <input type="text" name="NumeroDocumento" class="form-control" placeholder="Número de RUC">
                                </div>

                                <div class="form-group" id="grupoDireccion" style="display: none;">
                                    <label class="font-weight-bold">Dirección</label>
                                    <input type="text" name="Direccion" class="form-control" placeholder="Dirección fiscal">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Forma de Pago *</label>
                                    <select name="IdFormaPago" id="IdFormaPago" class="form-control form-control-lg" required>
                                        <option value="">Seleccione forma de pago...</option>
                                        @foreach($formasPago as $fp)
                                            <option value="{{ $fp->IdFormaPago }}">{{ $fp->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Monto Recibido (S/) *</label>
                                    <input type="number" 
                                           name="MontoRecibido" 
                                           id="MontoRecibido" 
                                           class="form-control form-control-lg" 
                                           value="{{ $totalPagar }}" 
                                           step="0.01" 
                                           min="0" 
                                           required>
                                    <small class="text-muted">Ingrese el monto que entrega el cliente</small>
                                </div>

                                <div id="infoVuelto" class="alert alert-info mb-3" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Vuelto:</span>
                                        <h4 class="mb-0" id="montoVuelto">S/ 0.00</h4>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Comentario</label>
                                    <textarea name="Comentario" class="form-control" rows="2" 
                                              placeholder="Observaciones adicionales..."></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-checkout btn-block btn-lg">
                            <i class="fas fa-check-circle mr-2"></i> REGISTRAR SALIDA
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('reservas.edit', $reserva->IdReserva) }}" class="text-muted">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    const totalPagar = {{ $totalPagar }};
    
    // ============================================
    // MOSTRAR/OCULTAR CAMPOS SEGÚN TIPO DE COMPROBANTE
    // ============================================
    $('#TipoComprobante').on('change', function() {
        const tipo = $(this).val();
        
        if (tipo === 'Factura') {
            $('#grupoRazonSocial').slideDown();
            $('#grupoNumeroDocumento').slideDown();
            $('#grupoDireccion').slideDown();
            
            // Hacer campos requeridos
            $('input[name="RazonSocial"]').attr('required', true);
            $('input[name="NumeroDocumento"]').attr('required', true);
        } else {
            $('#grupoRazonSocial').slideUp();
            $('#grupoNumeroDocumento').slideUp();
            $('#grupoDireccion').slideUp();
            
            // Quitar requerido
            $('input[name="RazonSocial"]').removeAttr('required');
            $('input[name="NumeroDocumento"]').removeAttr('required');
        }
    });
    
    // ============================================
    // CALCULAR VUELTO EN TIEMPO REAL
    // ============================================
    $('#MontoRecibido').on('input', function() {
        const montoRecibido = parseFloat($(this).val()) || 0;
        const vuelto = montoRecibido - totalPagar;
        
        if (montoRecibido > 0 && vuelto >= 0) {
            $('#infoVuelto').slideDown();
            $('#montoVuelto').text('S/ ' + vuelto.toLocaleString('es-PE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            
            if (vuelto > 0) {
                $('#infoVuelto').removeClass('alert-info').addClass('alert-warning');
            } else {
                $('#infoVuelto').removeClass('alert-warning').addClass('alert-info');
            }
        } else if (montoRecibido > 0 && vuelto < 0) {
            $('#infoVuelto').slideDown();
            $('#infoVuelto').removeClass('alert-info alert-warning').addClass('alert-danger');
            $('#montoVuelto').text('Falta S/ ' + Math.abs(vuelto).toLocaleString('es-PE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        } else {
            $('#infoVuelto').slideUp();
        }
    });
    
    // ============================================
    // VALIDAR ANTES DE ENVIAR
    // ============================================
    $('#formCheckout').on('submit', function(e) {
        const montoRecibido = parseFloat($('#MontoRecibido').val()) || 0;
        
        if (montoRecibido < totalPagar) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Monto Insuficiente',
                text: 'El monto recibido es menor al total a pagar.',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }
        
        // Confirmación final
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: '¿Confirmar Checkout?',
            html: `
                <div class="text-left">
                    <p><strong>Total a pagar:</strong> S/ ${totalPagar.toFixed(2)}</p>
                    <p><strong>Monto recibido:</strong> S/ ${montoRecibido.toFixed(2)}</p>
                    <p><strong>Vuelto:</strong> S/ ${(montoRecibido - totalPagar).toFixed(2)}</p>
                    <hr>
                    <p class="mb-0 text-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Esta acción finalizará la reserva y no se puede deshacer.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, registrar salida',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
    // Calcular vuelto inicial
    $('#MontoRecibido').trigger('input');
    
});
</script>
@endpush