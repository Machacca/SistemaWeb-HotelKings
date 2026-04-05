<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Hotel Kings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow p-4 flex justify-between">
        <h1 class="text-xl font-bold">🏨 Hotel Kings</h1>
        <div class="flex items-center gap-4">
            <span class="text-gray-700">Hola, {{ auth()->user()->Username }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-600 hover:text-red-800 font-medium">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="p-6">
        <h2 class="text-2xl font-bold mb-4">Panel de Control</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('habitaciones.index') }}" class="bg-white p-4 rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-lg">🛏️ Habitaciones</h3>
                <p class="text-gray-600">Gestionar disponibilidad y estados</p>
            </a>
            <a href="{{ route('reservas.index') }}" class="bg-white p-4 rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-lg">📅 Reservas</h3>
                <p class="text-gray-600">Crear y administrar reservas</p>
            </a>
            <a href="{{ route('huespedes.index') }}" class="bg-white p-4 rounded shadow hover:shadow-lg transition">
                <h3 class="font-bold text-lg">👥 Huéspedes</h3>
                <p class="text-gray-600">Registro de clientes</p>
            </a>
        </div>
    </main>
</body>
</html>