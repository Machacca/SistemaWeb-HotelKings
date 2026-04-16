<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Inka Kings</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('{{ asset('img/fondoLogin.png') }}') no-repeat center center/cover;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
        }

        /* Animación */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade {
            animation: fadeIn 0.6s ease-out;
        }

        /* Botón */
        .btn-gold {
            background: #d4af37;
            color: #000;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background: #c19b2e;
            transform: translateY(-1px);
        }

        /* Inputs */
        .input-luxury {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            transition: all 0.3s ease;
        }

        .input-luxury:focus {
            border-color: #C9A646;
            box-shadow: 0 0 0 3px rgba(201,166,70,0.2);
        }

        /* Placeholder */
        ::placeholder {
            color: #9ca3af;
            opacity: 1;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- Card -->
    <div class="relative w-full max-w-md bg-white/95 backdrop-blur-md rounded-xl shadow-2xl overflow-hidden animate-fade z-10">

        <!-- Header -->
        <div class="bg-black text-center py-8 px-6">

            <!-- Logo  -->
            <div class="relative mx-auto w-28 h-28 rounded-full 
                bg-gradient-to-br from-[#1a1a1a] to-black 
                border-2 border-[#D4AF37] 
                flex items-center justify-center 
                mb-5 shadow-xl">

                <img src="{{ asset('img/logo.jpeg') }}" 
                    class="w-full h-full object-cover rounded-full p-1">
            </div>

            <h1 class="font-playfair text-3xl font-semibold text-white tracking-wide">
                HOTEL INKA KINGS
            </h1>

            <div class="w-12 h-[2px] bg-[#C9A646] mx-auto my-3"></div>

            <p class="text-gray-400 text-xs tracking-[0.3em] uppercase">
                Acceso al sistema
            </p>
        </div>

        <!-- Form -->
        <div class="p-8">

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-5 text-sm rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-amber-50 border-l-4 border-[#C9A646] text-amber-800 p-3 mb-5 text-sm rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label class="text-xs text-gray-700 font-medium uppercase tracking-wider">
                        Correo electrónico
                    </label>

                    <input 
                        type="email"
                        name="Email"
                        value="{{ old('Email') }}"
                        class="w-full mt-2 px-4 py-3 rounded-lg input-luxury focus:outline-none"
                        placeholder="admin@hotelkings.com"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="text-xs text-gray-700 font-medium uppercase tracking-wider">
                        Contraseña
                    </label>

                    <input 
                        type="password"
                        name="Password"
                        class="w-full mt-2 px-4 py-3 rounded-lg input-luxury focus:outline-none"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Botón -->
                <button 
                    type="submit"
                    class="w-full btn-gold py-3 rounded-lg uppercase tracking-widest text-sm"
                >
                    Ingresar
                </button>
            </form>

        </div>
    </div>

</body>
</html>