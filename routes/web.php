<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebLoginController;
use App\Http\Controllers\ComprobanteController;
use App\Features\Huespedes\Controllers\HuespedController;
use App\Features\Reservas\Controllers\ReservaController;


// Login
Route::get('/login', [WebLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebLoginController::class, 'login']);
Route::post('/logout', [WebLoginController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

Route::get('/', fn() => auth()->check() ? redirect('/dashboard') : redirect('/login'));

Route::middleware('auth')->group(function () {
    Route::post('/reservas/{id}/checkin', [ReservaController::class, 'checkin'])->name('reservas.checkin');
    Route::post('/reservas/{id}/checkout', [ReservaController::class, 'checkout'])->name('reservas.checkout');
});

Route::post('/api/huespedes', [App\Features\Huespedes\Controllers\HuespedController::class, 'store'])->name('api.huespedes.store');

// CRUD Huéspedes
Route::resource('huespedes', HuespedController::class);
Route::get('huespedes/buscar', [HuespedController::class, 'search'])->name('huespedes.search');


// CRUD Reservas (con creación de huésped integrada)
Route::resource('reservas', ReservaController::class);

// API para crear huésped desde reserva
Route::post('api/huespedes', [HuespedController::class, 'store'])->name('api.huespedes.store');

// routes/web.php
Route::resource('comprobantes', ComprobanteController::class);
// Cargar rutas de features
require base_path('app/Features/Habitaciones/routes.php');
