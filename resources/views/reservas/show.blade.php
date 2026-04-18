{{-- resources/views/reservas/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-white text-2xl font-bold">Reserva #{{ $reserva->IdReserva }}</h2>
                    <p class="text-purple-200 mt-1">Creada: {{ $reserva->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('reservas.index') }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-gray-100">
                        ← Volver
                    </a>
                    @if($reserva->Estado == 'Activa')
                    <button onclick="confirmCheckIn()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        Realizar Check-in
                    </button>
                    @endif
                    @if($reserva->Estado == 'Check-in')
                    <button onclick="confirmCheckOut()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                        Realizar Check-out
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <!-- Información del Huésped -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Información del Huésped</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nombre Completo</p>
                            <p class="font-medium">{{ $reserva->huesped->NombreCompleto ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Documento</p>
                            <p class="font-medium">{{ $reserva->huesped->TipoDocumento ?? '' }}: {{ $reserva->huesped->NroDocumento ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">{{ $reserva->huesped->Email ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Teléfono</p>
                            <p class="font-medium">{{ $reserva->huesped->Telefono ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nacionalidad</p>
                            <p class="font-medium">{{ $reserva->huesped->Nacionalidad ?? 'No registrada' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Detalle de la Reserva -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Detalle de la Reserva</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Habitación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Entrada</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Salida</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Noches</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio/Noche</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reserva->detalles as $detalle)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Habitación {{ $detalle->habitacion->Numero ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $detalle->FechaCheckIn->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $detalle->FechaCheckOut->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $detalle->FechaCheckIn->diffInDays($detalle->FechaCheckOut) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        S/ {{ number_format($detalle->PrecioNoche, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                        S/ {{ number_format($detalle->PrecioNoche * $detalle->FechaCheckIn->diffInDays($detalle->FechaCheckOut), 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-right font-bold">Total Alojamiento:</td>
                                    <td class="px-6 py-4 text-right font-bold">
                                        S/ {{ number_format($reserva->detalles->sum(function($d) {
                                            return $d->PrecioNoche * $d->FechaCheckIn->diffInDays($d->FechaCheckOut);
                                        }), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Consumos (Si existen) -->
                @if(isset($reserva->consumos) && $reserva->consumos->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Consumos del Huésped</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reserva->consumos as $consumo)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $consumo->producto->Nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">{{ $consumo->Cantidad }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">S/ {{ number_format($consumo->PrecioUnitario, 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-right">S/ {{ number_format($consumo->Total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold">Total Consumos:</td>
                                    <td class="px-6 py-4 text-right font-bold">
                                        S/ {{ number_format($reserva->consumos->sum('Total'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif
                
                <!-- Resumen Final -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold text-gray-700">Estado de la Reserva:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            @if($reserva->Estado == 'Activa') bg-green-100 text-green-800
                            @elseif($reserva->Estado == 'Check-in') bg-blue-100 text-blue-800
                            @elseif($reserva->Estado == 'Check-out') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $reserva->Estado }}
                        </span>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center text-xl font-bold text-gray-900">
                            <span>TOTAL GENERAL:</span>
                            <span>S/ {{ number_format($reserva->TotalReserva, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmCheckIn() {
        if (confirm('¿Confirmar Check-in del huésped?')) {
            fetch('{{ route("reservas.checkin", $reserva->IdReserva) }}', {
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
    
    function confirmCheckOut() {
        if (confirm('¿Realizar Check-out? Se generará la factura final')) {
            fetch('{{ route("reservas.checkout", $reserva->IdReserva) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.href = '{{ route("comprobantes.create", $reserva->IdReserva) }}';
                  } else alert('Error: ' + data.message);
              });
        }
    }
</script>
@endpush
@endsection