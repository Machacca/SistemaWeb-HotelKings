@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">
                Gestión de Disponibilidad
            </h1>
            <p class="text-muted mb-0">Panel de control de planta y estados de habitación</p>
        </div>

        <div class="d-none d-md-flex align-items-center bg-white px-4 py-2 shadow-sm rounded-pill border">
            <div class="px-3 border-right">
                <i class="fas fa-check-circle text-success mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Libre</small>
            </div>
            <div class="px-3 border-right">
                <i class="fas fa-bed text-danger mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Ocupada</small>
            </div>
            <div class="px-3 border-right">
                <i class="fas fa-clock text-warning mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Reservada</small>
            </div>
            <div class="px-3">
                <i class="fas fa-tools text-dark mr-2"></i>
                <small class="font-weight-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Mantenimiento</small>
            </div>
        </div>
    </div>

    @foreach($habitacionesPorPiso as $piso => $habitaciones)
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
                <div class="flex-grow-1 ml-4" style="height: 2px; background: linear-gradient(to right, #ddd, transparent);"></div>
            </div>
            
            <div class="row">
                @foreach($habitaciones as $hab)
                    @php
                        // Configuración estética según estado
                        $config = [
                            1 => ['class' => 'success', 'icon' => 'fa-door-open', 'label' => 'Disponible'],
                            2 => ['class' => 'danger', 'icon' => 'fa-bed', 'label' => 'Ocupada'],
                            3 => ['class' => 'warning', 'icon' => 'fa-clock', 'label' => 'Reservada'],
                            4 => ['class' => 'dark', 'icon' => 'fa-tools', 'label' => 'Mantenimiento'],
                        ];

                        $st = $config[$hab->IdEstadoHabitacion] ?? $config[1];
                        
                        // Lógica de navegación
                        $url = "javascript:void(0)";
                        $attrs = "";

                        switch($hab->IdEstadoHabitacion) {
                            case 1: // DISPONIBLE
                                $url = route('reservas.create', ['id' => $hab->IdHabitacion]);
                                break;
                            case 2: // OCUPADA
                                // Usar '#' si aún no tienes la ruta reservas.edit lista
                                $url = "#"; 
                                break;
                            case 3: // RESERVADA
                                $attrs = 'data-toggle="modal" data-target="#modalDetalleReserva" onclick="cargarDetalle('.$hab->IdHabitacion.')"';
                                break;
                            case 4: // MANTENIMIENTO
                                $attrs = 'onclick="confirmarLimpieza('.$hab->IdHabitacion.', '.$hab->Numero.')"';
                                break;
                        }
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <a href="{{ $url }}" {!! $attrs !!} class="text-decoration-none">
                            <div class="card hab-card-premium border-0 shadow-sm h-100 bg-{{ $st['class'] }}">
                                <div class="card-body d-flex align-items-center p-4 text-white">
                                    <div class="icon-circle mr-4">
                                        <i class="fas {{ $st['icon'] }} fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h2 class="font-weight-bold mb-0" style="font-size: 2.2rem;">
                                                {{ $hab->Numero }}
                                            </h2>
                                            <span class="badge badge-light-soft">
                                                {{ $st['label'] }}
                                            </span>
                                        </div>
                                        <p class="mb-0 text-uppercase small font-weight-bold"
                                           style="opacity: 0.9; letter-spacing: 2px;">
                                            {{ $hab->tipo->Nombre }}
                                        </p>
                                    </div>
                                    <div class="ml-3 opacity-50">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

{{-- MODAL PARA EL CASO 3 (Reservado) --}}
@include('reservas.modal_detalle_reserva')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // CASO 3: Cargar datos en el modal
    function cargarDetalle(idHab) {
        console.log("Consultando reserva para habitación ID: " + idHab);
        // Aquí puedes usar AJAX para llenar el contenido del modal antes de mostrarlo
    }

    // CASO 4: Confirmación para liberar mantenimiento
    function confirmarLimpieza(idHab, numHab) {
        Swal.fire({
            title: '¿Habitación ' + numHab + ' Limpia?',
            text: "La habitación pasará a estar disponible para la venta.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1d976c',
            confirmButtonText: 'Sí, liberar ahora',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostramos un mensaje de carga mientras la base de datos procesa
                Swal.fire({
                    title: 'Procesando...',
                    didOpen: () => { Swal.showLoading() },
                    allowOutsideClick: false
                });

                // Redirigimos a la ruta para que el Controlador haga el save()
                window.location.href = "{{ url('/habitaciones/liberar') }}/" + idHab;
            }
        });
    }
</script>

<style>
    /* TARJETAS */
    .hab-card-premium {
        border-radius: 25px !important;
        transition: all 0.35s ease;
        overflow: hidden;
    }

    .hab-card-premium:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
    }

    /* ICONO */
    .icon-circle {
        background: rgba(255, 255, 255, 0.2);
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* BADGE */
    .badge-light-soft {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 800;
    }

    /* GRADIENTES SEGÚN ESTADO */
    .bg-success { background: linear-gradient(135deg, #1d976c 0%, #93f9b9 100%) !important; }
    .bg-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%) !important; }
    .bg-warning { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%) !important; }
    .bg-dark { background: linear-gradient(135deg, #232526 0%, #414345 100%) !important; }
</style>
@endsection