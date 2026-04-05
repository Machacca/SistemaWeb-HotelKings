@extends('layouts.app') {{-- Crea este layout base si no lo tienes --}}

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🛏️ Habitaciones</h1>
            <p class="text-gray-600">Gestión de habitaciones y estados</p>
        </div>
        <a href="{{ route('habitaciones.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
            + Nueva Habitación
        </a>
    </div>

    <!-- Filtros -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="buscar" placeholder="Buscar por número..." 
               value="{{ request('buscar') }}"
               class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
        
        <select name="estado" class="border border-gray-300 rounded px-3 py-2">
            <option value="">Todos los estados</option>
            <option value="1" {{ request('estado')=='1'?'selected':'' }}>Disponible</option>
            <option value="2" {{ request('estado')=='2'?'selected':'' }}>Ocupada</option>
            <option value="3" {{ request('estado')=='3'?'selected':'' }}>Mantenimiento</option>
        </select>
        
        <select name="hotel" class="border border-gray-300 rounded px-3 py-2">
            <option value="">Todos los hoteles</option>
            @foreach($hoteles as $hotel)
                <option value="{{ $hotel->IdHotel }}" {{ request('hotel')==$hotel->IdHotel?'selected':'' }}>
                    {{ $hotel->Nombre }}
                </option>
            @endforeach
        </select>
        
        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Filtrar
        </button>
    </form>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Piso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hotel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($habitaciones as $hab)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $hab->Numero }}</td>
                    <td class="px-6 py-4">{{ $hab->tipo->Nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $hab->Piso }}</td>
                    <td class="px-6 py-4">{{ $hab->hotel->Nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $estados = [1=>['Disponible','bg-green-100 text-green-800'], 
                                       2=>['Ocupada','bg-red-100 text-red-800'], 
                                       3=>['Mantenimiento','bg-yellow-100 text-yellow-800']];
                            $estado = $estados[$hab->IdEstadoHabitacion] ?? ['Desconocido','bg-gray-100'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $estado[1] }}">
                            {{ $estado[0] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('habitaciones.show', $hab) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                        <a href="{{ route('habitaciones.edit', $hab) }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-sm">Editar</a>
                        <form method="POST" action="{{ route('habitaciones.destroy', $hab) }}" class="inline"
                              onsubmit="return confirm('¿Eliminar habitación {{$hab->Numero}}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No hay habitaciones registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $habitaciones->withQueryString()->links() }}
    </div>
</div>
@endsection