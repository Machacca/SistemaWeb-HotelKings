<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hotel Kings')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js (Para interactividad del menu) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { DEFAULT: '#D4AF37', light: '#F4E4BC', dark: '#B8860B' },
                        brand: { black: '#0a0a0a', dark: '#1a1a1a' }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Animación suave para el sidebar */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        /* Estilo del scrollbar personalizado */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #B8860B; }
    </style>
</head>

<body class="font-sans bg-gray-50 text-gray-800 antialiased" x-data="{ sidebarOpen: false }">
    
    <!-- FONDO ANIMADO (Opcional: Heredado del login pero muy sutil) -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none opacity-30">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold rounded-full filter blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gold-light rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- OVERLAY MÓVIL -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- SIDEBAR -->
    <aside class="fixed top-0 left-0 z-30 h-full w-72 bg-brand-black sidebar-transition -translate-x-full lg:translate-x-0 shadow-2xl border-r border-white/10"
           :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
        
        <!-- Logo Area -->
        <div class="h-20 flex items-center justify-center border-b border-white/10 bg-black/50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-gold/10 border-2 border-gold flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform">
                    <span class="font-serif text-gold text-lg font-bold">HK</span>
                    <!-- O una imagen: <img src="{{ asset('img/logo.png') }}" class="w-full h-full object-cover"> -->
                </div>
                <span class="font-serif text-xl text-white tracking-wider">Hotel <span class="text-gold">Kings</span></span>
            </a>
        </div>

        <!-- Navegación -->
        <nav class="mt-6 px-4 space-y-1">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 rounded-lg transition-all duration-200
                      {{ request()->routeIs('dashboard') ? 'bg-gold/10 text-gold border-l-4 border-gold' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Habitaciones -->
            <a href="{{ route('habitaciones.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 rounded-lg transition-all duration-200
                      {{ request()->routeIs('habitaciones.*') ? 'bg-gold/10 text-gold border-l-4 border-gold' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Habitaciones</span>
            </a>

            <!-- Reservas -->
            <a href="{{ route('reservas.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 rounded-lg transition-all duration-200
                      {{ request()->routeIs('reservas.*') ? 'bg-gold/10 text-gold border-l-4 border-gold' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Reservas</span>
            </a>

            <!-- Huéspedes -->
            <a href="{{ route('huespedes.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 rounded-lg transition-all duration-200
                      {{ request()->routeIs('huespedes.*') ? 'bg-gold/10 text-gold border-l-4 border-gold' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Huéspedes</span>
            </a>
            
            <!-- Separador -->
            <div class="my-4 border-t border-white/10"></div>
            
            <!-- Configuración (Ejemplo) -->
             <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 rounded-lg transition-all duration-200 hover:bg-white/5 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-medium">Configuración</span>
            </a>
        </nav>

        <!-- User Profile Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-black/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gold/20 flex items-center justify-center text-gold font-bold">
                    {{ Str::substr(Auth::user()->Username ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->Username ?? 'Usuario' }}</p>
                    <p class="text-xs text-gray-500">Administrador</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-gray-500 hover:text-red-500 transition-colors" title="Cerrar sesión">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ÁREA PRINCIPAL -->
    <div class="lg:pl-72 min-h-screen flex flex-col relative z-10">
        
        <!-- TOP BAR (Mobile) -->
        <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md shadow-sm lg:hidden">
            <div class="flex items-center justify-between px-4 py-3">
                <button @click="sidebarOpen = true" class="p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="font-serif font-bold text-lg">Hotel <span class="text-gold">Kings</span></span>
                <div class="w-6"></div> <!-- Spacer -->
            </div>
        </header>

        <!-- TOP BAR (Desktop) -->
        <header class="hidden lg:block sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-gray-200">
             <div class="flex items-center justify-end px-8 py-4">
                 <!-- Breadcrumb o Título opcional -->
                 <div class="flex items-center gap-4">
                     <span class="text-sm text-gray-600">Bienvenido, <strong>{{ Auth::user()->Username }}</strong></span>
                 </div>
             </div>
        </header>

        <!-- CONTENIDO DINÁMICO -->
        <main class="flex-1 p-4 md:p-8">
            @yield('content')
        </main>
        
        <!-- Footer Opcional -->
        <footer class="py-4 text-center text-xs text-gray-400 border-t bg-white">
            © {{ date('Y') }} Hotel Kings. Todos los derechos reservados.
        </footer>
    </div>

    <!-- Scripts Adicionales -->
    @stack('scripts')
</body>
</html>