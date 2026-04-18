{{-- resources/views/huespedes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4 flex justify-between items-center">
            <h2 class="text-white text-2xl font-bold">Gestión de Huéspedes</h2>
            <a href="{{ route('huespedes.create') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-gray-100">
                + Nuevo Huésped
            </a>
        </div>
        
        <div class="p-6">
            <!-- Buscador -->
            <div class="mb-6">
                <input type="text" id="search" placeholder="Buscar por nombre, apellido o documento..." 
                       class="w-full md:w-96 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>
            
            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre Completo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reservas</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($huespedes as $huesped)
                        <tr>
                            <td class="px-6 py-4">{{ $huesped->NombreCompleto }}</td>
                            <td class="px-6 py-4">{{ $huesped->TipoDocumento }}: {{ $huesped->NroDocumento }}</td>
                            <td class="px-6 py-4">{{ $huesped->Email ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $huesped->Telefono ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ $huesped->reservas_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('huespedes.show', $huesped->IdHuesped) }}" 
                                   class="text-blue-600 hover:text-blue-900">Ver</a>
                                <a href="{{ route('huespedes.edit', $huesped->IdHuesped) }}" 
                                   class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                <form action="{{ route('huespedes.destroy', $huesped->IdHuesped) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('¿Eliminar este huésped?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $huespedes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection