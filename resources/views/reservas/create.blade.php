@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-gray-900">Registrar Nueva Reserva</h1>
            <p class="text-gray-500 mt-1">Complete los datos del huesped y los detalles de la estadía.</p>
        </div>
        <a href="{{ route('reservas.index') }}" class="text-gray-500 hover:text-gold transition-colors flex items-center gap-2 text-sm font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            
            <!-- COLUMNA IZQUIERDA: HUÉSPEDES (Excel-like) -->
            <div class="lg:col-span-3 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Selector de Huésped Rápido -->
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            1. Buscar Huésped Existente (Titular)
                        </label>
                        <select name="IdHuesped" id="selectHuespedExistente" class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition bg-white">
                            <option value="">-- Buscar por nombre o documento --</option>
                            @foreach($huespedes as $huesped)
                                <option value="{{ $huesped->IdHuesped }}">
                                    {{ $huesped->Nombre }} {{ $huesped->Apellido }} ({{ $huesped->NroDocumento }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-2">Si selecciona uno, se omitirá el registro manual del titular.</p>
                    </div>

                    <!-- Tabla Tipo Excel para Huéspedes -->
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Lista de Huéspedes
                            </h3>
                            <button type="button" id="btnAgregarFila" class="text-xs font-bold text-gold hover:text-black transition-colors border border-gold rounded px-3 py-1 hover:bg-gold">
                                + Agregar Fila
                            </button>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm" id="tablaHuespedes">
                                <thead class="bg-black text-white text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3 text-left w-24">Tipo</th>
                                        <th class="px-4 py-3 text-left">Nombre</th>
                                        <th class="px-4 py-3 text-left">Apellido</th>
                                        <th class="px-4 py-3 text-left w-28">T. Doc</th>
                                        <th class="px-4 py-3 text-left">N° Documento</th>
                                        <th class="px-4 py-3 text-center w-16">#</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyHuespedes">
                                    <!-- Fila Titular (Inicialmente oculta si se busca existente) -->
                                    <tr class="bg-gold/5 border-b border-gray-200 hover:bg-gold/10 transition-colors" id="filaTitular">
                                        <td class="px-4 py-2">
                                            <span class="bg-gold text-black text-xs font-bold px-2 py-1 rounded">Titular</span>
                                        </td>
                                        <td class="px-2 py-2"><input type="text" name="huesped_titular[Nombre]" placeholder="Nombre" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1"></td>
                                        <td class="px-2 py-2"><input type="text" name="huesped_titular[Apellido]" placeholder="Apellido" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1"></td>
                                        <td class="px-2 py-2">
                                            <select name="huesped_titular[TipoDocumento]" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1">
                                                <option value="DNI">DNI</option>
                                                <option value="CE">CE</option>
                                                <option value="Pasaporte">PAS</option>
                                            </select>
                                        </td>
                                        <td class="px-2 py-2"><input type="text" name="huesped_titular[NroDocumento]" placeholder="N° Doc" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1"></td>
                                        <td class="px-4 py-2 text-center text-gray-300">-</td>
                                    </tr>
                                    
                                    <!-- Acompañantes se agregarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">* Si seleccionó un huésped existente arriba, complete esta tabla solo para los acompañantes adicionales.</p>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: RESERVA Y RESUMEN -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Detalles de la Reserva -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-brand-black p-5">
                        <h3 class="font-serif text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Datos de la Estadía
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Habitación -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Habitación</label>
                            <select name="IdHabitacion" id="selectHabitacion" required class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition">
                                <option value="">Seleccionar...</option>
                                @foreach($habitaciones as $habitacion)
                                    <option value="{{ $habitacion->IdHabitacion }}" data-precio="{{ $habitacion->PrecioBase ?? 100 }}">
                                        Hab. {{ $habitacion->Numero }} - {{ $habitacion->tipo->Nombre ?? 'Std' }} (S/ {{ number_format($habitacion->PrecioBase ?? 100, 0) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Check-in -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Check-in</label>
                                <input type="date" name="FechaCheckIn" id="FechaCheckIn" required class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition">
                            </div>
                            <!-- Check-out -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Check-out</label>
                                <input type="date" name="FechaCheckOut" id="FechaCheckOut" required class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition">
                            </div>
                        </div>

                        <!-- Precio -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Precio por Noche</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">S/</span>
                                <input type="number" name="PrecioNoche" id="PrecioNoche" step="0.01" required class="w-full pl-10 pr-4 py-2 border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen Financiero -->
                <div class="bg-gradient-to-br from-brand-black to-gray-900 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="font-bold text-gray-300 mb-4 uppercase text-xs tracking-widest">Resumen de Reserva</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Cantidad de Noches:</span>
                            <span id="noches" class="font-bold text-lg">0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Tarifa por Noche:</span>
                            <span id="tarifa" class="font-bold">S/ 0.00</span>
                        </div>
                        <div class="border-t border-gray-700 pt-3 mt-3">
                            <div class="flex justify-between items-end">
                                <span class="text-gray-300 font-medium">Total Estimado:</span>
                                <span id="total" class="text-3xl font-bold text-gold">S/ 0.00</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gold hover:bg-yellow-500 text-black font-bold rounded-lg uppercase tracking-widest text-sm transition-all duration-300 shadow-lg hover:shadow-gold/30 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Confirmar Reserva
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Elementos DOM ---
        const selectExistente = document.getElementById('selectHuespedExistente');
        const filaTitular = document.getElementById('filaTitular');
        const tablaBody = document.getElementById('tbodyHuespedes');
        const btnAgregarFila = document.getElementById('btnAgregarFila');
        const form = document.getElementById('reservaForm');

        // --- Lógica Huéspedes (Excel-like) ---
        
        // 1. Toggle para el titular manual
        selectExistente.addEventListener('change', function() {
            if (this.value) {
                // Si elige existente, ocultar inputs del titular
                filaTitular.style.display = 'none';
                // Deshabilitar inputs para que no se envíen
                filaTitular.querySelectorAll('input, select').forEach(el => el.disabled = true);
            } else {
                // Si no elige nada, mostrar inputs para registrar nuevo titular
                filaTitular.style.display = '';
                filaTitular.querySelectorAll('input, select').forEach(el => el.disabled = false);
            }
        });
        // Trigger inicial
        selectExistente.dispatchEvent(new Event('change'));

        // 2. Agregar filas de acompañantes
        btnAgregarFila.addEventListener('click', function() {
            const rowCount = tablaBody.querySelectorAll('tr').length;
            const newRow = document.createElement('tr');
            newRow.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';
            newRow.innerHTML = `
                <td class="px-4 py-2">
                    <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-1 rounded">Acomp.</span>
                </td>
                <td class="px-2 py-2"><input type="text" name="acompanantes[][Nombre]" placeholder="Nombre" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1 border border-transparent focus:border-gold"></td>
                <td class="px-2 py-2"><input type="text" name="acompanantes[][Apellido]" placeholder="Apellido" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1 border border-transparent focus:border-gold"></td>
                <td class="px-2 py-2">
                    <select name="acompanantes[][TipoDocumento]" class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1 border border-transparent focus:border-gold">
                        <option value="DNI">DNI</option>
                        <option value="CE">CE</option>
                        <option value="Pasaporte">PAS</option>
                    </select>
                </td>
                <td class="px-2 py-2"><input type="text" name="acompanantes[][NroDocumento]" placeholder="N° Doc" required  class="w-full bg-transparent focus:outline-none focus:ring-1 focus:ring-gold rounded px-1 py-1 border border-transparent focus:border-gold"></td>
                <td class="px-4 py-2 text-center">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </td>
            `;
            tablaBody.appendChild(newRow);
        });

        // --- Lógica de Reserva y Cálculos ---

        const selectHabitacion = document.getElementById('selectHabitacion');
        const inputPrecio = document.getElementById('PrecioNoche');
        const inputCheckIn = document.getElementById('FechaCheckIn');
        const inputCheckOut = document.getElementById('FechaCheckOut');

        // Auto-llenar precio
        selectHabitacion.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const precio = option.getAttribute('data-precio');
            if (precio) {
                inputPrecio.value = precio;
                calcularTotales();
            }
        });

        // Calcular totales
        function calcularTotales() {
            const inicio = inputCheckIn.value;
            const fin = inputCheckOut.value;
            const precio = parseFloat(inputPrecio.value) || 0;

            if (inicio && fin && precio > 0) {
                const date1 = new Date(inicio);
                const date2 = new Date(fin);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 0) {
                    const total = diffDays * precio;
                    document.getElementById('noches').textContent = diffDays;
                    document.getElementById('tarifa').textContent = `S/ ${precio.toFixed(2)}`;
                    document.getElementById('total').textContent = `S/ ${total.toFixed(2)}`;
                }
            }
        }

        [inputCheckIn, inputCheckOut, inputPrecio].forEach(el => {
            el.addEventListener('change', calcularTotales);
            el.addEventListener('input', calcularTotales);
        });
        
        // Fecha mínima hoy
        const hoy = new Date().toISOString().split('T')[0];
        inputCheckIn.setAttribute('min', hoy);
        inputCheckIn.addEventListener('change', function() {
            inputCheckOut.setAttribute('min', this.value);
        });

    });
</script>
@endpush