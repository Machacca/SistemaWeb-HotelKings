<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\WebLoginController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\CanalReservaController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CajaController;

Route::get('/', function () {
    return view('login');
});


//rutas de autenticación
Route::get('/login', [WebLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebLoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [WebLoginController::class, 'logout'])->name('logout');
    Route::post('/cambiar-contexto', [WebLoginController::class, 'cambiarContexto'])->name('contexto.hotel');    // Cambiar contexto de hotel (solo para usuarios Master)
    
    
    Route::resource('habitaciones', HabitacionController::class)->parameters(['habitaciones' => 'id' ]); // Esto hace que el parámetro se llame 'id'
    Route::put('habitaciones/{id}/liberar', [HabitacionController::class, 'liberar'])->name('habitaciones.liberar');
    Route::put('habitaciones/{id}/reactivar', [HabitacionController::class, 'reactivar'])->name('habitaciones.reactivar');

    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/create/{idHabitacion}', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{id}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::put('/reservas/{id}/checkin', [ReservaController::class, 'checkin'])->name('reservas.checkin');
    Route::put('/reservas/{id}/checkout', [ReservaController::class, 'checkout'])->name('reservas.checkout');
    Route::post('/reservas/detalle/{idDetalle}/acompanante', [ReservaController::class, 'agregarAcompanante'])->name('reservas.agregarAcompanante');
    

    Route::resource('huespedes', HuespedController::class)->parameters(['huespedes' => 'id']);
    Route::put('huespedes/{id}/reactivar', [HuespedController::class, 'reactivar'])->name('huespedes.reactivar');
    Route::get('huespedes/buscar', [HuespedController::class, 'buscar'])->name('huespedes.buscar');
    Route::post('/huespedes/modal-store', [HuespedController::class, 'storeModal'])->name('huespedes.modalStore');
    Route::get('/buscar-huespedes', [HuespedController::class, 'buscarModal'])->name('buscar.huespedes');
    
    Route::get('/reservas/{id}/liquidacion', [ReservaController::class, 'liquidacion'])->name('reservas.liquidacion');
    Route::post('/reservas/{id}/checkout', [ReservaController::class, 'procesarCheckout'])->name('reservas.procesarCheckout');

    Route::resource('productos', ProductoController::class)->parameters(['productos' => 'id']);
    Route::put('productos/{id}/reactivar', [ProductoController::class, 'reactivar'])->name('productos.reactivar');
    Route::delete('/consumos/{id}/eliminar', [ReservaController::class, 'eliminarConsumo'])->name('consumos.eliminar');
    
    Route::post('/productos/{id}/movimiento', [MovimientoInventarioController::class, 'storeFromProducto'])->name('movimientos.producto.store');
    Route::delete('/movimientos/{id}', [MovimientoInventarioController::class, 'destroy'])->name('movimientos.destroy');

    Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
    Route::post('/caja/movimiento', [CajaController::class, 'store'])->name('caja.store');
    Route::get('/caja/imprimir', [CajaController::class, 'imprimir'])->name('caja.imprimir');

    Route::get('/comprobantes/{id}', [ReservaController::class, 'verComprobante'])->name('comprobantes.show');
    Route::get('/comprobantes/{id}/pdf', [ReservaController::class, 'descargarComprobantePDF'])->name('comprobantes.pdf');

    Route::resource('usuarios', UsuarioController::class)->parameters(['usuarios' => 'id']);
    Route::put('usuarios/{id}/reactivar', [UsuarioController::class, 'reactivar'])->name('usuarios.reactivar');

    Route::resource('tiposhabitacion', TipoHabitacionController::class)->parameters(['tiposhabitacion' => 'id']);
    Route::put('tiposhabitacion/{id}/reactivar', [TipoHabitacionController::class, 'reactivar'])->name('tiposhabitacion.reactivar');

    Route::resource('hoteles', HotelController::class)->parameters(['hoteles' => 'id']);
    Route::put('hoteles/{id}/reactivar', [HotelController::class, 'reactivar'])->name('hoteles.reactivar');

    Route::resource('empleados', EmpleadoController::class)->parameters(['empleados' => 'id']);
    Route::put('empleados/{id}/reactivar', [EmpleadoController::class, 'reactivar'])->name('empleados.reactivar');

    Route::resource('canales-reserva', CanalReservaController::class)->parameters(['canales-reserva' => 'id']);
    Route::put('canales-reserva/{id}/reactivar', [CanalReservaController::class, 'reactivar'])->name('canales-reserva.reactivar');

    Route::resource('formas-pago', FormaPagoController::class)->parameters(['formas-pago' => 'id']);
    Route::put('formas-pago/{id}/reactivar', [FormaPagoController::class, 'reactivar'])->name('formas-pago.reactivar');

    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    
});
//Ruta raíz (redirige a login si no está autenticado, o a dashboard si lo está)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});