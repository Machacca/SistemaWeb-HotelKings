<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataTipoHabitacionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_habitacion')->insert([
            [
                'Nombre' => 'Suite',
                'Tarifa_base' => 150.00,
                'Capacidad' => 3,
                'Descripcion' => 'Habitación de lujo con sala de estar separada y jacuzzi.',
                'activo' => true,
            ],
            [
                'Nombre' => 'Doble',
                'Tarifa_base' => 80.00,
                'Capacidad' => 2,
                'Descripcion' => 'Habitación con dos camas queen size.',
                'activo' => true,
            ],
            [
                'Nombre' => 'Individual',
                'Tarifa_base' => 50.00,
                'Capacidad' => 1,
                'Descripcion' => 'Habitación con una cama individual.',
                'activo' => true,
            ],
            [
                'Nombre' => 'Matrimonial',
                'Tarifa_base' => 100.00,
                'Capacidad' => 2,
                'Descripcion' => 'Habitación con cama king size.',
                'activo' => true,
            ],
        ]);
    }
}