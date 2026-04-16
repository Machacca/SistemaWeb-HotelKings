@extends('layouts.app')

@section('title', 'Editar Reserva - Hotel Kings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Reserva #{{ $reserva->IdReserva }}</h1>
        <p class="text-gray-600 mt-1">Modificar los datos de la reserva</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('reservas.update', $reserva) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Huésped -->
                <div>
                    <label for="IdHuesped" class="block text-sm font-medium text-gray-700 mb-2">
                        Huésped *
                    </label>
                    <select name="IdHuesped" id="IdHuesped" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar huésped</option>
                        @foreach($huespedes as $huesped)
                        <option value="{{ $huesped->IdHuesped }}"
                                {{ $reserva->IdHuesped == $huesped->IdHuesped ? 'selected' : '' }}>
                            {{ $huesped->Nombre }} {{ $huesped->Apellido }} - {{ $huesped->Documento }}
                        </option>
                        @endforeach
                    </select>
                    @error('IdHuesped')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Habitación -->
                <div>
                    <label for="IdHabitacion" class="block text-sm font-medium text-gray-700 mb-2">
                        Habitación *
                    </label>
                    <select name="IdHabitacion" id="IdHabitacion" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar habitación</option>
                        @foreach($habitaciones as $habitacion)
                        <option value="{{ $habitacion->IdHabitacion }}"
                                {{ $reserva->detalles->first()->IdHabitacion ?? '' == $habitacion->IdHabitacion ? 'selected' : '' }}>
                            Hab {{ $habitacion->Numero }} - {{ $habitacion->tipo->Nombre ?? 'N/A' }} - Piso {{ $habitacion->Piso }}
                        </option>
                        @endforeach
                    </select>
                    @error('IdHabitacion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de entrada -->
                <div>
                    <label for="FechaEntrada" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Entrada *
                    </label>
                    <input type="date" name="FechaEntrada" id="FechaEntrada" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('FechaEntrada', \Carbon\Carbon::parse($reserva->FechaEntrada)->format('Y-m-d')) }}">
                    @error('FechaEntrada')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de salida -->
                <div>
                    <label for="FechaSalida" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Salida *
                    </label>
                    <input type="date" name="FechaSalida" id="FechaSalida" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('FechaSalida', \Carbon\Carbon::parse($reserva->FechaSalida)->format('Y-m-d')) }}">
                    @error('FechaSalida')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Precio por noche -->
                <div>
                    <label for="PrecioNoche" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio por Noche *
                    </label>
                    <input type="number" name="PrecioNoche" id="PrecioNoche" step="0.01" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0.00" value="{{ old('PrecioNoche', $reserva->PrecioNoche) }}">
                    @error('PrecioNoche')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="Estado" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <select name="Estado" id="Estado"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Pendiente" {{ $reserva->Estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Confirmada" {{ $reserva->Estado == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="Cancelada" {{ $reserva->Estado == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('reservas.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
                    Actualizar Reserva
                </button>
            </div>
        </form>
    </div>
</div>
@endsection