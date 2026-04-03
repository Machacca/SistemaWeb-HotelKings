<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConsumoController;
use App\Http\Controllers\ComprobanteController;

Route::resource('habitaciones', HabitacionController::class);
Route::resource('huespedes', HuespedController::class);
Route::resource('productos', ProductoController::class);
Route::resource('reservas', ReservaController::class);
Route::resource('usuarios', UsuarioController::class);
Route::post('consumos', [ConsumoController::class, 'store'])->name('consumos.store');
Route::resource('comprobantes', ComprobanteController::class);

