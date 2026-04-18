@extends('layouts.app')

@section('title', 'Habitaciones')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-gray-900">Gestión de Habitaciones</h1>
            <p class="text-gray-500 mt-1">Administra las habitaciones, tipos y estados.</p>
        </div>
        <a href="{{ route('habitaciones.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gold hover:text-black transition-all duration-300 shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nueva Habitación
        </a>
    </div>

    <!-- Mensajes de Sesión -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg flex items-center" role="alert">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Buscar</label>
                <input type="text" name="buscar" placeholder="Número de habitación..." 
                       value="{{ request('buscar') }}"
                       class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Estado</label>
                <select name="estado" class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition">
                    <option value="">Todos</option>
                    <option value="1" {{ request('estado')=='1'?'selected':'' }}>Disponible</option>
                    <option value="2" {{ request('estado')=='2'?'selected':'' }}>Ocupada</option>
                    <option value="3" {{ request('estado')=='3'?'selected':'' }}>Mantenimiento</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Hotel</label>
                <select name="hotel" class="w-full border-gray-200 rounded-lg focus:ring-gold focus:border-gold transition">
                    <option value="">Todos</option>
                    @foreach($hoteles as $hotel)
                        <option value="{{ $hotel->IdHotel }}" {{ request('hotel')==$hotel->IdHotel?'selected':'' }}>
                            {{ $hotel->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="bg-gold text-black font-bold py-2 px-4 rounded-lg hover:bg-gold-dark transition-colors shadow-sm">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">N° Hab.</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Piso</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hotel</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($habitaciones as $hab)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900">{{ $hab->Numero }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $hab->tipo->Nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $hab->Piso }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $hab->hotel->Nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $estados = [
                                    1=>['Disponible', 'bg-green-100 text-green-700 border-green-200'], 
                                    2=>['Ocupada', 'bg-red-100 text-red-700 border-red-200'], 
                                    3=>['Mantenimiento', 'bg-yellow-100 text-yellow-700 border-yellow-200']
                                ];
                                $estado = $estados[$hab->IdEstadoHabitacion] ?? ['Desconocido', 'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $estado[1] }}">
                                {{ $estado[0] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('habitaciones.show', $hab) }}" 
                               class="inline-flex items-center text-gray-500 hover:text-gold transition-colors p-2 hover:bg-gray-100 rounded-lg" title="Ver">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('habitaciones.edit', $hab) }}" 
                               class="inline-flex items-center text-gray-500 hover:text-blue-600 transition-colors p-2 hover:bg-gray-100 rounded-lg" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form method="POST" action="{{ route('habitaciones.destroy', $hab) }}" class="inline" onsubmit="return confirm('¿Eliminar habitación {{$hab->Numero}}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-gray-500 hover:text-red-600 transition-colors p-2 hover:bg-gray-100 rounded-lg" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <p class="text-gray-500 font-medium">No hay habitaciones registradas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $habitaciones->withQueryString()->links() }}
    </div>
</div>
@endsection