@extends('layouts.app')

@section('title', 'Caja y Facturación')

@section('content')
<style>
    .stats-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-3px);
    }
    .stats-ingresos {
        background: linear-gradient(135deg, #198754, #157347);
        color: white;
    }
    .stats-egresos {
        background: linear-gradient(135deg, #dc3545, #b02a37);
        color: white;
    }
    .stats-balance {
        background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        color: #c9a45c;
    }
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.3;
    }
    .stats-number {
        font-size: 1.8rem;
        font-weight: bold;
    }
    .filter-bar {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .table-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .table-card thead th {
        background: #1a1a1a;
        color: #c9a45c;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.78rem;
        letter-spacing: 0.5px;
        padding: 12px 10px;
    }
    .table-card tbody td {
        padding: 10px;
        vertical-align: middle;
    }
    .badge-ingreso {
        background: #d4edda;
        color: #155724;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
    }
    .badge-egreso {
        background: #f8d7da;
        color: #721c24;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
    }
    .monto-positivo {
        color: #198754;
        font-weight: bold;
    }
    .monto-negativo {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700;">
                <i class="fas fa-cash-register mr-2 text-warning"></i>Caja y Facturación
            </h1>
            <p class="text-muted mb-0">Control de ingresos y egresos | {{ date('d/m/Y') }}</p>
        </div>
        <div>
            <a href="{{ route('caja.imprimir', request()->query()) }}" 
            class="btn btn-outline-dark rounded-pill px-4 mr-2" 
            target="_blank">
                <i class="fas fa-print mr-2"></i> Imprimir Reporte
            </a>
            <button class="btn btn-warning btn-lg rounded-pill px-4 shadow" 
                    data-toggle="modal" 
                    data-target="#modalNuevoMovimiento">
                <i class="fas fa-plus-circle mr-2"></i> Registrar Movimiento
            </button>
        </div>
    </div>
    
    {{-- Filtros --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('caja.index') }}" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="small text-muted font-weight-bold">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-3 mb-2">
                <label class="small text-muted font-weight-bold">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-3 mb-2">
                <label class="small text-muted font-weight-bold">Usuario</label>
                <select name="usuario_id" class="form-control">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $usr)
                        <option value="{{ $usr->IdUsuario }}" {{ $usuarioId == $usr->IdUsuario ? 'selected' : '' }}>
                            {{ $usr->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-dark btn-block">
                    <i class="fas fa-filter mr-1"></i> Aplicar Filtros
                </button>
            </div>
        </form>
    </div>
    
    {{-- Cards de Resumen --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stats-card stats-ingresos p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="opacity:0.8;">Ingresos Totales</h6>
                        <h2 class="mb-0 stats-number">S/ {{ number_format($totalIngresos, 2) }}</h2>
                    </div>
                    <i class="fas fa-arrow-up stats-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card stats-egresos p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="opacity:0.8;">Egresos Totales</h6>
                        <h2 class="mb-0 stats-number">S/ {{ number_format($totalEgresos, 2) }}</h2>
                    </div>
                    <i class="fas fa-arrow-down stats-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card stats-balance p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="opacity:0.8;">Balance Neto</h6>
                        <h2 class="mb-0 stats-number">S/ {{ number_format($balance, 2) }}</h2>
                    </div>
                    <i class="fas fa-balance-scale stats-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Tabla 1: Ingresos por Hospedaje --}}
    <div class="table-card">
        <div class="p-3 border-bottom">
            <h5 class="mb-0"><i class="fas fa-hotel mr-2 text-success"></i>Ingresos por Hospedaje y Consumos</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Habitación</th>
                        <th>Huésped</th>
                        <th>Forma de Pago</th>
                        <th class="text-right">Total Cobrado</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingresosHospedaje as $comprobante)
                    <tr>
                        <td>
                            <span class="badge badge-dark">{{ $comprobante->Serie }}-{{ $comprobante->Numero }}</span>
                            <br>
                            <small>{{ $comprobante->Tipo }}</small>
                        </td>
                        <td>
                            @if($comprobante->reserva)
                                {{ $comprobante->reserva->detalles->first()->habitacion->Numero ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($comprobante->reserva)
                                {{ $comprobante->reserva->huesped->Nombre }} {{ $comprobante->reserva->huesped->Apellido }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $comprobante->formaPago->Nombre ?? 'N/A' }}</td>
                        <td class="text-right monto-positivo">
                            <strong>S/ {{ number_format($comprobante->Total, 2) }}</strong>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($comprobante->FechaEmision)->format('d/m/Y H:i') }}</td>
                        <td>{{ $comprobante->usuario->name ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>No hay ingresos registrados en este período</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($ingresosHospedaje->count() > 0)
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="4" class="text-right font-weight-bold">Total Ingresos por Hospedaje:</td>
                        <td class="text-right monto-positivo">
                            <h5 class="mb-0">S/ {{ number_format($ingresosHospedaje->sum('Total'), 2) }}</h5>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    
    {{-- Tabla 2: Gastos de Caja --}}
    <div class="table-card">
        <div class="p-3 border-bottom">
            <h5 class="mb-0"><i class="fas fa-receipt mr-2 text-danger"></i>Gastos y Movimientos de Caja</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
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
                        <td><small>#{{ $mov->IdMovimiento }}</small></td>
                        <td>
                            <strong>{{ $mov->concepto }}</strong>
                            @if($mov->observacion)
                                <br><small class="text-muted">{{ $mov->observacion }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $mov->tipo === 'ingreso' ? 'badge-ingreso' : 'badge-egreso' }}">
                                {{ $mov->tipo === 'ingreso' ? 'Ingreso' : 'Egreso' }}
                            </span>
                        </td>
                        <td class="text-right {{ $mov->tipo === 'ingreso' ? 'monto-positivo' : 'monto-negativo' }}">
                            <strong>{{ $mov->tipo === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->monto, 2) }}</strong>
                        </td>
                        <td>{{ $mov->referencia ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y') }}</td>
                        <td>{{ $mov->usuario->name ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>No hay movimientos registrados en este período</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Registrar Movimiento --}}
<div class="modal fade" id="modalNuevoMovimiento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Registrar Movimiento de Caja</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formMovimiento" method="POST" action="{{ route('caja.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Movimiento *</label>
                        <select name="tipo" class="form-control form-control-lg" required>
                            <option value="ingreso">Ingreso (+)</option>
                            <option value="egreso">Egreso (-)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Concepto *</label>
                        <input type="text" name="concepto" class="form-control form-control-lg" 
                               placeholder="Ej: Compra de insumos, Pago de servicios..." required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Monto (S/) *</label>
                        <input type="number" name="monto" class="form-control form-control-lg" 
                               placeholder="0.00" step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha *</label>
                        <input type="date" name="fecha_movimiento" class="form-control form-control-lg" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Referencia</label>
                        <input type="text" name="referencia" class="form-control" 
                               placeholder="N° de factura, ticket...">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Observación</label>
                        <textarea name="observacion" class="form-control" rows="2" 
                                  placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-lg">Registrar Movimiento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formMovimiento').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#modalNuevoMovimiento').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Registrado',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                let message = 'Error al registrar';
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
    });
});
</script>
@endsection