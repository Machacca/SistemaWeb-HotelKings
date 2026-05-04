@extends('layouts.app')

@section('title', 'Calendario de Habitaciones - ' . $meses[$mes] . ' ' . $anio)

@section('content')
<style>
    .calendar-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .calendar-top-bar {
        background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .month-selector {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .month-selector h2 {
        color: white;
        margin: 0;
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        min-width: 180px;
        text-align: center;
    }
    
    .btn-nav {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.3);
        background: transparent;
        color: white;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-nav:hover {
        background: #c9a45c;
        border-color: #c9a45c;
        color: #1a1a1a;
    }
    
    .btn-today {
        background: #c9a45c;
        color: #1a1a1a;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-today:hover {
        background: #d4b36a;
    }
    
    .quick-months {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-quick-month {
        padding: 6px 15px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.3);
        background: transparent;
        color: white;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s;
        white-space: nowrap;
    }
    
    .btn-quick-month:hover {
        background: rgba(255,255,255,0.1);
    }
    
    .btn-quick-month.active {
        background: #c9a45c;
        color: #1a1a1a;
        border-color: #c9a45c;
        font-weight: bold;
    }
    
    .table-container {
        overflow-x: auto;
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .calendar-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 800px;
    }
    
    .calendar-table thead {
        position: sticky;
        top: 0;
        z-index: 20;
    }
    
    .calendar-table thead th {
        background: #1a1a1a;
        color: #c9a45c;
        padding: 10px 4px;
        font-weight: 600;
        font-size: 11px;
        border: 1px solid #333;
    }
    
    .calendar-table thead th.weekday {
        background: #2d2d2d;
        color: #fff;
        font-size: 10px;
        padding: 6px 4px;
    }
    
    .calendar-table thead th.today-col {
        background: #c9a45c !important;
        color: #1a1a1a !important;
    }
    
    .col-room {
        position: sticky;
        left: 0;
        background: #f8f9fa;
        font-weight: bold;
        text-align: left !important;
        padding: 10px 15px !important;
        min-width: 140px;
        z-index: 10;
        border-right: 2px solid #dee2e6;
    }
    
    .col-room small {
        display: block;
        color: #6c757d;
        font-weight: normal;
        font-size: 10px;
    }
    
    .day-cell {
        height: 32px;
        min-width: 32px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #e9ecef;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        font-size: 10px;
    }
    
    .day-cell:hover {
        background: #f8f9fa;
        transform: scale(1.05);
        z-index: 5;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .day-cell.today {
        background: #fff8e1 !important;
    }
    
    .day-cell.today::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 5px;
        height: 5px;
        background: #dc3545;
        border-radius: 50%;
    }
    
    .day-cell.occupied {
        background: #dc3545 !important;
        color: white;
    }
    
    .day-cell.reserved {
        background: #ffc107 !important;
        color: #1a1a1a;
    }
    
    .day-cell.checkin {
        border-left: 3px solid #198754 !important;
    }
    
    .day-cell.checkout {
        border-right: 3px solid #dc3545 !important;
    }
    
    .day-cell.weekend {
        background: #f8f9fa;
    }
    
    .tooltip-info {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #1a1a1a;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 10px;
        white-space: nowrap;
        z-index: 100;
        pointer-events: none;
        margin-bottom: 5px;
    }
    
    .tooltip-info::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #1a1a1a;
    }
    
    .day-cell:hover .tooltip-info {
        display: block;
    }
    
    .legend {
        display: flex;
        gap: 25px;
        padding: 15px 25px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        flex-wrap: wrap;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
    }
    
    .legend-color {
        width: 25px;
        height: 25px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700;">
                <i class="fas fa-calendar-alt mr-2 text-warning"></i>Calendario de Habitaciones
            </h1>
            <p class="text-muted mb-0">Gestión visual de ocupación</p>
        </div>
        <div>
            <a href="{{ route('reservas.index') }}" class="btn btn-outline-dark rounded-pill px-4 mr-2">
                <i class="fas fa-th mr-2"></i> Mapa
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-dark rounded-pill px-4">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
        </div>
    </div>
    
    {{-- Calendario --}}
    <div class="calendar-wrapper">
        {{-- Barra superior --}}
        <div class="calendar-top-bar">
            <div class="month-selector">
                <button class="btn-nav" onclick="navegar(-1)" title="Mes anterior">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h2>{{ $meses[$mes] }} {{ $anio }}</h2>
                <button class="btn-nav" onclick="navegar(1)" title="Mes siguiente">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button class="btn-today" onclick="irHoy()">
                    <i class="fas fa-calendar-day mr-1"></i> Hoy
                </button>
            </div>
            
            <div class="quick-months">
                @foreach($mesesRapidos as $m)
                    <button class="btn-quick-month {{ $m['activo'] ? 'active' : '' }}"
                            onclick="window.location.href='{{ route('calendario.index', ['mes' => $m['mes'], 'anio' => $m['anio']]) }}'">
                        {{ $m['nombre'] }}
                    </button>
                @endforeach
            </div>
        </div>
        
        {{-- Tabla del calendario --}}
        <div class="table-container">
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th class="col-room">Habitación</th>
                        @foreach($diasMes as $dia)
                            @php
                                $esHoy = $dia->isToday();
                                $esFinSemana = $dia->isWeekend();
                            @endphp
                            <th class="weekday {{ $esHoy ? 'today-col' : '' }}">
                                {{ $dia->shortDayName }}<br>
                                <strong>{{ $dia->day }}</strong>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($calendario as $habId => $data)
                        <tr>
                            <td class="col-room">
                                <strong>{{ $data['habitacion']->Numero }}</strong>
                                <small>{{ $data['habitacion']->tipo->Nombre ?? 'N/A' }} | Piso {{ $data['habitacion']->Piso }}</small>
                            </td>
                            
                            @foreach($diasMes as $dia)
                                @php
                                    $info = $data['dias'][$dia->day] ?? null;
                                    $esHoy = $dia->isToday();
                                    $esFinSemana = $dia->isWeekend();
                                    
                                    $clases = [];
                                    if ($esHoy) $clases[] = 'today';
                                    if ($esFinSemana) $clases[] = 'weekend';
                                    
                                    if ($info) {
                                        if ($info['estado'] === 'Check-in') {
                                            $clases[] = 'occupied';
                                        } elseif ($info['estado'] === 'Reserva') {
                                            $clases[] = 'reserved';
                                        }
                                        if ($info['es_checkin']) $clases[] = 'checkin';
                                        if ($info['es_checkout']) $clases[] = 'checkout';
                                    }
                                @endphp
                                
                                <td class="day-cell {{ implode(' ', $clases) }}"
                                    @if($info)
                                        onclick="window.location.href='{{ route('reservas.edit', $info['reserva_id']) }}'"
                                    @else
                                        onclick="window.location.href='{{ route('reservas.create', $habId) }}'"
                                    @endif
                                    title="{{ $dia->format('d/m/Y') }}">
                                    
                                    @if($info)
                                        <div class="tooltip-info">
                                            {{ $info['huesped'] }}<br>
                                            {{ $info['estado'] }}
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Leyenda --}}
        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background: #ffc107;"></div>
                <span>Reservada</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #dc3545;"></div>
                <span>Ocupada (Check-in)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #198754; width: 3px;"></div>
                <span>Inicio (Check-in)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #dc3545; width: 3px;"></div>
                <span>Fin (Check-out)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #fff8e1;"></div>
                <span>Hoy</span>
            </div>
        </div>
    </div>
</div>

<script>
    function navegar(direccion) {
        let mes = {{ $mes }} + direccion;
        let anio = {{ $anio }};
        
        if (mes > 12) {
            mes = 1;
            anio++;
        } else if (mes < 1) {
            mes = 12;
            anio--;
        }
        
        window.location.href = '{{ route("calendario.index") }}?mes=' + mes + '&anio=' + anio;
    }
    
    function irHoy() {
        const hoy = new Date();
        window.location.href = '{{ route("calendario.index") }}?mes=' + (hoy.getMonth() + 1) + '&anio=' + hoy.getFullYear();
    }
    
    // Scroll horizontal con la rueda del mouse
    document.querySelector('.table-container').addEventListener('wheel', function(e) {
        if (e.shiftKey) {
            e.preventDefault();
            this.scrollLeft += e.deltaY;
        }
    });
</script>
@endsection