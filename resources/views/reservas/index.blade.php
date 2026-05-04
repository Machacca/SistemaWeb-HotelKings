@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado Dinámico --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">
                Gestión de Disponibilidad
            </h1>
            <p class="text-muted mb-0">
                @if(auth()->user()->IdRol == 4)
                    Vista Global: Todas las Sedes
                @else
                    Sede: {{ auth()->user()->hotel->Nombre ?? 'Sede Asignada' }}
                @endif
            </p>
        </div>

        {{-- Leyenda de Estados --}}
        <div class="d-none d-md-flex align-items-center bg-white px-4 py-2 shadow-sm rounded-pill border">
            <div class="px-3 border-right">
                <i class="fas fa-check-circle text-success mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Disponible</small>
            </div>
            <div class="px-3 border-right">
                <i class="fas fa-bed text-danger mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Ocupada</small>
            </div>
            <div class="px-3 border-right">
                <i class="fas fa-broom text-warning mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Limpieza</small>
            </div>
            <div class="px-3">
                <i class="fas fa-tools text-secondary mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Mantenimiento</small>
            </div>
        </div>
    </div>

    {{-- Filtro de Sede para Master --}}
    @if(auth()->user()->IdRol == 4)
    <div class="mb-4">
        <form action="{{ route('reservas.index') }}" method="GET" class="d-flex align-items-center">
            <select name="hotel" class="form-control rounded-pill border-0 shadow-sm mr-2" style="max-width: 300px;" onchange="this.form.submit()">
                <option value="">Todas las Sedes</option>
                @foreach($hoteles as $h)
                    <option value="{{ $h->IdHotel }}" {{ request('hotel') == $h->IdHotel ? 'selected' : '' }}>
                        {{ $h->Nombre }}
                    </option>
                @endforeach
            </select>
            <i class="fas fa-filter text-muted ml-2"></i>
        </form>
    </div>
    @endif

    {{-- Listado por Pisos --}}
    @forelse($habitacionesPorPiso as $piso => $habitaciones)
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-dark text-white d-flex align-items-center justify-content-center rounded-lg shadow"
                     style="width: 50px; height: 50px; font-size: 1.2rem; font-weight: 700;">
                    {{ $piso }}
                </div>
                <div class="ml-3">
                    <h4 class="mb-0 text-dark font-weight-bold" style="letter-spacing: 1px;">
                        NIVEL / PISO {{ $piso }}
                    </h4>
                    <small class="text-muted text-uppercase">
                        {{ $habitaciones->count() }} Habitaciones registradas
                    </small>
                </div>
                <div class="w-100 ml-4" style="height: 2px; background: linear-gradient(to right, #ddd, transparent);"></div>
            </div>
            
            <div class="row">
                @foreach($habitaciones as $hab)
                    @php
                        $config = [
                            1 => ['class' => 'success', 'icon' => 'fa-door-open', 'label' => 'Disponible'],
                            2 => ['class' => 'danger',  'icon' => 'fa-bed',       'label' => 'Ocupada'],
                            3 => ['class' => 'warning', 'icon' => 'fa-broom',     'label' => 'Reservada'],
                            4 => ['class' => 'dark',    'icon' => 'fa-tools',     'label' => 'Mantenimiento'],
                        ];
                        
                        $st = $config[$hab->IdEstadoHabitacion] ?? $config[1];
                        
                        $url = "javascript:void(0)";
                        $attrs = "";
                        
                        if($hab->IdEstadoHabitacion == 1) {
                            // Disponible -> Crear reserva
                            $url = url('/reservas/create/' . $hab->IdHabitacion);
                        } 
                        elseif($hab->IdEstadoHabitacion == 3) {
                            // Reservada -> Mostrar detalles (show)
                            $detalleActivo = $hab->detallesReserva->filter(function($detalle) {
                                return in_array($detalle->reserva->Estado, ['Reserva', 'Confirmada']);
                            })->first();
                            if ($detalleActivo) {
                                $url = route('reservas.show', $detalleActivo->IdReserva);
                            }
                        } 
                        elseif($hab->IdEstadoHabitacion == 2) {
                            // Ocupada -> Panel de gestión (edit)
                            $detalleActivo = $hab->detallesReserva->filter(function($detalle) {
                                return $detalle->reserva->Estado === 'Check-in';
                            })->first();
                            if ($detalleActivo) {
                                $url = route('reservas.edit', $detalleActivo->IdReserva);
                            }
                        }
                        elseif($hab->IdEstadoHabitacion == 4) {
                            // Mantenimiento/Limpieza -> SweetAlert para liberar
                            $attrs = 'onclick="confirmarLiberacion('.$hab->IdHabitacion.', \''.$hab->Numero.'\')"';
                        }
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex">
                        <a href="{{ $url }}" {!! $attrs !!} class="text-decoration-none w-100">
                            <div class="card hab-card-premium border-0 shadow-sm h-100 w-100 bg-{{ $st['class'] }}">
                                <div class="card-body d-flex align-items-center p-4 text-{{ $hab->IdEstadoHabitacion == 3 ? 'dark' : 'white' }}">
                                    <div class="icon-circle mr-4" style="background: rgba({{ $hab->IdEstadoHabitacion == 3 ? '0,0,0' : '255,255,255' }}, 0.15);">
                                        <i class="fas {{ $st['icon'] }} fa-2x"></i>
                                    </div>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                                            <h2 class="font-weight-bold mb-0" style="font-size: 2rem;">{{ $hab->Numero }}</h2>
                                            <span class="badge badge-light-soft mt-1" style="background: rgba({{ $hab->IdEstadoHabitacion == 3 ? '0,0,0' : '255,255,255' }}, 0.2);">
                                                {{ $st['label'] }}
                                            </span>
                                        </div>
                                        <p class="mb-0 mt-2 text-uppercase small font-weight-bold" style="opacity: 0.8; letter-spacing: 1px;">
                                            {{ $hab->tipo->Nombre ?? 'TIPO N/A' }}
                                            @if(auth()->user()->IdRol == 4)
                                                <br><small class="font-weight-normal text-lowercase">{{ $hab->hotel->Nombre }}</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fas fa-hotel fa-3x text-muted mb-3"></i>
            <p class="h5 text-muted">No hay habitaciones registradas para esta sede.</p>
        </div>
    @endforelse
