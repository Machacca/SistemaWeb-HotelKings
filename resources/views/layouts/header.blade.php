

<header class="bg-white p-3 shadow-sm d-flex justify-content-between align-items-center px-4">
    <div style="width: 300px;">
        <div class="input-group bg-light rounded-pill px-3 py-1">
            <i class="fas fa-search align-self-center text-muted"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Buscar reserva...">
        </div>
    </div>

    <div class="d-flex align-items-center">
        <i class="far fa-bell mr-4 fa-lg text-muted"></i>
        
        <div class="mr-4">
            @if(auth()->user()->IdRol == 4)
                <form action="{{ route('contexto.hotel') }}" method="POST" id="form-hotel-context">
                    @csrf
                    {{--<select name="IdHotel" class="form-control form-control-sm border-luxury shadow-sm" 
                            style="border-radius: 20px; color: #d4af37; font-weight: bold;"
                            onchange="document.getElementById('form-hotel-context').submit();">
                        
                        {{-- Si el hotel_id en sesión es null, significa "Todos" --}}
                        {{-- <option value="" {{ is_null(session('hotel_id')) ? 'selected' : '' }}>
                            🌍 TODOS LOS HOTELES
                        </option>

                        @foreach(\App\Models\Hotel::all() as $hotel)
                            <option value="{{ $hotel->IdHotel }}" {{ session('hotel_id') == $hotel->IdHotel ? 'selected' : '' }}>
                                🏨 {{ $hotel->Nombre }}
                            </option>
                        @endforeach
                    </select> --}}
                </form>
            @else
                {{-- Muestra el nombre del hotel guardado en la sesión al hacer login --}}
                <span class="badge badge-pill p-2 px-3 shadow-sm" style="background-color: #fdfaf0; color: #d4af37; border: 1px solid #d4af37; font-size: 0.85rem;">
                    <i class="fas fa-hotel mr-2"></i>
                    {{ session('hotel_nombre') }}
                </span>
            @endif
        </div>
        
        <div class="d-flex align-items-center border-left pl-3">
            <div class="text-right mr-3">
                <span class="d-block font-weight-bold" style="font-size: 0.85rem; color: #333;">
                    {{ auth()->user()->Username }}
                </span>
                <small class="text-success" style="font-size: 0.7rem;">● En línea</small>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm border-0" title="Cerrar Sesión">
                    <i class="fas fa-power-off fa-lg"></i>
                </button>
            </form>
        </div>
    </div>
</header>