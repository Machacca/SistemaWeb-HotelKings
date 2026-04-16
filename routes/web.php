<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebLoginController;

use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConsumoController;
use App\Http\Controllers\ComprobanteController;


// Login web
Route::get('/login', [WebLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebLoginController::class, 'login']);
Route::post('/logout', [WebLoginController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard protegido
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Crea esta vista después
    })->name('dashboard');
});

// Redirect raíz a login o dashboard
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::resource('habitaciones', HabitacionController::class);
Route::resource('productos', ProductoController::class);
Route::resource('usuarios', UsuarioController::class);
Route::post('consumos', [ConsumoController::class, 'store'])->name('consumos.store');
Route::resource('comprobantes', ComprobanteController::class);

require base_path('app/Features/Habitaciones/routes.php');


use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// --- BLOQUE UNIFICADO DE RESERVAS ---

// 1. Mapa de habitaciones
Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');

// 2. Formulario de creación (Usaremos 'create' para seguir el estándar)
Route::get('/reservas/create/{id}', [ReservaController::class, 'create'])->name('reservas.create');

// 3. Guardar la reserva
Route::post('/reservas/store', [ReservaController::class, 'store'])->name('reservas.store');

// 4. Ver detalles de una reserva (Opcional por ahora)
Route::get('/reservas/{id}', [ReservaController::class, 'show'])->name('reservas.show');
Route::get('/habitaciones/liberar/{id}', [HabitacionController::class, 'liberar']);
use App\Http\Controllers\HuespedController;

// Ruta para mostrar el formulario
Route::get('/huespedes/create', [HuespedController::class, 'create'])->name('huespedes.create');

// Ruta para procesar el guardado
Route::post('/huespedes', [HuespedController::class, 'store'])->name('huespedes.store');

// Ruta para el listado (opcional para el redireccionamiento)
Route::get('/huespedes', [HuespedController::class, 'index'])->name('huespedes.index');

Route::resource('huespedes', HuespedController::class);