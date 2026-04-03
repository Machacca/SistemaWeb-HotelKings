<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HotelDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insertar el Hotel Principal
        $hotelId = DB::table('hoteles')->insertGetId([
            'Nombre' => 'Hotel King Imperial',
            'Direccion' => 'Av. Principal 123, Centro Histórico',
            'RUC' => '20123456789',
            'Configuracion' => now(),
            'created_at' => now(),
        ]);

        // 2. Tipos de Habitación (Plantilla de categorías)
        $tipoSimple = DB::table('tipo_habitacion')->insertGetId([
            'Nombre' => 'Individual Estándar',
            'Tarifa_base' => 45.00,
            'Capacidad' => 1,
            'Descripcion' => 'Habitación acogedora con cama de 1.5 plazas y vista interna.'
        ]);

        $tipoSuite = DB::table('tipo_habitacion')->insertGetId([
            'Nombre' => 'Suite Presidencial',
            'Tarifa_base' => 180.00,
            'Capacidad' => 2,
            'Descripcion' => 'Máximo lujo con jacuzzi, cama King y vista al mar.'
        ]);

        // 3. Insertar algunas habitaciones físicas
        DB::table('habitaciones')->insert([
            ['IdTipo' => $tipoSimple, 'IdHotel' => $hotelId, 'Numero' => '101', 'Piso' => '1', 'IdEstadoHabitacion' => 1],
            ['IdTipo' => $tipoSimple, 'IdHotel' => $hotelId, 'Numero' => '102', 'Piso' => '1', 'IdEstadoHabitacion' => 1],
            ['IdTipo' => $tipoSuite, 'IdHotel' => $hotelId, 'Numero' => '501', 'Piso' => '5', 'IdEstadoHabitacion' => 1],
        ]);

        // 4. Roles del sistema
        $rolAdmin = DB::table('roles')->insertGetId(['NombreRol' => 'Administrador']);
        DB::table('roles')->insert(['NombreRol' => 'Recepcionista']);

        // 5. Usuario para que entres a probar
        DB::table('usuarios')->insert([
            'IdRol' => $rolAdmin,
            'IdHotel' => $hotelId,
            'Username' => 'admin_hotel',
            'PasswordHash' => Hash::make('admin123'), // Contraseña: admin123
            'Email' => 'admin@hotelkings.com',
            'created_at' => now(),
        ]);
        
        // 6. Canales de Reserva
        DB::table('canales_reserva')->insert([
            ['Nombre' => 'Recepción / Directo'],
            ['Nombre' => 'Booking.com'],
            ['Nombre' => 'WhatsApp'],
        ]);
    }
}
