<?php

use Illuminate\Support\Facades\Route;
use App\Features\Habitaciones\Controllers\HabitacionController;

Route::middleware('auth')->prefix('habitaciones')->name('habitaciones.')->group(function () {
    Route::get('/', [HabitacionController::class, 'index'])->name('index');
    Route::get('/crear', [HabitacionController::class, 'create'])->name('create');
    Route::post('/', [HabitacionController::class, 'store'])->name('store');
    Route::get('/{habitacion}', [HabitacionController::class, 'show'])->name('show');
    Route::get('/{habitacion}/editar', [HabitacionController::class, 'edit'])->name('edit');
    Route::put('/{habitacion}', [HabitacionController::class, 'update'])->name('update');
    Route::delete('/{habitacion}', [HabitacionController::class, 'destroy'])->name('destroy');
    
    // Acción rápida para housekeeping
    Route::patch('/{habitacion}/estado', [HabitacionController::class, 'updateEstado'])->name('updateEstado');
});