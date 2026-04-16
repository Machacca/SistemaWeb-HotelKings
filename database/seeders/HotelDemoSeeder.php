<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HotelDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. HOTEL
        $hotelId = DB::table('hoteles')->insertGetId([
            'Nombre' => 'Hotel King Imperial',
            'Direccion' => 'Av. Principal 123, Centro Histórico',
            'RUC' => '20123456789',
            'Configuracion' => now(),
            'created_at' => now(),
        ]);

        // 2. CANALES DE RESERVA
        $canalRec = DB::table('canales_reserva')->insertGetId(['Nombre' => 'Recepción', 'created_at' => now()]);
        $canalBook = DB::table('canales_reserva')->insertGetId(['Nombre' => 'Booking.com', 'created_at' => now()]);
        $canalWeb = DB::table('canales_reserva')->insertGetId(['Nombre' => 'Sitio Web', 'created_at' => now()]);

        // 3. TIPOS DE HABITACIÓN
        $tSimple = DB::table('tipo_habitacion')->insertGetId(['Nombre' => 'Simple', 'Tarifa_base' => 60.00, 'Capacidad' => 1, 'Descripcion' => 'Cama 1.5 plazas']);
        $tDoble = DB::table('tipo_habitacion')->insertGetId(['Nombre' => 'Doble', 'Tarifa_base' => 100.00, 'Capacidad' => 2, 'Descripcion' => 'Dos camas de 2 plazas']);
        $tSuite = DB::table('tipo_habitacion')->insertGetId(['Nombre' => 'Suite', 'Tarifa_base' => 250.00, 'Capacidad' => 2, 'Descripcion' => 'Jacuzzi y Minibar']);

        // 4. HABITACIONES (Total: 6 habitaciones para pruebas)
        $h101 = DB::table('habitaciones')->insertGetId(['IdTipo' => $tSimple, 'IdHotel' => $hotelId, 'Numero' => '101', 'Piso' => '1', 'IdEstadoHabitacion' => 1]); // Disponible
        $h102 = DB::table('habitaciones')->insertGetId(['IdTipo' => $tSimple, 'IdHotel' => $hotelId, 'Numero' => '102', 'Piso' => '1', 'IdEstadoHabitacion' => 2]); // Ocupada
        $h201 = DB::table('habitaciones')->insertGetId(['IdTipo' => $tDoble, 'IdHotel' => $hotelId, 'Numero' => '201', 'Piso' => '2', 'IdEstadoHabitacion' => 1]); // Disponible
        $h202 = DB::table('habitaciones')->insertGetId(['IdTipo' => $tDoble, 'IdHotel' => $hotelId, 'Numero' => '202', 'Piso' => '2', 'IdEstadoHabitacion' => 2]); // Ocupada
        $h501 = DB::table('habitaciones')->insertGetId(['IdTipo' => $tSuite, 'IdHotel' => $hotelId, 'Numero' => '501', 'Piso' => '5', 'IdEstadoHabitacion' => 1]); // Disponible
        $h502 = DB::table('habitaciones')->insertGetId(['IdTipo' => $tSuite, 'IdHotel' => $hotelId, 'Numero' => '502', 'Piso' => '5', 'IdEstadoHabitacion' => 3]); // Mantenimiento

        // 5. PRODUCTOS NUEVOS
        $p1 = DB::table('productos')->insertGetId(['IdHotel' => $hotelId, 'Nombre' => 'Agua San Mateo 500ml', 'PrecioVenta' => 3.00, 'StockActual' => 100, 'created_at' => now()]);
        $p2 = DB::table('productos')->insertGetId(['IdHotel' => $hotelId, 'Nombre' => 'Cerveza Pilsen Lata', 'PrecioVenta' => 7.00, 'StockActual' => 48, 'created_at' => now()]);
        $p3 = DB::table('productos')->insertGetId(['IdHotel' => $hotelId, 'Nombre' => 'Snack Pringles Original', 'PrecioVenta' => 12.00, 'StockActual' => 20, 'created_at' => now()]);
        $p4 = DB::table('productos')->insertGetId(['IdHotel' => $hotelId, 'Nombre' => 'Kit de Aseo Premium', 'PrecioVenta' => 15.00, 'StockActual' => 15, 'created_at' => now()]);

        // 6. HUÉSPEDES VARIADOS
        $hue1 = DB::table('huespedes')->insertGetId(['Nombre' => 'Leo David', 'Apellido' => 'García', 'TipoDocumento' => 'DNI', 'NroDocumento' => '12345677', 'Email' => 'leo@gmail.com', 'Telefono' => '919291230', 'Nacionalidad' => 'Perú']);
        $hue2 = DB::table('huespedes')->insertGetId(['Nombre' => 'Maria', 'Apellido' => 'Fernandez', 'TipoDocumento' => 'DNI', 'NroDocumento' => '44556677', 'Email' => 'maria@gmail.com', 'Telefono' => '988776655', 'Nacionalidad' => 'Perú']);
        $hue3 = DB::table('huespedes')->insertGetId(['Nombre' => 'John', 'Apellido' => 'Doe', 'TipoDocumento' => 'PAS', 'NroDocumento' => 'Z998877', 'Email' => 'john.doe@email.com', 'Telefono' => '1202555010', 'Nacionalidad' => 'EEUU']);

        // 7. RESERVA 1 (Ocupando la 102)
        $res1 = DB::table('reservas')->insertGetId([
            'IdHuesped' => $hue1, 'IdCanal' => $canalRec, 'Estado' => 'Confirmada', 'FechaReserva' => now(), 'TotalReserva' => 120.00, 'created_at' => now()
        ]);
        DB::table('detalle_reserva')->insert([
            'IdReserva' => $res1, 'IdHabitacion' => $h102, 'FechaCheckIn' => now(), 'FechaCheckOut' => Carbon::now()->addDays(2), 'PrecioNoche' => 60.00, 'created_at' => now()
        ]);
        // Consumos para Reserva 1
        DB::table('consumo_reserva')->insert(['IdReserva' => $res1, 'IdProducto' => $p1, 'Cantidad' => 2, 'FechaConsumo' => now(), 'EstadoPago' => false, 'created_at' => now()]);

        // 8. RESERVA 2 (Ocupando la 202 con Acompañante)
        $res2 = DB::table('reservas')->insertGetId([
            'IdHuesped' => $hue2, 'IdCanal' => $canalBook, 'Estado' => 'Confirmada', 'FechaReserva' => now(), 'TotalReserva' => 300.00, 'created_at' => now()
        ]);
        $det2 = DB::table('detalle_reserva')->insertGetId([
            'IdReserva' => $res2, 'IdHabitacion' => $h202, 'FechaCheckIn' => now(), 'FechaCheckOut' => Carbon::now()->addDays(3), 'PrecioNoche' => 100.00, 'created_at' => now()
        ]);
        DB::table('acompanantes')->insert([
            'IdDetalleReserva' => $det2, 'Nombre' => 'Pedro', 'Apellido' => 'Ramirez', 'TipoDocumento' => 'DNI', 'NroDocumento' => '77889900', 'Parentesco' => 'Hijo', 'created_at' => now()
        ]);
        // Consumos para Reserva 2
        DB::table('consumo_reserva')->insert(['IdReserva' => $res2, 'IdProducto' => $p2, 'Cantidad' => 1, 'FechaConsumo' => now(), 'EstadoPago' => true, 'created_at' => now()]);

        // 9. ROLES Y USUARIO
        $rolId = DB::table('roles')->insertGetId(['NombreRol' => 'Administrador']);
        DB::table('usuarios')->insert([
            'IdRol' => $rolId, 'IdHotel' => $hotelId, 'Username' => 'admin', 'PasswordHash' => Hash::make('admin123'), 'Email' => 'admin@hotel.com', 'created_at' => now()
        ]);
    }
}