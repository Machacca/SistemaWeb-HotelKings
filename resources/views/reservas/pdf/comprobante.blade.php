<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante {{ $comprobante->Serie }}-{{ $comprobante->Numero }}</title>
    <style>
        @page {
            size: a4;
            margin: 10mm;
        }
        
        body {
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
            border: 1px dashed #ccc;
        }
        
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 3mm;
            margin-bottom: 3mm;
        }
        
        .header h3 {
            margin: 0 0 2px 0;
            font-size: 13px;
            font-weight: bold;
        }
        
        .header h4 {
            margin: 2px 0;
            font-size: 11px;
        }
        
        .header p {
            margin: 1px 0;
            font-size: 9px;
        }
        
        .info {
            margin: 3mm 0;
        }
        
        .info p {
            margin: 2px 0;
            font-size: 10px;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 10px;
            margin: 2mm 0 1mm 0;
        }
        
        table.item-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table.item-table td {
            padding: 2px 0;
            font-size: 10px;
            vertical-align: top;
        }
        
        table.item-table td:last-child {
            text-align: right;
            white-space: nowrap;
        }
        
        .total-box {
            background: #000;
            color: #fff;
            padding: 3mm;
            text-align: center;
            margin: 3mm 0;
        }
        
        .total-box small {
            font-size: 8px;
        }
        
        .total-box h2 {
            margin: 1px 0 0 0;
            font-size: 16px;
        }
        
        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 3mm;
            margin-top: 3mm;
            font-size: 9px;
        }
        
        .footer p {
            margin: 1px 0;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $hotel = $comprobante->reserva->detalles->first()->habitacion->hotel ?? null;
        $totalHospedaje = $comprobante->reserva->total_hospedaje;
        $totalConsumos = $comprobante->reserva->total_consumos;
        $subtotal = $totalHospedaje + $totalConsumos;
        $adelantos = $comprobante->reserva->detalles->sum('PagosAdelantados');
        $descuentos = $comprobante->reserva->detalles->sum('Descuento');
        $vuelto = $comprobante->Subtotal - $comprobante->Total;
    @endphp
    
    <!-- Encabezado -->
    <div class="header">
        <h3>{{ $hotel->Nombre ?? 'HOTEL' }}</h3>
        <p>{{ $hotel->Direccion ?? 'Dirección' }}</p>
        <p>Tel: {{ $hotel->Telefono ?? 'N/A' }}</p>
        <h4>{{ strtoupper($comprobante->Tipo) }} ELECTRÓNICA</h4>
        <h3>{{ $comprobante->Serie }} - {{ $comprobante->Numero }}</h3>
    </div>
    
    <!-- Información General -->
    <div class="info">
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($comprobante->FechaEmision)->format('d/m/Y H:i') }}</p>
        <p><strong>Forma de Pago:</strong> {{ $comprobante->formaPago->Nombre ?? 'N/A' }}</p>
        
        @if($comprobante->Tipo === 'Factura')
            <p><strong>Razón Social:</strong> {{ $comprobante->reserva->huesped->Nombre }} {{ $comprobante->reserva->huesped->Apellido }}</p>
            <p><strong>RUC:</strong> {{ $comprobante->reserva->huesped->NroDocumento }}</p>
        @else
            <p><strong>Cliente:</strong> {{ $comprobante->reserva->huesped->Nombre }} {{ $comprobante->reserva->huesped->Apellido }}</p>
            <p><strong>{{ $comprobante->reserva->huesped->TipoDocumento }}:</strong> {{ $comprobante->reserva->huesped->NroDocumento }}</p>
        @endif
        
        <p><strong>Reserva #:</strong> {{ $comprobante->IdReserva }}</p>
    </div>
    
    <div class="divider"></div>
    
    <!-- HOSPEDAJE -->
    <div class="section-title">HOSPEDAJE</div>
    @foreach($comprobante->reserva->detalles as $detalle)
        @php
            $noches = \Carbon\Carbon::parse($detalle->FechaCheckIn)->diffInDays(\Carbon\Carbon::parse($detalle->FechaCheckOut)) ?: 1;
            $totalHabitacion = $detalle->PrecioNoche * $noches;
        @endphp
        <table class="item-table">
            <tr>
                <td>Hab. {{ $detalle->habitacion->Numero }} ({{ $noches }} noches)</td>
                <td>S/ {{ number_format($totalHabitacion, 2) }}</td>
            </tr>
        </table>
    @endforeach
    
    <!-- CONSUMOS (PRODUCTOS) -->
    @if($comprobante->reserva->consumos->count() > 0)
        <div class="section-title">PRODUCTOS CONSUMIDOS</div>
        @foreach($comprobante->reserva->consumos as $consumo)
            @php
                $subtotalConsumo = $consumo->Cantidad * $consumo->PrecioVenta;
            @endphp
            <table class="item-table">
                <tr>
                    <td>{{ $consumo->producto->Nombre }} x{{ $consumo->Cantidad }}</td>
                    <td>S/ {{ number_format($subtotalConsumo, 2) }}</td>
                </tr>
            </table>
        @endforeach
    @endif
    
    <div class="divider"></div>
    
    <!-- TOTALES -->
    <table class="item-table">
        <tr>
            <td>Subtotal:</td>
            <td>S/ {{ number_format($subtotal, 2) }}</td>
        </tr>
        
        {{-- IGV OCULTO POR AHORA --}}
        {{-- 
        <tr>
            <td>IGV (18%):</td>
            <td>S/ {{ number_format($comprobante->IGV, 2) }}</td>
        </tr>
        --}}
        
        @if($adelantos > 0)
        <tr>
            <td>Adelantos:</td>
            <td>- S/ {{ number_format($adelantos, 2) }}</td>
        </tr>
        @endif
        
        @if($descuentos > 0)
        <tr>
            <td>Descuentos:</td>
            <td>- S/ {{ number_format($descuentos, 2) }}</td>
        </tr>
        @endif
    </table>
    
    <div class="total-box">
        <small>TOTAL PAGADO</small>
        <h2>S/ {{ number_format($comprobante->Total, 2) }}</h2>
    </div>
    
    @if($vuelto > 0)
    <table class="item-table">
        <tr>
            <td>Monto Recibido:</td>
            <td>S/ {{ number_format($comprobante->Subtotal, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Vuelto:</strong></td>
            <td><strong>S/ {{ number_format($vuelto, 2) }}</strong></td>
        </tr>
    </table>
    @endif
    
    <!-- Pie -->
    <div class="footer">
        <p>Atendido por: {{ $comprobante->usuario->name ?? 'Sistema' }}</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        <p>¡Gracias por su visita!</p>
        <p><small>Comprobante electrónico</small></p>
    </div>
</body>
</html>