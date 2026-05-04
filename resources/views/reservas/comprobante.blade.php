@extends('layouts.app')

@section('title', 'Comprobante de Pago')

@section('content')
<style>
    .ticket-container {
        max-width: 400px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    .ticket-header {
        background: #1a1a1a;
        color: white;
        padding: 20px;
        text-align: center;
        border-bottom: 3px dashed #c9a45c;
    }
    .ticket-body {
        padding: 20px;
        font-family: 'Courier New', monospace;
    }
    .ticket-footer {
        background: #f8f9fa;
        padding: 20px;
        text-align: center;
        border-top: 3px dashed #c9a45c;
    }
    .ticket-line {
        border-top: 1px dashed #ccc;
        margin: 10px 0;
    }
    .ticket-total {
        background: #198754;
        color: white;
        padding: 15px;
        border-radius: 10px;
        margin: 15px 0;
        text-align: center;
    }
    .btn-imprimir {
        background: #1a1a1a;
        color: #c9a45c;
        border: 2px solid #c9a45c;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-imprimir:hover {
        background: #c9a45c;
        color: #1a1a1a;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .ticket-container, .ticket-container * {
            visibility: visible;
        }
        .ticket-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container py-4">
    
    {{-- Botones de acción --}}
    <div class="text-center mb-4 no-print">
        <button onclick="window.print()" class="btn btn-imprimir">
            <i class="fas fa-print mr-2"></i> Imprimir
        </button>
        <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary ml-2">
            <i class="fas fa-home mr-2"></i> Ir al Inicio
        </a>
    </div>
    
    {{-- Ticket --}}
    <div class="ticket-container" id="ticket-imprimir">
        {{-- Encabezado --}}
        <div class="ticket-header">
            @php
                $hotel = $comprobante->reserva->detalles->first()->habitacion->hotel ?? null;
            @endphp
            <h4 class="mb-1 font-weight-bold">{{ $hotel->Nombre ?? config('app.name', 'HOTEL') }}</h4>
            <p class="mb-1 small">{{ $hotel->Direccion ?? 'Dirección del hotel' }}</p>
            <p class="mb-0 small">{{ $hotel->Telefono ?? 'Teléfono' }}</p>
            <div class="mt-3">
                <h5 class="mb-0 text-warning">{{ strtoupper($comprobante->Tipo) }} ELECTRÓNICA</h5>
                <h3 class="mb-0 font-weight-bold">{{ $comprobante->Serie }} - {{ $comprobante->Numero }}</h3>
            </div>
        </div>
        
        {{-- Cuerpo --}}
        <div class="ticket-body">
            {{-- Datos del comprobante --}}
            <div class="mb-3">
                <small class="text-muted">Fecha de Emisión:</small>
                <strong>{{ \Carbon\Carbon::parse($comprobante->FechaEmision)->format('d/m/Y H:i') }}</strong>
            </div>
            
            <div class="mb-3">
                <small class="text-muted">Forma de Pago:</small>
                <strong>{{ $comprobante->formaPago->Nombre ?? 'N/A' }}</strong>
            </div>
            
            <div class="ticket-line"></div>
            
            {{-- Datos del cliente --}}
            @if($comprobante->Tipo === 'Factura')
            <div class="mb-3">
                <small class="text-muted">Razón Social:</small>
                <strong>{{ $comprobante->reserva->huesped->Nombre }} {{ $comprobante->reserva->huesped->Apellido }}</strong>
            </div>
            <div class="mb-3">
                <small class="text-muted">RUC:</small>
                <strong>{{ $comprobante->reserva->huesped->NroDocumento }}</strong>
            </div>
            @else
            <div class="mb-3">
                <small class="text-muted">Cliente:</small>
                <strong>{{ $comprobante->reserva->huesped->Nombre }} {{ $comprobante->reserva->huesped->Apellido }}</strong>
            </div>
            <div class="mb-3">
                <small class="text-muted">{{ $comprobante->reserva->huesped->TipoDocumento }}:</small>
                <strong>{{ $comprobante->reserva->huesped->NroDocumento }}</strong>
            </div>
            @endif
            
            <div class="mb-3">
                <small class="text-muted">Reserva #:</small>
                <strong>{{ $comprobante->IdReserva }}</strong>
            </div>
            
            <div class="ticket-line"></div>
            
            {{-- Detalle de Hospedaje --}}
            <h6 class="text-uppercase text-muted mb-2 small font-weight-bold">Hospedaje</h6>
            @foreach($comprobante->reserva->detalles as $detalle)
                @php
                    $noches = \Carbon\Carbon::parse($detalle->FechaCheckIn)->diffInDays(\Carbon\Carbon::parse($detalle->FechaCheckOut)) ?: 1;
                    $totalHabitacion = $detalle->PrecioNoche * $noches;
                @endphp
                <div class="d-flex justify-content-between mb-1 small">
                    <span>Hab. {{ $detalle->habitacion->Numero }} ({{ $noches }} noche(s))</span>
                    <span>S/ {{ number_format($totalHabitacion, 2) }}</span>
                </div>
            @endforeach
            
            {{-- Detalle de Consumos --}}
            @if($comprobante->reserva->consumos->count() > 0)
                <h6 class="text-uppercase text-muted mb-2 mt-3 small font-weight-bold">Consumos</h6>
                @foreach($comprobante->reserva->consumos as $consumo)
                <div class="d-flex justify-content-between mb-1 small">
                    <span>{{ $consumo->producto->Nombre }} x{{ $consumo->Cantidad }}</span>
                    <span>S/ {{ number_format($consumo->Cantidad * $consumo->PrecioVenta, 2) }}</span>
                </div>
                @endforeach
            @endif
            
            <div class="ticket-line"></div>
            
            {{-- Totales --}}
            @php
                $totalHospedaje = $comprobante->reserva->total_hospedaje;
                $totalConsumos = $comprobante->reserva->total_consumos;
                $subtotal = $totalHospedaje + $totalConsumos;
                $adelantos = $comprobante->reserva->detalles->sum('PagosAdelantados');
                $descuentos = $comprobante->reserva->detalles->sum('Descuento');
                $vuelto = $comprobante->Subtotal - $comprobante->Total;
            @endphp
            
            <div class="d-flex justify-content-between mb-1">
                <span>Subtotal:</span>
                <span>S/ {{ number_format($subtotal, 2) }}</span>
            </div>
            
            {{-- IGV OCULTO POR AHORA --}}
            {{-- 
            <div class="d-flex justify-content-between mb-1">
                <span>IGV (18%):</span>
                <span>S/ {{ number_format($comprobante->IGV, 2) }}</span>
            </div>
            --}}
            
            @if($adelantos > 0)
            <div class="d-flex justify-content-between mb-1 text-danger">
                <span>Adelantos:</span>
                <span>- S/ {{ number_format($adelantos, 2) }}</span>
            </div>
            @endif
            
            @if($descuentos > 0)
            <div class="d-flex justify-content-between mb-1 text-danger">
                <span>Descuentos:</span>
                <span>- S/ {{ number_format($descuentos, 2) }}</span>
            </div>
            @endif
            
            <div class="ticket-total">
                <small>TOTAL PAGADO</small>
                <h3 class="mb-0 font-weight-bold">S/ {{ number_format($comprobante->Total, 2) }}</h3>
            </div>
            
            @if($vuelto > 0)
            <div class="d-flex justify-content-between mt-2">
                <span>Monto Recibido:</span>
                <span>S/ {{ number_format($comprobante->Subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between text-success">
                <span>Vuelto:</span>
                <strong>S/ {{ number_format($vuelto, 2) }}</strong>
            </div>
            @endif
        </div>
        
        {{-- Pie --}}
        <div class="ticket-footer">
            <p class="mb-1 small">Atendido por: {{ $comprobante->usuario->name ?? 'Sistema' }}</p>
            <p class="mb-1 small">{{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="mb-0 small text-muted">¡Gracias por su visita!</p>
            <div class="mt-2">
                <small class="text-muted">Este comprobante es generado electrónicamente</small>
            </div>
        </div>
    </div>
    
    {{-- Botones adicionales --}}
    <div class="text-center mt-4 no-print">
        <a href="{{ route('reservas.index') }}" class="btn btn-success btn-lg">
            <i class="fas fa-check mr-2"></i> Finalizar y Volver al Panel
        </a>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-imprimir al cargar (opcional)
    // setTimeout(() => window.print(), 1000);
});
</script>
@endsection