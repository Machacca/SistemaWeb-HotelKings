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

<nav class="side-menu flex-column">
    @php
        $user = auth()->user();
        $rol = $user->IdRol;
        $esMaster = $rol == 4;
        $esAdmin = $rol == 1;
        $esRecepcion = $rol == 2;
        $esCajero = $rol == 3;
    @endphp

    {{-- ============================================= --}}
    {{-- 1. DASHBOARD (Todos) --}}
    {{-- ============================================= --}}
    <a class="menu-item {{ Request::routeIs('dashboard') ? 'active-menu' : '' }}" href="{{ route('dashboard') }}">
        <div class="icon-box"><i class="fas fa-th-large"></i></div>
        <span>Dashboard</span>
    </a>

    {{-- ============================================= --}}
    {{-- 2. HABITACIONES (CRUD de habitaciones - Master y Admin) --}}
    {{-- ============================================= --}}
    @if($esMaster || $esAdmin)
    <a class="menu-item {{ Request::routeIs('habitaciones.*') ? 'active-menu' : '' }}" href="{{ route('habitaciones.index') }}">
        <div class="icon-box"><i class="fas fa-bed"></i></div>
        <span>Habitaciones</span>
    </a>
    @endif

    {{-- ============================================= --}}
    {{-- 3. HUÉSPEDES EN CASA (CRUD de Reservas - Master, Admin, Recepción) --}}
    {{-- ============================================= --}}
    @if($esMaster || $esAdmin || $esRecepcion)
    <a class="menu-item {{ Request::routeIs('reservas.*') ? 'active-menu' : '' }}" href="{{ route('reservas.index') }}">
        <div class="icon-box"><i class="fas fa-user-friends"></i></div>
        <span>Huéspedes en Casa</span>
    </a>
    @endif

    {{-- ============================================= --}}
    {{-- 4. RESERVAS (Calendario - Master, Admin, Recepción) --}}
    {{-- ============================================= --}}
    @if($esMaster || $esAdmin || $esRecepcion)
    <a class="menu-item {{ Request::routeIs('calendario.*') ? 'active-menu' : '' }}" href="{{ route('calendario.index') }}">
        <div class="icon-box"><i class="fas fa-calendar-alt"></i></div>
        <span>Calendario</span>
    </a>
    @endif

    {{-- ============================================= --}}
    {{-- 5. HUÉSPEDES (CRUD - Todos) --}}
    {{-- ============================================= --}}
    <a class="menu-item {{ Request::routeIs('huespedes.*') ? 'active-menu' : '' }}" href="{{ route('huespedes.index') }}">
        <div class="icon-box"><i class="fas fa-users"></i></div>
        <span>Huéspedes</span>
    </a>

    {{-- ============================================= --}}
    {{-- 6. PRODUCTOS / SERVICIOS (Master, Admin, Cajero) --}}
    {{-- ============================================= --}}
    @if($esMaster || $esAdmin || $esCajero)
    <a class="menu-item {{ Request::routeIs('productos.*') ? 'active-menu' : '' }}" href="{{ route('productos.index') }}">
        <div class="icon-box"><i class="fas fa-boxes"></i></div>
        <span>Productos / Servicios</span>
    </a>
    @endif

    {{-- ============================================= --}}
    {{-- 7. FACTURACIÓN / CAJA (Master, Admin, Cajero) --}}
    {{-- ============================================= --}}
    @if($esMaster || $esAdmin || $esCajero)
    <a class="menu-item {{ Request::routeIs('caja.*') ? 'active-menu' : '' }}" href="{{ route('caja.index') }}">
        <div class="icon-box"><i class="fas fa-file-invoice-dollar"></i></div>
        <span>Facturación / Caja</span>
    </a>
    @endif

    {{-- ============================================= --}}
    {{-- 8. CONFIGURACIÓN (Master y Admin) --}}
    {{-- ============================================= --}}
    @if($esMaster || $esAdmin)
    <div class="menu-group">
        <a class="menu-item {{ Request::routeIs('hoteles.*', 'usuarios.*', 'tiposhabitacion.*', 'empleados.*', 'canales-reserva.*', 'formas-pago.*') ? 'active-menu' : '' }}" 
            data-toggle="collapse" href="#collapseConfiguracion" role="button"
            aria-expanded="{{ Request::routeIs('hoteles.*', 'usuarios.*', 'tiposhabitacion.*', 'empleados.*', 'canales-reserva.*', 'formas-pago.*') ? 'true' : 'false' }}">
            <div class="icon-box"><i class="fas fa-cog"></i></div>
            <span>Configuración</span>
            <i class="fas fa-chevron-down ml-auto arrow-icon"></i>
        </a>
        <div class="collapse {{ Request::routeIs('hoteles.*', 'usuarios.*', 'tiposhabitacion.*', 'empleados.*', 'canales-reserva.*', 'formas-pago.*') ? 'show' : '' }}" id="collapseConfiguracion">
            
            {{-- Gestión de Hoteles --}}
            <a href="{{ route('hoteles.index') }}" class="sub-menu-item {{ Request::routeIs('hoteles.*') ? 'active-sub' : '' }}">
                <i class="fas fa-building mr-2"></i> Hoteles
            </a>
            
            {{-- Gestión de Usuarios (solo los que tienen login) --}}
            <a href="{{ route('usuarios.index') }}" class="sub-menu-item {{ Request::routeIs('usuarios.*') ? 'active-sub' : '' }}">
                <i class="fas fa-user-shield mr-2"></i> Usuarios
            </a>
            
            {{-- Gestión de Empleados (todos los trabajadores) --}}
            <a href="{{ route('empleados.index') }}" class="sub-menu-item {{ Request::routeIs('empleados.*') ? 'active-sub' : '' }}">
                <i class="fas fa-users mr-2"></i> Empleados
            </a>
            
            {{-- Tipos de Habitación --}}
            <a href="{{ route('tiposhabitacion.index') }}" class="sub-menu-item {{ Request::routeIs('tiposhabitacion.*') ? 'active-sub' : '' }}">
                <i class="fas fa-bed mr-2"></i> Tipos de Habitación
            </a>
            
            {{-- Canales de Reserva --}}
            <a href="{{ route('canales-reserva.index') }}" class="sub-menu-item {{ Request::routeIs('canales-reserva.*') ? 'active-sub' : '' }}">
                <i class="fas fa-globe mr-2"></i> Canales de Reserva
            </a>
            
            {{-- Formas de Pago --}}
            <a href="{{ route('formas-pago.index') }}" class="sub-menu-item {{ Request::routeIs('formas-pago.*') ? 'active-sub' : '' }}">
                <i class="fas fa-credit-card mr-2"></i> Formas de Pago
            </a>
            
        </div>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- 9. MI PERFIL (Todos los roles) --}}
    {{-- ============================================= --}}
    <a class="menu-item {{ Request::routeIs('perfil.*') ? 'active-menu' : '' }}" href="{{ route('perfil.index') }}">
        <div class="icon-box"><i class="fas fa-user-circle"></i></div>
        <span>Mi Perfil</span>
    </a>

