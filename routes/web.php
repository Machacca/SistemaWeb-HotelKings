<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebLoginController;

use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\HuespedController;
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
Route::resource('huespedes', HuespedController::class);
Route::resource('productos', ProductoController::class);
Route::resource('reservas', ReservaController::class);
Route::resource('usuarios', UsuarioController::class);
Route::post('consumos', [ConsumoController::class, 'store'])->name('consumos.store');
Route::resource('comprobantes', ComprobanteController::class);

require base_path('app/Features/Habitaciones/routes.php');
