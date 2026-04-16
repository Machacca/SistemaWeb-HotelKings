<div class="logo-container d-flex align-items-center">
    <img src="{{ asset('img/logo.jpeg') }}" alt="Logo Hotel" 
         class="rounded-circle mr-3 shadow-sm" 
         style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #d4af37;">
    
    <div>
        <h5 class="mb-0 text-white" style="font-family: 'Playfair Display', serif; font-size: 1.1rem; letter-spacing: 0.5px;">
            Inka Kings
        </h5>
        <small class="text-muted" style="letter-spacing: 1.5px; font-size: 0.6rem; font-weight: 700; text-transform: uppercase;">
            Staff Portal
        </small>
    </div>
</div>

<nav class="nav flex-column side-menu">
    @php
        $nav = [
            ['dashboard', 'fa-th-large', 'Dashboard'],
            ['habitaciones.index', 'fa-bed', 'Habitaciones'],
            ['reservas.index', 'fa-calendar-alt', 'Reservas'],
            ['huespedes.index', 'fa-users', 'Huéspedes'],
            ['facturacion', 'fa-file-invoice-dollar', 'Facturación'],
            ['configuracion', 'fa-cog', 'Configuración'],
        ];
    @endphp

    @foreach($nav as $item)
        <a class="menu-item {{ Request::routeIs($item[0]) ? 'active-menu' : '' }}" 
           href="{{ Route::has($item[0]) ? route($item[0]) : '#' }}">
            <div class="icon-box">
                <i class="fas {{ $item[1] }}"></i>
            </div>
            <span>{{ $item[2] }}</span>
        </a>
    @endforeach
</nav>