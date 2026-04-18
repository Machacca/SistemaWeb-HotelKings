@extends('layouts.app')

@section('title', 'Detalle Habitación')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="font-serif text-3xl font-bold text-gray-900">Habitación {{ $habitacion->Numero }}</h1>
                <!-- Badge de Estado Grande -->
                @php
                    $estados = [
                        1=>['Disponible', 'bg-green-100 text-green-700 border-green-200'], 
                        2=>['Ocupada', 'bg-red-100 text-red-700 border-red-200'], 
                        3=>['Mantenimiento', 'bg-yellow-100 text-yellow-700 border-yellow-200']
                    ];
                    $estado = $estados[$habitacion->IdEstadoHabitacion] ?? ['Desconocido', 'bg-gray-100'];
                @endphp
                <span class="px-4 py-1 rounded-full text-sm font-semibold border {{ $estado[1] }}">
                    {{ $estado[0] }}
                </span>
            </div>
            <p class="text-gray-500 mt-1">Piso {{ $habitacion->Piso }} - {{ $habitacion->hotel->Nombre ?? 'Sin Asignar' }}</p>
        </div>
        <a href="{{ route('habitaciones.index') }}" class="text-gray-500 hover:text-gold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al listado
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Columna Izquierda: Info Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Ficha Técnica -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <h3 class="font-bold text-lg text-gray-800 mb-6 border-b pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Información General
                </h3>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tipo de Habitación</p>
                        <p class="text-lg text-gray-900 font-medium mt-1">{{ $habitacion->tipo->Nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Hotel</p>
                        <p class="text-lg text-gray-900 font-medium mt-1">{{ $habitacion->hotel->Nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Piso</p>
                        <p class="text-lg text-gray-900 font-medium mt-1">{{ $habitacion->Piso }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Número</p>
                        <p class="text-lg text-gray-900 font-medium mt-1">{{ $habitacion->Numero }}</p>
                    </div>
                </div>
            </div>

            <!-- Aquí podrías añadir un historial de reservas de esta habitación en el futuro -->
        </div>

        <!-- Columna Derecha: Acciones Rápidas -->
        <div class="space-y-6">
            
            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <a href="{{ route('habitaciones.edit', $habitacion) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-black text-white rounded-lg hover:bg-gold hover:text-black transition font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar Datos
                    </a>

                    <form method="POST" action="{{ route('habitaciones.destroy', $habitacion) }}" onsubmit="return confirm('¿Estás seguro de eliminar esta habitación?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cambio Rápido de Estado (Housekeeping) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4">Cambiar Estado Rápido</h3>
                <p class="text-sm text-gray-500 mb-4">Actualiza el estado de la habitación para el personal de servicio.</p>
                
                <form method="POST" action="{{ route('habitaciones.updateEstado', $habitacion) }}">
                    @csrf
                    <div class="space-y-2">
                        <!-- Botón Disponible -->
                        <button type="submit" name="IdEstadoHabitacion" value="1" 
                                class="w-full px-4 py-2 rounded-lg border {{ $habitacion->IdEstadoHabitacion == 1 ? 'bg-green-100 border-green-400 text-green-800 ring-2 ring-green-300' : 'hover:bg-green-50 border-gray-200' }} transition text-sm font-medium text-left">
                            🟢 Disponible
                        </button>
                        
                        <!-- Botón Ocupada -->
                        <button type="submit" name="IdEstadoHabitacion" value="2" 
                                class="w-full px-4 py-2 rounded-lg border {{ $habitacion->IdEstadoHabitacion == 2 ? 'bg-red-100 border-red-400 text-red-800 ring-2 ring-red-300' : 'hover:bg-red-50 border-gray-200' }} transition text-sm font-medium text-left">
                            🔴 Ocupada
                        </button>

                        <!-- Botón Mantenimiento -->
                        <button type="submit" name="IdEstadoHabitacion" value="3" 
                                class="w-full px-4 py-2 rounded-lg border {{ $habitacion->IdEstadoHabitacion == 3 ? 'bg-yellow-100 border-yellow-400 text-yellow-800 ring-2 ring-yellow-300' : 'hover:bg-yellow-50 border-gray-200' }} transition text-sm font-medium text-left">
                            🛠️ Mantenimiento
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection