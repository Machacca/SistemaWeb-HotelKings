@extends('layouts.app')

@section('content')
<style>
    /* Estética de Detalles (Lectura) */
    .info-label {
        color: #888;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 1.1rem;
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
    }

    /* TARJETA DE HABITACIÓN: Versión Azul Ejecutivo */
    .card-resumen-detalle {
        background: #0f172a; /* Azul muy oscuro (Slate 900) */
        color: #f1f1f1;
        border-radius: 25px;
        border: 1px solid #1e293b;
        overflow: hidden;
    }
    .resumen-header-detalle {
        background: linear-gradient(180deg, rgba(56, 189, 248, 0.1) 0%, rgba(15, 23, 42, 0) 100%);
        padding: 40px 20px;
        text-align: center;
    }
    .numero-habitacion-detalle {
        font-size: 5.5rem;
        font-weight: 800;
        line-height: 1;
        color: #fff;
        text-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .status-badge {
        background: #38bdf8; /* Azul brillante */
        color: #0f172a;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.7rem;
        letter-spacing: 1px;
    }

    /* Listado de Consumos */
    .table-consumos {
        background: rgba(255,255,255,0.03);
        border-radius: 15px;
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('reservas.index') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-chevron-left mr-1"></i> VOLVER AL LISTADO
            </a>
            <h2 style="font-family: 'Playfair Display', serif;" class="font-weight-bold mt-2">Expediente de Reserva</h2>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-dark rounded-pill px-4 mr-2"><i class="fas fa-print mr-2"></i>Imprimir Ticket</button>
            <button class="btn btn-dark rounded-pill px-4">Modificar Datos</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">
                    
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h6 class="gold-text font-weight-bold mb-4 text-uppercase small">Datos del Huésped Principal</h6>
                            <div class="info-label">Nombre Completo</div>
                            <div class="info-value">{{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</div>
                            
                            <div class="info-label">Documento de Identidad</div>
                            <div class="info-value">{{ $reserva->huesped->TipoDocumento }}: {{ $reserva->huesped->NroDocumento }}</div>
                            
                            <div class="info-label">Contacto</div>
                            <div class="info-value">{{ $reserva->huesped->Email ?? 'Sin email' }} <br> {{ $reserva->huesped->Telefono }}</div>
                        </div>

                        <div class="col-md-6 pl-md-4">
                            <h6 class="gold-text font-weight-bold mb-4 text-uppercase small">Detalles de la Operación</h6>
                            <div class="info-label">Canal de Reserva</div>
                            <div class="info-value"><span class="badge badge-light p-2">{{ $reserva->canal->Nombre ?? 'Recepción' }}</span></div>
                            
                            <div class="info-label">Fecha de Registro</div>
                            <div class="info-value">{{ $reserva->created_at->format('d/m/Y h:i A') }}</div>

                            <div class="info-label">Estado Actual</div>
                            <div class="info-value">
                                @if($reserva->Estado == 'Confirmada')
                                    <span class="text-success"><i class="fas fa-check-circle mr-1"></i> Confirmada</span>
                                @else
                                    <span class="text-warning"><i class="fas fa-clock mr-1"></i> Pendiente</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($reserva->detalle->acompanantes->count() > 0)
                    <div class="border-top mt-4 pt-4">
                        <h6 class="gold-text font-weight-bold mb-3 text-uppercase small">Acompañantes Registrados</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>Nombre</th>
                                        <th>Documento</th>
                                        <th>Parentesco</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reserva->detalle->acompanantes as $acomp)
                                    <tr>
                                        <td class="font-weight-bold">{{ $acomp->Nombre }} {{ $acomp->Apellido }}</td>
                                        <td>{{ $acomp->NroDocumento }}</td>
                                        <td><span class="badge badge-light px-2">{{ $acomp->Parentesco }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h6 class="text-success font-weight-bold mb-4 text-uppercase small"><i class="fas fa-utensils mr-2"></i> Consumos y Cargos Extra</h6>
                    <table class="table align-middle">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reserva->consumos as $consumo)
                            <tr>
                                <td>{{ $consumo->producto->Nombre }}</td>
                                <td class="text-center">{{ $consumo->Cantidad }}</td>
                                <td class="text-right">S/ {{ number_format($consumo->producto->PrecioVenta, 2) }}</td>
                                <td class="text-right font-weight-bold">S/ {{ number_format($consumo->Cantidad * $consumo->producto->PrecioVenta, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No se registran consumos adicionales.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-resumen-detalle shadow-lg sticky-top" style="top: 20px;">
                <div class="resumen-header-detalle">
                    <span class="status-badge mb-3">RESERVA ACTIVA</span>
                    <div class="numero-habitacion-detalle">{{ $reserva->detalle->habitacion->Numero }}</div>
                    <div class="text-info font-weight-bold small" style="letter-spacing: 2px;">
                        {{ strtoupper($reserva->detalle->habitacion->tipo->Nombre) }}
                    </div>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="bg-white text-dark rounded-lg p-3 mb-4 shadow-sm">
                        <div class="row text-center">
                            <div class="col-6 border-right">
                                <small class="text-muted d-block text-uppercase" style="font-size: 0.6rem;">Ingreso</small>
                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($reserva->detalle->FechaCheckIn)->format('d M') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block text-uppercase" style="font-size: 0.6rem;">Salida</small>
                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($reserva->detalle->FechaCheckOut)->format('d M') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-2">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Costo por Noche</span>
                            <span class="font-weight-bold text-white">S/ {{ number_format($reserva->detalle->PrecioNoche, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Noches de Estancia</span>
                            <span class="font-weight-bold text-white">{{ $noches }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Cargos por Productos</span>
                            <span class="font-weight-bold text-white">S/ {{ number_format($totalConsumos, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span class="small">Descuento Aplicado</span>
                            <span class="font-weight-bold">- S/ {{ number_format($reserva->Descuento ?? 0, 2) }}</span>
                        </div>

                        <div class="border-top border-secondary pt-4 mt-4">
                            <div class="d-flex justify-content-between align-items-end">
                                <span class="text-info font-weight-bold h6 mb-2">TOTAL FINAL</span>
                                <h2 class="font-weight-bold mb-0 text-white">S/ {{ number_format($reserva->TotalReserva, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn btn-info btn-block mt-4 py-3 font-weight-bold rounded-pill shadow">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> GENERAR COMPROBANTE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection