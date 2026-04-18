{{-- resources/views/reservas/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 px-6 py-4">
                <h2 class="text-white text-2xl font-bold">Editar Reserva #{{ $reserva->IdReserva }}</h2>
                <p class="text-yellow-200 text-sm mt-1">Última modificación: {{ $reserva->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
            
            <form action="{{ route('reservas.update', $reserva->IdReserva) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6">
                    @if(session('warning'))
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                            <p>{{ session('warning') }}</p>
                        </div>
                    @endif
                    
                    <!-- Información del Huésped -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Huésped</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="font-medium">{{ $reserva->huesped->NombreCompleto }}</p>
                            <p class="text-sm text-gray-600">Documento: {{ $reserva->huesped->TipoDocumento }}: {{ $reserva->huesped->NroDocumento }}</p>
                            <p class="text-sm text-gray-600">Email: {{ $reserva->huesped->Email ?? 'No registrado' }}</p>
                            <p class="text-sm text-gray-600">Teléfono: {{ $reserva->huesped->Telefono ?? 'No registrado' }}</p>
                        </div>
                        <small class="text-gray-500 text-xs">El huésped no se puede modificar en una reserva existente</small>
                    </div>
                    
                    <!-- Acompañantes -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-700">Acompañantes</label>
                            <button type="button" onclick="agregarAcompanante()" class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                + Agregar Acompañante
                            </button>
                        </div>
                        <div id="acompanantesContainer">
                            @if(isset($reserva->acompanantes) && $reserva->acompanantes->count() > 0)
                                @foreach($reserva->acompanantes as $index => $acompanante)
                                <div class="acompanante-item bg-gray-50 p-4 rounded-lg mb-3">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <input type="text" name="acompanantes[{{ $index }}][Nombre]" value="{{ $acompanante->Nombre }}" 
                                               placeholder="Nombre completo" class="rounded-lg border-gray-300">
                                        <input type="text" name="acompanantes[{{ $index }}][TipoDocumento]" value="{{ $acompanante->TipoDocumento }}" 
                                               placeholder="Tipo Documento" class="rounded-lg border-gray-300">
                                        <input type="text" name="acompanantes[{{ $index }}][NroDocumento]" value="{{ $acompanante->NroDocumento }}" 
                                               placeholder="N° Documento" class="rounded-lg border-gray-300">
                                    </div>
                                    <button type="button" onclick="eliminarAcompanante(this)" class="text-red-600 text-sm mt-2">Eliminar</button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <input type="hidden" id="acompananteCount" value="{{ isset($reserva->acompanantes) ? $reserva->acompanantes->count() : 0 }}">
                    </div>
                    
                    <!-- Detalles de la Reserva -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Detalles de la Reserva</h3>
                        
                        @foreach($reserva->detalles as $index => $detalle)
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-medium text-gray-700 mb-3">Habitación {{ $detalle->habitacion->Numero }}</h4>
                                @if(count($reserva->detalles) > 1)
                                <button type="button" onclick="eliminarHabitacion(this, {{ $detalle->IdDetalle }})" 
                                        class="text-red-600 text-sm">Eliminar</button>
                                @endif
                            </div>
                            <input type="hidden" name="detalles[{{ $index }}][IdDetalle]" value="{{ $detalle->IdDetalle }}">
                            <input type="hidden" name="detalles[{{ $index }}][IdHabitacion]" value="{{ $detalle->IdHabitacion }}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Entrada</label>
                                    <input type="date" name="detalles[{{ $index }}][FechaEntrada]" 
                                           value="{{ $detalle->FechaEntrada instanceof \Carbon\Carbon ? $detalle->FechaEntrada->format('Y-m-d') : date('Y-m-d', strtotime($detalle->FechaEntrada)) }}"
                                           class="w-full rounded-lg border-gray-300 fecha-entrada" 
                                           data-index="{{ $index }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Salida</label>
                                    <input type="date" name="detalles[{{ $index }}][FechaSalida]" 
                                           value="{{ $detalle->FechaSalida instanceof \Carbon\Carbon ? $detalle->FechaSalida->format('Y-m-d') : date('Y-m-d', strtotime($detalle->FechaSalida)) }}"
                                           class="w-full rounded-lg border-gray-300 fecha-salida" 
                                           data-index="{{ $index }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio por Noche (S/)</label>
                                    <input type="number" name="detalles[{{ $index }}][PrecioNoche]" 
                                           value="{{ $detalle->PrecioNoche }}" step="0.01"
                                           class="w-full rounded-lg border-gray-300 precio-noche" 
                                           data-index="{{ $index }}" required>
                                </div>
                            </div>
                            <div class="mt-2 text-right">
                                <span class="text-sm text-gray-600">Subtotal: S/ <span class="subtotal-{{ $index }}">0.00</span></span>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Botón para agregar más habitaciones (solo si la reserva está activa) -->
                        @if($reserva->Estado == 'Activa')
                        <button type="button" onclick="agregarHabitacion()" class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200">
                            + Agregar otra habitación
                        </button>
                        @endif
                    </div>
                    
                    <!-- Observaciones -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones / Notas</label>
                        <textarea name="Observaciones" rows="3" class="w-full rounded-lg border-gray-300" 
                                  placeholder="Notas adicionales sobre la reserva...">{{ $reserva->Observaciones ?? '' }}</textarea>
                    </div>
                    
                    <!-- Resumen -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold">Total Alojamiento:</span>
                            <span id="totalAlojamiento" class="text-xl font-bold text-green-600">S/ 0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Estado:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($reserva->Estado == 'Activa') bg-green-100 text-green-800
                                @elseif($reserva->Estado == 'Check-in') bg-blue-100 text-blue-800
                                @elseif($reserva->Estado == 'Check-out') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $reserva->Estado }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <a href="{{ route('reservas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        Actualizar Reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let detalleCount = {{ count($reserva->detalles) }};
    
    function calcularSubtotal(index) {
        const entrada = document.querySelector(`.fecha-entrada[data-index="${index}"]`).value;
        const salida = document.querySelector(`.fecha-salida[data-index="${index}"]`).value;
        const precio = parseFloat(document.querySelector(`.precio-noche[data-index="${index}"]`).value);
        
        if (entrada && salida && precio) {
            const fechaInicio = new Date(entrada);
            const fechaFin = new Date(salida);
            const diffTime = Math.abs(fechaFin - fechaInicio);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                const subtotal = diffDays * precio;
                document.querySelector(`.subtotal-${index}`).innerText = subtotal.toFixed(2);
                calcularTotalGeneral();
                return subtotal;
            }
        }
        document.querySelector(`.subtotal-${index}`).innerText = '0.00';
        calcularTotalGeneral();
        return 0;
    }
    
    function calcularTotalGeneral() {
        let total = 0;
        for (let i = 0; i < detalleCount; i++) {
            const subtotal = parseFloat(document.querySelector(`.subtotal-${i}`)?.innerText || 0);
            total += subtotal;
        }
        document.getElementById('totalAlojamiento').innerText = `S/ ${total.toFixed(2)}`;
    }
    
    // Inicializar cálculos
    @for($i = 0; $i < count($reserva->detalles); $i++)
    calcularSubtotal({{ $i }});
    
    document.querySelector(`.fecha-entrada[data-index="{{ $i }}"]`).addEventListener('change', () => calcularSubtotal({{ $i }}));
    document.querySelector(`.fecha-salida[data-index="{{ $i }}"]`).addEventListener('change', () => calcularSubtotal({{ $i }}));
    document.querySelector(`.precio-noche[data-index="{{ $i }}"]`).addEventListener('input', () => calcularSubtotal({{ $i }}));
    @endfor
    
    function agregarHabitacion() {
        const container = document.querySelector('form .p-6 > div:has(h3)');
        const newIndex = detalleCount;
        
        const html = `
            <div class="bg-gray-50 p-4 rounded-lg mb-4" data-detalle-index="${newIndex}">
                <div class="flex justify-between items-start">
                    <h4 class="font-medium text-gray-700 mb-3">Nueva Habitación</h4>
                    <button type="button" onclick="eliminarHabitacion(this, null)" class="text-red-600 text-sm">Eliminar</button>
                </div>
                <input type="hidden" name="detalles[${newIndex}][IdDetalle]" value="">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Habitación</label>
                        <select name="detalles[${newIndex}][IdHabitacion]" class="w-full rounded-lg border-gray-300" required>
                            <option value="">Seleccionar habitación</option>
                            @foreach($habitaciones ?? [] as $habitacion)
                            <option value="{{ $habitacion->IdHabitacion }}">Hab. {{ $habitacion->Numero }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Entrada</label>
                        <input type="date" name="detalles[${newIndex}][FechaEntrada]" 
                               class="w-full rounded-lg border-gray-300 fecha-entrada" data-index="${newIndex}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Salida</label>
                        <input type="date" name="detalles[${newIndex}][FechaSalida]" 
                               class="w-full rounded-lg border-gray-300 fecha-salida" data-index="${newIndex}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Precio por Noche (S/)</label>
                        <input type="number" name="detalles[${newIndex}][PrecioNoche]" step="0.01"
                               class="w-full rounded-lg border-gray-300 precio-noche" data-index="${newIndex}" required>
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <span class="text-sm text-gray-600">Subtotal: S/ <span class="subtotal-${newIndex}">0.00</span></span>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        
        // Agregar event listeners
        document.querySelector(`.fecha-entrada[data-index="${newIndex}"]`).addEventListener('change', () => calcularSubtotal(newIndex));
        document.querySelector(`.fecha-salida[data-index="${newIndex}"]`).addEventListener('change', () => calcularSubtotal(newIndex));
        document.querySelector(`.precio-noche[data-index="${newIndex}"]`).addEventListener('input', () => calcularSubtotal(newIndex));
        
        detalleCount++;
    }
    
    function eliminarHabitacion(button, detalleId) {
        if (confirm('¿Eliminar esta habitación de la reserva?')) {
            const detalleDiv = button.closest('[data-detalle-index], .bg-gray-50');
            if (detalleId) {
                // Si tiene ID, agregar campo oculto para eliminarlo
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'eliminar_detalles[]';
                input.value = detalleId;
                document.querySelector('form').appendChild(input);
            }
            detalleDiv.remove();
            calcularTotalGeneral();
        }
    }
    
    // Acompañantes
    let acompananteCount = {{ isset($reserva->acompanantes) ? $reserva->acompanantes->count() : 0 }};
    
    function agregarAcompanante() {
        const container = document.getElementById('acompanantesContainer');
        const html = `
            <div class="acompanante-item bg-gray-50 p-4 rounded-lg mb-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="text" name="acompanantes[${acompananteCount}][Nombre]" 
                           placeholder="Nombre completo" class="rounded-lg border-gray-300">
                    <input type="text" name="acompanantes[${acompananteCount}][TipoDocumento]" 
                           placeholder="Tipo Documento" class="rounded-lg border-gray-300">
                    <input type="text" name="acompanantes[${acompananteCount}][NroDocumento]" 
                           placeholder="N° Documento" class="rounded-lg border-gray-300">
                </div>
                <button type="button" onclick="eliminarAcompanante(this)" class="text-red-600 text-sm mt-2">Eliminar</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        acompananteCount++;
    }
    
    function eliminarAcompanante(button) {
        button.closest('.acompanante-item').remove();
    }
</script>
@endpush
@endsection