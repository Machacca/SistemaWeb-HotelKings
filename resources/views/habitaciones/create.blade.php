@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">➕ Nueva Habitación</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('habitaciones.store') }}" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Número de Habitación *</label>
            <input type="text" name="Numero" value="{{ old('Numero') }}" required
                   class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Piso *</label>
                <input type="number" name="Piso" value="{{ old('Piso') }}" min="1" required
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo *</label>
                <select name="IdTipo" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Seleccionar...</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->IdTipo }}" {{ old('IdTipo')==$tipo->IdTipo?'selected':'' }}>
                            {{ $tipo->Nombre }} - S/.{{ $tipo->Tarifa_base }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Hotel *</label>
            <select name="IdHotel" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                <option value="">Seleccionar...</option>
                @foreach($hoteles as $hotel)
                    <option value="{{ $hotel->IdHotel }}" {{ old('IdHotel')==$hotel->IdHotel?'selected':'' }}>
                        {{ $hotel->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-medium">
                Guardar Habitación
            </button>
            <a href="{{ route('habitaciones.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection