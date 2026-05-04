<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Caja - {{ date('d/m/Y') }}</title>
    <style>
        @page {
            margin: 10mm;
            size: a4 portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #1a1a1a;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
            margin: 2px 0;
        }
        
        .header .periodo {
            font-size: 14px;
            font-weight: bold;
            color: #c9a45c;
            margin-top: 8px;
        }
        
        .resumen {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }
        
        .resumen-box {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        
        .resumen-box h4 {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 5px;
        }
        
        .resumen-box h2 {
            font-size: 18px;
        }
        
        .resumen-box.ingresos h2 {
            color: #198754;
        }
        
        .resumen-box.egresos h2 {
            color: #dc3545;
        }
        
        .resumen-box.balance {
            background: #1a1a1a;
            color: #c9a45c;
        }
        
        .resumen-box.balance h4 {
            color: #c9a45c;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
            color: #1a1a1a;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead th {
            background: #1a1a1a;
            color: #c9a45c;
            padding: 8px 6px;
            font-size: 10px;
            text-transform: uppercase;
            text-align: left;
        }
        
        table tbody td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        
        table tfoot td {
            padding: 8px 6px;
            font-weight: bold;
            border-top: 2px solid #1a1a1a;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .monto-positivo {
            color: #198754;
        }
        
        .monto-negativo {
            color: #dc3545;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-ingreso {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-egreso {
            background: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
        }
        
        .firma-linea {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 10px;
        }
        
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    
    {{-- Botón imprimir (no se ve al imprimir) --}}
    <div class="no-print" style="text-align: right; margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1a1a1a; color: #c9a45c; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            🖨️ Imprimir Reporte
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            ✖ Cerrar
        </button>
    </div>
    
    {{-- Encabezado --}}
    <div class="header">
        <h1>{{ $hotel->Nombre ?? config('app.name', 'HOTEL') }}</h1>
        <p>{{ $hotel->Direccion ?? '' }}</p>
        <p>Tel: {{ $hotel->Telefono ?? '' }}</p>
        <div class="periodo">
            REPORTE DE CAJA Y FACTURACIÓN
        </div>
        <p>
            Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} 
            al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
        </p>
        <p>Generado: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    
    {{-- Resumen --}}
    <div class="resumen">
        <div class="resumen-box ingresos">
            <h4>Total Ingresos</h4>
            <h2>S/ {{ number_format($totalIngresos, 2) }}</h2>
        </div>
        <div class="resumen-box egresos">
            <h4>Total Egresos</h4>
            <h2>S/ {{ number_format($totalEgresos, 2) }}</h2>
        </div>
        <div class="resumen-box balance">
            <h4>Balance Neto</h4>
            <h2>S/ {{ number_format($balance, 2) }}</h2>
        </div>
    </div>
    
    {{-- Ingresos por Hospedaje --}}
    <div class="section-title">📋 INGRESOS POR HOSPEDAJE Y CONSUMOS</div>
    <table>
        <thead>
            <tr>
                <th>Comprobante</th>
                <th>Habitación</th>
                <th>Huésped</th>
                <th>Forma Pago</th>
                <th class="text-right">Total</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ingresosHospedaje as $comp)
            <tr>
                <td><strong>{{ $comp->Serie }}-{{ $comp->Numero }}</strong> ({{ $comp->Tipo }})</td>
                <td>{{ $comp->reserva->detalles->first()->habitacion->Numero ?? 'N/A' }}</td>
                <td>{{ $comp->reserva->huesped->Nombre ?? 'N/A' }} {{ $comp->reserva->huesped->Apellido ?? '' }}</td>
                <td>{{ $comp->formaPago->Nombre ?? 'N/A' }}</td>
                <td class="text-right monto-positivo">S/ {{ number_format($comp->Total, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($comp->FechaEmision)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay ingresos registrados en este período</td>
            </tr>
            @endforelse
        </tbody>
        @if($ingresosHospedaje->count() > 0)
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">Total Ingresos por Hospedaje:</td>
                <td class="text-right monto-positivo">S/ {{ number_format($ingresosHospedaje->sum('Total'), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
    
    {{-- Movimientos de Caja --}}
    <div class="section-title">💰 MOVIMIENTOS DE CAJA</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Concepto</th>
                <th>Tipo</th>
                <th class="text-right">Monto</th>
                <th>Referencia</th>
                <th>Fecha</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
            <tr>
                <td>#{{ $mov->IdMovimiento }}</td>
                <td>{{ $mov->concepto }}</td>
                <td>
                    <span class="badge {{ $mov->tipo === 'ingreso' ? 'badge-ingreso' : 'badge-egreso' }}">
                        {{ $mov->tipo === 'ingreso' ? 'INGRESO' : 'EGRESO' }}
                    </span>
                </td>
                <td class="text-right {{ $mov->tipo === 'ingreso' ? 'monto-positivo' : 'monto-negativo' }}">
                    {{ $mov->tipo === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->monto, 2) }}
                </td>
                <td>{{ $mov->referencia ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y') }}</td>
                <td>{{ $mov->usuario->Nombre ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay movimientos registrados</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">Total Ingresos:</td>
                <td class="text-right monto-positivo">S/ {{ number_format($totalIngresos, 2) }}</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Total Egresos:</td>
                <td class="text-right monto-negativo">S/ {{ number_format($totalEgresos, 2) }}</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">BALANCE:</td>
                <td class="text-right" style="font-size: 14px;">
                    <strong>S/ {{ number_format($balance, 2) }}</strong>
                </td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
    
    {{-- Pie de página --}}
    <div class="footer">
        <p>Reporte generado el {{ date('d/m/Y H:i:s') }} | Usuario: {{ Auth::user()->Nombre ?? 'Sistema' }}</p>
        <p>{{ $hotel->Nombre ?? config('app.name', 'Hotel') }} - Sistema de Gestión Hotelera</p>
        
        <div style="margin-top: 30px; display: flex; justify-content: space-around;">
            <div class="firma-linea">
                _______________________<br>
                <strong>Responsable de Caja</strong>
            </div>
            <div class="firma-linea">
                _______________________<br>
                <strong>Administrador</strong>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>