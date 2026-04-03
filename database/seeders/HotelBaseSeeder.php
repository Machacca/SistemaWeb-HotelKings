<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        // Insertar Roles
        DB::table('roles')->insert([
            ['NombreRol' => 'Administrador'],
            ['NombreRol' => 'Recepcionista'],
            ['NombreRol' => 'Mantenimiento'],
        ]);

        // Insertar Tipos de Habitación
        DB::table('tipo_habitacion')->insert([
            ['Nombre' => 'Simple', 'Tarifa_base' => 45.00, 'Capacidad' => 1],
            ['Nombre' => 'Doble', 'Tarifa_base' => 75.00, 'Capacidad' => 2],
            ['Nombre' => 'Matrimonial', 'Tarifa_base' => 90.00, 'Capacidad' => 2],
            ['Nombre' => 'Suite', 'Tarifa_base' => 150.00, 'Capacidad' => 4],
        ]);
    }
}