</nav>

<style>
    .side-menu {
        overflow-y: auto;
        max-height: calc(100vh - 120px);
        padding: 0 15px;
    }

    .sub-menu-item {
        display: block;
        padding: 10px 15px 10px 55px;
        color: #888 !important;
        font-size: 0.82rem;
        text-decoration: none !important;
        transition: all 0.3s;
        border-radius: 8px;
        margin-bottom: 2px;
    }

    .sub-menu-item:hover {
        color: #d4af37 !important;
        background: rgba(212, 175, 55, 0.05);
    }
    
    .active-sub {
        color: #d4af37 !important;
        background: rgba(212, 175, 55, 0.08) !important;
    }

    .arrow-icon {
        font-size: 0.7rem;
        transition: transform 0.3s;
        color: #555;
    }

    .menu-item[aria-expanded="true"] .arrow-icon {
        transform: rotate(180deg);
    }

    .side-menu::-webkit-scrollbar { width: 4px; }
    .side-menu::-webkit-scrollbar-thumb {
        background: rgba(212, 175, 55, 0.1);
        border-radius: 10px;
    }
    
    .menu-item {
        color: #888 !important;
        padding: 12px 16px !important;
        border-radius: 10px;
        margin-bottom: 4px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        text-decoration: none !important;
        position: relative;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .menu-item:hover {
        color: #fff !important;
        background: rgba(255, 255, 255, 0.05);
    }

    .active-menu {
        color: #d4af37 !important;
        background: rgba(212, 175, 55, 0.1) !important;
    }

    .active-menu::before {
        content: "";
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 3px;
        background: #d4af37;
        border-radius: 0 3px 3px 0;
    }
    
    .icon-box {
        width: 28px;
        margin-right: 12px;
        text-align: center;
        font-size: 1rem;
    }
</style>

<script>
// Mantener el submenú abierto si tiene un item activo
document.addEventListener('DOMContentLoaded', function() {
    const activeSub = document.querySelector('.active-sub');
    if (activeSub) {
        const collapse = activeSub.closest('.collapse');
        if (collapse) {
            collapse.classList.add('show');
            const trigger = document.querySelector(`[href="#${collapse.id}"]`);
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'true');
            }
        }
    }
});
</script>