</div>

<style>
    .hab-card-premium {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        min-height: 160px;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .hab-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
    
    .hab-card-premium .card-body {
        min-height: 160px;
        display: flex;
        align-items: center;
    }
    
    .icon-circle {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .badge-light-soft {
        font-size: 0.65rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .bg-success {
        background-color: #28a745 !important;
    }
    .bg-danger {
        background-color: #dc3545 !important;
    }
    .bg-warning {
        background-color: #ffc107 !important;
    }
    .bg-dark {
        background-color: #343a40 !important;
    }
    
    @media (max-width: 768px) {
        .icon-circle {
            width: 40px;
            height: 40px;
        }
        .icon-circle i {
            font-size: 1.2rem;
        }
        .hab-card-premium .card-body {
            padding: 1rem !important;
        }
        .hab-card-premium h2 {
            font-size: 1.5rem !important;
        }
    }
</style>

<script>
    function confirmarLiberacion(idHab, numHab) {
        SwalConfirmacion(
            '¿Habilitar Habitación ' + numHab + '?',
            'El personal de limpieza ha terminado. La habitación pasará a estado DISPONIBLE.',
            'info',
            'Sí, habilitar'
        ).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ url("/habitaciones") }}/' + idHab + '/liberar', {
                    method: 'PUT',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        SwalToast('success', data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        SwalError('Error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    SwalError('Error', 'Ocurrió un error al liberar la habitación');
                });
            }
        });
    }

    @if(session('success'))
        SwalToast('success', "{{ session('success') }}");
    @endif
    
    @if(session('error'))
        SwalToast('error', "{{ session('error') }}");
    @endif
</script>

@endsection