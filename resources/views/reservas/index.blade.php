{{-- resources/views/reservas/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4 flex justify-between items-center">
            <h2 class="text-white text-2xl font-bold">Gestión de Reservas</h2>
            <a href="{{ route('reservas.create') }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                + Nueva Reserva
            </a>
        </div>
        
        <div class="p-6">
            <!-- Filtros -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" id="search" placeholder="Buscar huésped..." 
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                </div>
                <div>
                    <select id="filterEstado" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Todos los estados</option>
                        <option value="Activa">Activas</option>
                        <option value="Check-in">Check-in Realizado</option>
                        <option value="Check-out">Check-out Realizado</option>
                        <option value="Cancelada">Canceladas</option>
                    </select>
                </div>
                <div>
                    <input type="date" id="filterFecha" placeholder="Filtrar por fecha" 
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                </div>
                <div>
                    <button id="btnLimpiar" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        Limpiar Filtros
                    </button>
                </div>
            </div>
            
            <!-- Tabla de Reservas -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Huésped</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Habitación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tablaReservas">
                        @forelse($reservas as $reserva)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $reserva->IdReserva }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $reserva->huesped->NombreCompleto ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $reserva->huesped->NroDocumento ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($reserva->detalles as $detalle)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        Hab. {{ $detalle->habitacion->Numero ?? 'N/A' }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>Entrada: {{ optional($reserva->detalles->first())->FechaEntrada ? \Carbon\Carbon::parse($reserva->detalles->first()->FechaEntrada)->format('d/m/Y') : 'N/A' }}</div>
                                <div>Salida: {{ optional($reserva->detalles->first())->FechaSalida ? \Carbon\Carbon::parse($reserva->detalles->first()->FechaSalida)->format('d/m/Y') : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($reserva->Estado == 'Activa') bg-green-100 text-green-800
                                    @elseif($reserva->Estado == 'Check-in') bg-blue-100 text-blue-800
                                    @elseif($reserva->Estado == 'Check-out') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $reserva->Estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                S/ {{ number_format($reserva->TotalReserva, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('reservas.show', $reserva->IdReserva) }}" 
                                   class="text-blue-600 hover:text-blue-900 inline-block" title="Ver detalle">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('reservas.edit', $reserva->IdReserva) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 inline-block" title="Editar reserva">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if($reserva->Estado == 'Activa')
                                <button onclick="confirmCheckIn({{ $reserva->IdReserva }})" 
                                        class="text-green-600 hover:text-green-900" title="Realizar Check-in">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                                @endif
                                @if($reserva->Estado == 'Check-in')
                                <button onclick="confirmCheckOut({{ $reserva->IdReserva }})" 
                                        class="text-orange-600 hover:text-orange-900" title="Realizar Check-out">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </button>
                                @endif
                                <form action="{{ route('reservas.destroy', $reserva->IdReserva) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Cancelar esta reserva?')" 
                                            class="text-red-600 hover:text-red-900" title="Cancelar reserva">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                No hay reservas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $reservas->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filtros en tiempo real
    const searchInput = document.getElementById('search');
    const filterEstado = document.getElementById('filterEstado');
    const filterFecha = document.getElementById('filterFecha');
    const btnLimpiar = document.getElementById('btnLimpiar');
    
    function filtrarReservas() {
        const search = searchInput.value.toLowerCase();
        const estado = filterEstado.value;
        const fecha = filterFecha.value;
        
        const rows = document.querySelectorAll('#tablaReservas tr');
        
        rows.forEach(row => {
            if (row.querySelector('td')) {
                const huesped = row.querySelector('td:nth-child(2)')?.innerText.toLowerCase() || '';
                const estadoRow = row.querySelector('td:nth-child(5) span')?.innerText || '';
                const fechaRow = row.querySelector('td:nth-child(4) div:first-child')?.innerText || '';
                
                let show = true;
                
                if (search && !huesped.includes(search)) show = false;
                if (estado && estadoRow !== estado) show = false;
                if (fecha && !fechaRow.includes(fecha)) show = false;
                
                row.style.display = show ? '' : 'none';
            }
        });
    }
    
    searchInput.addEventListener('keyup', filtrarReservas);
    filterEstado.addEventListener('change', filtrarReservas);
    filterFecha.addEventListener('change', filtrarReservas);
    
    btnLimpiar.addEventListener('click', () => {
        searchInput.value = '';
        filterEstado.value = '';
        filterFecha.value = '';
        filtrarReservas();
    });
    
    function confirmCheckIn(id) {
        if (confirm('¿Confirmar Check-in del huésped?')) {
            fetch(`/reservas/${id}/checkin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) location.reload();
                  else alert('Error: ' + data.message);
              });
        }
    }
    
    function confirmCheckOut(id) {
        if (confirm('¿Realizar Check-out? Se generará la factura final')) {
            fetch(`/reservas/${id}/checkout`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.href = `/comprobantes/create/${id}`;
                  } else alert('Error: ' + data.message);
              });
        }
    }
</script>
@endpush
@endsection