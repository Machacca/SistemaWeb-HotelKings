@extends('layouts.app')

@section('title', 'Editar Habitación')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-3xl font-bold text-gray-900">Editar Habitación</h1>
            <p class="text-gray-500 mt-1">Modifica los datos de la habitación #{{ $habitacion->Numero }}</p>
        </div>
        <a href="{{ route('habitaciones.index') }}" class="text-gray-500 hover:text-gold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('habitaciones.update', $habitacion) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Número -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Número de Habitación</label>
                    <input type="text" name="Numero" value="{{ old('Numero', $habitacion->Numero) }}"
                           class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition uppercase font-bold text-lg"
                           required>
                </div>

                <!-- Piso -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Piso</label>
                    <input type="number" name="Piso" value="{{ old('Piso', $habitacion->Piso) }}"
                           class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition"
                           required>
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipo de Habitación</label>
                    <select name="IdTipo" class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->IdTipo }}" {{ $habitacion->IdTipo == $tipo->IdTipo ? 'selected' : '' }}>
                                {{ $tipo->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hotel -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hotel</label>
                    <select name="IdHotel" class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition" required>
                        @foreach($hoteles as $hotel)
                            <option value="{{ $hotel->IdHotel }}" {{ $habitacion->IdHotel == $hotel->IdHotel ? 'selected' : '' }}>
                                {{ $hotel->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Estado (Solo en edición) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Estado Actual</label>
                    <select name="IdEstadoHabitacion" class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition" required>
                        @foreach($estados as $id => $nombre)
                            <option value="{{ $id }}" {{ $habitacion->IdEstadoHabitacion == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Footer del Formulario -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('habitaciones.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-black text-white font-semibold rounded-lg hover:bg-gold hover:text-black transition-all duration-300 shadow-sm">
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>
@endsection