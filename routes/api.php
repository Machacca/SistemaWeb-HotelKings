<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('api.profile');
    
    // Tu API existente (ejemplo)
    // Route::apiResource('reservas', \App\Http\Controllers\ReservaController::class);
});

// Fallback para rutas no encontradas
Route::fallback(function () {
    return response()->json(['message' => 'Endpoint no encontrado'], 404);
});