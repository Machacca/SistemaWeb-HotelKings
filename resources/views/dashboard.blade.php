@extends('layouts.app')

@section('title', 'Dashboard - ' . ($hotel_nombre ?? 'Hotel'))

@section('content')
<style>
    :root {
        --gold: #c9a45c;
        --gold-light: #d4b36a;
        --dark: #1a1a1a;
        --darker: #111111;
        --cream: #faf7f2;
    }
    
    .dashboard-luxury {
        padding: 25px;
        background: #f5f0e8;
        min-height: 100vh;
    }
    
    /* Header elegante */
    .luxury-header {
        background: linear-gradient(135deg, var(--darker) 0%, #1a1a1a 50%, #2d1f0e 100%);
        border-radius: 25px;
        padding: 35px 40px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(201, 164, 92, 0.2);
    }
    
    .luxury-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(201, 164, 92, 0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .luxury-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }
    
    .luxury-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: white;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }
    
    .luxury-header .subtitle {
        color: var(--gold);
        font-size: 1rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-weight: 300;
    }
    
    .luxury-header .date-display {
        color: rgba(255,255,255,0.6);
        font-size: 0.9rem;
        font-style: italic;
    }
    
    .hotel-indicator {
        display: inline-flex;
        align-items: center;
        background: rgba(201, 164, 92, 0.15);
        border: 1px solid rgba(201, 164, 92, 0.3);
        padding: 10px 20px;
        border-radius: 50px;
        color: var(--gold);
        font-size: 0.9rem;
        letter-spacing: 1px;
    }
    
    .hotel-indicator i {
        margin-right: 8px;
    }
    
    /* Tarjetas de estadísticas */
    .stat-luxury {
        background: white;
        border-radius: 20px;
        padding: 30px 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid #eee;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        height: 100%;
    }
    
    .stat-luxury:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: var(--gold);
    }
    
    .stat-luxury .stat-accent {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }
    
    .stat-luxury .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    
    .stat-luxury .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
        margin-bottom: 5px;
        color: var(--dark);
    }
    
    .stat-luxury .stat-label {
        font-size: 0.85rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 500;
    }
    
    .stat-luxury .stat-change {
        font-size: 0.8rem;
        margin-top: 8px;
        font-weight: 600;
    }
    
    /* Secciones */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--dark);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--gold);
        display: inline-block;
        letter-spacing: 1px;
    }
    
    /* Accesos rápidos */
    .quick-card {
        background: white;
        border-radius: 20px;
        padding: 25px 15px;
        text-align: center;
        border: 1px solid #eee;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: var(--dark);
        display: block;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .quick-card:hover {
        border-color: var(--gold);
        text-decoration: none;
        color: var(--dark);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }
    
    .quick-card .quick-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.6rem;
        color: white;
        transition: all 0.3s;
    }
    
    .quick-card:hover .quick-icon {
        transform: scale(1.1);
    }
    
    .quick-card h6 {
        font-weight: 700;
        margin-bottom: 3px;
        font-size: 0.9rem;
    }
    
    .quick-card small {
        color: #999;
        font-size: 0.75rem;
    }
    
    /* Tabla elegante */
    .table-luxury {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        border: 1px solid #eee;
    }
    
    .table-luxury .table-header {
        background: var(--dark);
        color: var(--gold);
        padding: 18px 25px;
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    
    .table-luxury table {
        margin-bottom: 0;
    }
    
    .table-luxury thead th {
        background: #fafafa;
        border-bottom: 2px solid #eee;
        color: #666;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px;
        font-weight: 600;
    }
    
    .table-luxury tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .table-luxury tbody tr {
        transition: all 0.2s;
    }
    
    .table-luxury tbody tr:hover {
        background: #fdfaf5;
        cursor: pointer;
    }
    
    .badge-status-luxury {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    /* Resumen panel */
    .summary-panel {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        border: 1px solid #eee;
    }
    
    .summary-panel .panel-header {
        background: var(--dark);
        color: var(--gold);
        padding: 18px 25px;
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 25px;
        border-bottom: 1px solid #f5f5f5;
        transition: all 0.2s;
    }
    
    .summary-item:last-child {
        border-bottom: none;
    }
    
    .summary-item:hover {
        background: #fdfaf5;
    }
    
    .summary-item .label {
        color: #666;
        font-size: 0.9rem;
    }
    
    .summary-item .value {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark);
    }
    
    .summary-item .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }
