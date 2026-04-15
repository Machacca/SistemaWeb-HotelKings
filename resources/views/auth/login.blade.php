<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Inka Kings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fuentes Elegantes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Fondo base negro */
            background-color: #000000;
            overflow: hidden; /* Evita scroll por las animaciones del fondo */
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        /* --- ANIMACIONES DE FONDOS --- */
        
        /* Contenedor del fondo */
        .bg-animated-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Esferas de luz dorada difuminada */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
        }

        .orb-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #D4AF37, transparent);
            top: -200px;
            left: -200px;
            animation: moveOrb1 20s infinite alternate ease-in-out;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #B8860B, transparent);
            bottom: -150px;
            right: -150px;
            animation: moveOrb2 25s infinite alternate ease-in-out;
        }

        .orb-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #F4E4BC, transparent);
            top: 40%;
            left: 60%;
            opacity: 0.2;
            animation: moveOrb3 15s infinite alternate-reverse ease-in-out;
        }

        /* Keyframes para movimiento orgánico */
        @keyframes moveOrb1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 150px) scale(1.1); }
        }

        @keyframes moveOrb2 {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-80px, -100px) rotate(45deg); }
        }

        @keyframes moveOrb3 {
            0% { transform: translate(0, 0); opacity: 0.2; }
            100% { transform: translate(-50px, 50px); opacity: 0.3; }
        }

        /* --- ANIMACIONES DE UI --- */

        /* Animación de entrada de la tarjeta */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .animate-card {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Efecto de brillo dorado en el botón */
        .btn-gold {
            background: linear-gradient(45deg, #B8860B, #D4AF37, #F4E4BC, #D4AF37);
            background-size: 300% 300%;
            transition: all 0.5s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
        }

        .btn-gold:hover {
            background-position: 100% 0;
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }

        /* Inputs: Efecto de borde brillante al enfocar */
        .input-luxury {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background-color: #F9FAFB; /* gray-50 */
            box-shadow: 0 0 0 1px rgba(0,0,0,0.05);
        }

        .input-luxury:focus {
            border-color: #D4AF37;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    
    <!-- CAPA DE FONDO ANIMADO -->
    <div class="bg-animated-container">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <!-- Capa de ruido sutil para textura (opcional) -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]"></div>
    </div>

    <!-- TARJETA DE LOGIN -->
    <div class="relative bg-white/95 backdrop-blur-sm w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-card z-10 border border-white/10">
        
        <!-- Sección Superior (Negra) con Logo -->
        <div class="bg-black p-10 text-center relative overflow-hidden">
            <!-- Brillo sutil en la esquina superior -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#D4AF37] opacity-10 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
            
            <!-- Logo -->
            <div class="relative mx-auto w-28 h-28 rounded-full bg-gradient-to-br from-[#1a1a1a] to-black border-2 border-[#D4AF37] flex items-center justify-center mb-5 shadow-xl transform hover:rotate-3 transition-transform duration-500">
                <!-- Reemplaza el src con tu logo: {{ asset('img/logo.png') }} -->
                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo Hotel Inka Kings" class="w-full h-full object-cover rounded-full p-1">
            </div>
            
            <h1 class="font-playfair text-4xl font-bold text-white tracking-wide">Hotel Inka Kings</h1>
            <div class="w-16 h-0.5 bg-[#D4AF37] mx-auto my-3 rounded-full"></div>
            <p class="text-gray-400 text-sm tracking-widest uppercase">Bienvenido de nuevo</p>
        </div>

        <!-- Cuerpo del Formulario -->
        <div class="p-8 md:p-10">
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-amber-50 border-l-4 border-[#D4AF37] text-amber-800 p-4 mb-6 rounded-r-lg flex items-center shadow-sm animate-pulse" role="alert">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-[#B8860B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Campo Email -->
                <div class="mb-6 group">
                    <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wider" for="Email">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#D4AF37] transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            name="Email" 
                            id="Email"
                            value="{{ old('Email') }}"
                            class="w-full pl-12 pr-4 py-3.5 rounded-lg text-gray-800 placeholder-gray-400 input-luxury focus:outline-none"
                            required
                            placeholder="admin@hotelkings.com"
                        >
                    </div>
                </div>

                <!-- Campo Password -->
                <div class="mb-8 group">
                    <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wider" for="Password">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#D4AF37] transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="Password" 
                            id="Password"
                            class="w-full pl-12 pr-4 py-3.5 rounded-lg text-gray-800 placeholder-gray-400 input-luxury focus:outline-none"
                            required
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Botón Submit -->
                <button 
                    type="submit" 
                    class="w-full btn-gold text-black font-bold py-4 px-4 rounded-lg shadow-lg uppercase tracking-widest text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4AF37]"
                >
                    Ingresar
                </button>
            </form>

            <!-- Datos Demo -->
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Credenciales de prueba</p>
                <div class="flex justify-center items-center gap-2 text-sm text-gray-600 font-medium">
                    <span class="bg-gray-100 px-3 py-1 rounded text-xs font-mono text-gray-700 border border-gray-200">admin@hotelkings.com</span>
                    <span class="text-[#D4AF37]">/</span>
                    <span class="bg-gray-100 px-3 py-1 rounded text-xs font-mono text-gray-700 border border-gray-200">admin123</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>