<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Kings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">🏨 Hotel Kings</h1>
            <p class="text-gray-600">Inicia sesión para continuar</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="Email">
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    name="Email" 
                    id="Email"
                    value="{{ old('Email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    required
                    placeholder="admin@hotelkings.com"
                >
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="Password">
                    Contraseña
                </label>
                <input 
                    type="password" 
                    name="Password" 
                    id="Password"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                    required
                    placeholder="••••••••"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition"
            >
                Ingresar
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-500">
            <p>Demo: <strong>admin@hotelkings.com</strong> / <strong>admin123</strong></p>
        </div>
    </div>
</body>
</html>