</style>

<div class="dashboard-luxury">
    
    {{-- Header Principal --}}
    <div class="luxury-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="subtitle mb-2">Panel de Administración</div>
                <h1>Bienvenido, {{ $nombre_usuario }}</h1>
                <p class="date-display mb-0 mt-2">
                    <i class="far fa-calendar-alt mr-2"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, j \d\e F \d\e Y') }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <div class="hotel-indicator">
                    <i class="fas fa-building"></i>
                    {{ $hotel_nombre }}
                </div>
                <br>
                <small style="color: rgba(255,255,255,0.5); margin-top: 8px; display: inline-block;">
                    <i class="fas fa-shield-alt mr-1"></i>{{ $rol_usuario }}
                </small>
            </div>
        </div>
    </div>
    
    {{-- Estadísticas Principales --}}
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-luxury" onclick="window.location.href='{{ route('habitaciones.index') }}'">
                <div class="stat-accent" style="background: #c9a45c;"></div>
                <div class="stat-icon" style="background: rgba(201, 164, 92, 0.1); color: #c9a45c;">
                    <i class="fas fa-bed"></i>
                </div>
                <div class="stat-value">{{ \App\Models\Habitacion::where('activo', true)->count() }}</div>
                <div class="stat-label">Habitaciones</div>
                <div class="stat-change text-success">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ \App\Models\Habitacion::where('IdEstadoHabitacion', 1)->where('activo', true)->count() }} disponibles
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-luxury" onclick="window.location.href='{{ route('reservas.index') }}'">
                <div class="stat-accent" style="background: #6c5ce7;"></div>
                <div class="stat-icon" style="background: rgba(108, 92, 231, 0.1); color: #6c5ce7;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ \App\Models\Reserva::whereIn('Estado', ['Reserva', 'Check-in'])->count() }}</div>
                <div class="stat-label">Reservas Activas</div>
                <div class="stat-change" style="color: #6c5ce7;">
                    <i class="fas fa-clock mr-1"></i>
                    {{ \App\Models\Reserva::where('Estado', 'Check-in')->count() }} en curso
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-luxury" onclick="window.location.href='{{ route('huespedes.index') }}'">
                <div class="stat-accent" style="background: #00b894;"></div>
                <div class="stat-icon" style="background: rgba(0, 184, 148, 0.1); color: #00b894;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ \App\Models\Huesped::where('activo', true)->count() }}</div>
                <div class="stat-label">Huéspedes</div>
                <div class="stat-change text-muted">
                    <i class="fas fa-user-check mr-1"></i>
                    Registro activo
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-luxury" onclick="window.location.href='{{ route('caja.index') }}'">
                <div class="stat-accent" style="background: #e17055;"></div>
                <div class="stat-icon" style="background: rgba(225, 112, 85, 0.1); color: #e17055;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">
                    S/ {{ number_format(\App\Models\MovimientoCaja::whereDate('fecha_movimiento', today())->where('tipo', 'ingreso')->sum('monto'), 2) }}
                </div>
                <div class="stat-label">Ingresos Hoy</div>
                <div class="stat-change text-muted">
                    <i class="fas fa-calendar-day mr-1"></i>
                    {{ date('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
    
    {{-- Accesos Rápidos --}}
    <h4 class="section-title">Accesos Rápidos</h4>
    <div class="row mb-5">
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ route('reservas.index') }}" class="quick-card">
                <div class="quick-icon" style="background: linear-gradient(135deg, #c9a45c, #b8943e);">
                    <i class="fas fa-th-large"></i>
                </div>
                <h6>Mapa de Hab.</h6>
                <small>Disponibilidad</small>
            </a>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ route('calendario.index') }}" class="quick-card">
                <div class="quick-icon" style="background: linear-gradient(135deg, #6c5ce7, #a29bfe);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h6>Calendario</h6>
                <small>Vista mensual</small>
            </a>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ route('caja.index') }}" class="quick-card">
                <div class="quick-icon" style="background: linear-gradient(135deg, #00b894, #55efc4);">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h6>Caja</h6>
                <small>Finanzas</small>
            </a>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ route('huespedes.index') }}" class="quick-card">
                <div class="quick-icon" style="background: linear-gradient(135deg, #0984e3, #74b9ff);">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h6>Huéspedes</h6>
                <small>Clientes</small>
            </a>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ route('productos.index') }}" class="quick-card">
                <div class="quick-icon" style="background: linear-gradient(135deg, #e17055, #fab1a0);">
                    <i class="fas fa-boxes"></i>
                </div>
                <h6>Inventario</h6>
                <small>Productos</small>
            </a>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ route('perfil.index') }}" class="quick-card">
                <div class="quick-icon" style="background: linear-gradient(135deg, #636e72, #b2bec3);">
                    <i class="fas fa-cog"></i>
                </div>
                <h6>Mi Perfil</h6>
                <small>Configuración</small>
            </a>
        </div>
    </div>
    
    {{-- Tablas --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="table-luxury">
                <div class="table-header">
                    <i class="fas fa-history mr-2"></i> Últimas Reservas
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th># Reserva</th>
                                <th>Huésped</th>
                                <th>Habitación</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Estado</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ultimasReservas = \App\Models\Reserva::with(['huesped', 'detalles.habitacion'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($ultimasReservas as $reserva)
                            <tr onclick="window.location.href='{{ route('reservas.edit', $reserva->IdReserva) }}'">
                                <td><strong>#{{ str_pad($reserva->IdReserva, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>
                                    <i class="fas fa-user-circle mr-2 text-muted"></i>
                                    {{ $reserva->huesped->Nombre ?? 'N/A' }} {{ $reserva->huesped->Apellido ?? '' }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $reserva->detalles->first()->habitacion->Numero ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $reserva->detalles->first()->FechaCheckIn ?? '-' }}</td>
                                <td>{{ $reserva->detalles->first()->FechaCheckOut ?? '-' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($reserva->Estado) {
                                            'Reserva' => 'bg-warning text-dark',
                                            'Check-in' => 'bg-success',
                                            'Finalizada' => 'bg-secondary',
                                            'Anulada' => 'bg-danger',
                                            default => 'bg-dark'
                                        };
                                    @endphp
                                    <span class="badge badge-status-luxury {{ $badgeClass }}">
                                        {{ $reserva->Estado }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <strong style="color: #c9a45c;">
                                        S/ {{ number_format($reserva->TotalReserva, 2) }}
                                    </strong>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                                    <p>No hay reservas registradas aún</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- Panel de Resumen --}}
        <div class="col-lg-4 mb-4">
            <div class="summary-panel">
                <div class="panel-header">
                    <i class="fas fa-chart-pie mr-2"></i> Resumen del Día
                </div>
                <div class="summary-item">
                    <span class="label">
                        <span class="dot" style="background: #00b894;"></span>
                        Check-ins Hoy
                    </span>
                    <span class="value">
                        @php
                            $hoy = today();
                            $checkinsHoy = \App\Models\Reserva::whereHas('detalles', function($q) use ($hoy) {
                                $q->whereDate('FechaCheckIn', $hoy);
                            })->count();
                        @endphp
                        {{ $checkinsHoy }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">
                        <span class="dot" style="background: #e17055;"></span>
                        Check-outs Hoy
                    </span>
                    <span class="value">
                        @php
                            $checkoutsHoy = \App\Models\Reserva::whereHas('detalles', function($q) use ($hoy) {
                                $q->whereDate('FechaCheckOut', $hoy);
                            })->count();
                        @endphp
                        {{ $checkoutsHoy }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">
                        <span class="dot" style="background: #c9a45c;"></span>
                        Habitaciones Disponibles
                    </span>
                    <span class="value">
                        @php
                            $disponibles = \App\Models\Habitacion::where('IdEstadoHabitacion', 1)->where('activo', true)->count();
                        @endphp
                        {{ $disponibles }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">
                        <span class="dot" style="background: #6c5ce7;"></span>
                        Habitaciones Ocupadas
                    </span>
                    <span class="value">
                        @php
                            $ocupadas = \App\Models\Habitacion::where('IdEstadoHabitacion', 2)->where('activo', true)->count();
                        @endphp
                        {{ $ocupadas }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">
                        <span class="dot" style="background: #0984e3;"></span>
                        Total Huéspedes Hoy
                    </span>
                    <span class="value">
                        @php
                            $huespedesHoy = \App\Models\Reserva::where('Estado', 'Check-in')
                                ->withCount('detalles')
                                ->get()
                                ->sum('detalles_count');
                        @endphp
                        {{ $huespedesHoy }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection