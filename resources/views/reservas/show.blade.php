@extends('layouts.app')

@section('title', 'Detalles de Reserva')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a;">Detalles de Reserva</h1>
            <p class="text-muted mb-0">Reserva #{{ $reserva->IdReserva }} - {{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</p>
        </div>
        <a href="{{ route('reservas.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Mapa
        </a>
    </div>

    <div class="row">
        {{-- Columna izquierda: Información de la reserva --}}
        <div class="col-lg-8">
            {{-- Información General --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2 text-warning"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted text-uppercase mb-1">Huésped Principal</label>
                            <p class="mb-0 font-weight-bold">{{ $reserva->huesped->Nombre }} {{ $reserva->huesped->Apellido }}</p>
                            <small class="text-muted">{{ $reserva->huesped->TipoDocumento }}: {{ $reserva->huesped->NroDocumento }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted text-uppercase mb-1">Canal de Reserva</label>
                            <p class="mb-0">{{ $reserva->canal->Nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted text-uppercase mb-1">Fecha de Reserva</label>
                            <p class="mb-0">{{ $reserva->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted text-uppercase mb-1">Estado Actual</label>
                            <p><span class="badge {{ $reserva->estado_badge_class }} px-3 py-2">{{ $reserva->estado_nombre }}</span></p>
                        </div>
                        @if($reserva->observaciones)
                        <div class="col-12 mb-3">
                            <label class="small text-muted text-uppercase mb-1">Observaciones</label>
                            <p class="mb-0">{{ $reserva->observaciones }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Habitaciones Reservadas --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-bed mr-2 text-warning"></i> Habitaciones Reservadas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr><th>Habitación</th><th>Check-In</th><th>Check-Out</th><th>Precio/Noche</th><th>Acompañantes</th></tr>
                            </thead>
                            <tbody>
                                @foreach($reserva->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->habitacion->Numero }} (Piso {{ $detalle->habitacion->Piso }})</td>
                                    <td>{{ \Carbon\Carbon::parse($detalle->FechaCheckIn)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($detalle->FechaCheckOut)->format('d/m/Y') }}</td>
                                    <td>S/ {{ number_format($detalle->PrecioNoche, 2) }}</td>
                                    <td>
                                        @foreach($detalle->acompanantes as $acomp)
                                            <span class="badge badge-light mr-1">{{ $acomp->Nombre }} {{ $acomp->Apellido }}</span>
                                        @endforeach
                                     </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Consumos Registrados --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart mr-2 text-warning"></i> Consumos Registrados</h5>
                </div>
                <div class="card-body p-0">
                    @if($reserva->consumos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr><th>Producto</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($reserva->consumos as $consumo)
                                    <tr><td>{{ $consumo->producto->Nombre }}</td><td>{{ $consumo->Cantidad }}</td><td>S/ {{ number_format($consumo->PrecioVenta, 2) }}</td><td>S/ {{ number_format($consumo->Cantidad * $consumo->PrecioVenta, 2) }}</td></tr>
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
                <div class="card-header bg-dark text-white">
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
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase mb-1">Descuentos</label>
                        <p class="h5 mb-0 text-danger">- S/ {{ number_format($reserva->detalles->sum('Descuento'), 2) }}</p>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase mb-1">SALDO PENDIENTE</label>
                        <h2 class="mb-0 text-success">S/ {{ number_format($reserva->saldo_pendiente, 2) }}</h2>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg" id="btnConfirmarIngreso">
                            <i class="fas fa-key mr-2"></i> CONFIRMAR INGRESO
                        </button>
                        <button type="button" class="btn btn-danger" id="btnAnularReserva">
                            <i class="fas fa-ban mr-2"></i> ANULAR RESERVA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const reservaId = {{ $reserva->IdReserva }};
        
        // Confirmar Ingreso - URL CORRECTA
        const btnConfirmar = document.getElementById('btnConfirmarIngreso');
        if (btnConfirmar) {
            btnConfirmar.onclick = function() {
                const url = '/reservas/' + reservaId + '/checkin';
                
                Swal.fire({
                    title: '¿Confirmar ingreso del huésped?',
                    text: 'La habitación pasará a estado OCUPADA y se iniciará la estadía.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar ingreso',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: data.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                // Redirigir después de 1 segundo
                                setTimeout(() => {
                                    window.location.href = data.redirect || '/reservas/' + reservaId + '/edit';
                                }, 1000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la solicitud'
                            });
                        });
                    }
                });
            };
        }
                
        // Anular Reserva - URL CORRECTA
        const btnAnular = document.getElementById('btnAnularReserva');
        if (btnAnular) {
            btnAnular.onclick = function() {
                const url = '/reservas/' + reservaId;
                
                Swal.fire({
                    title: '¿Anular reserva?',
                    text: 'Esta acción liberará las habitaciones y no se podrá deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, anular reserva',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: data.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                setTimeout(() => {
                                    window.location.href = '/reservas';
                                }, 1000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la solicitud'
                            });
                        });
                    }
                });
            };
        }
    })();
</script>
@endsection