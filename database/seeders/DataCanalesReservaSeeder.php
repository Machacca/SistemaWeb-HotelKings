<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataCanalesReservaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('canales_reserva')->insert([
            [
                'Nombre' => 'Directo',
                'descripcion' => 'Reserva realizada directamente en recepción',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Booking.com',
                'descripcion' => 'Reserva a través de Booking.com',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Expedia',
                'descripcion' => 'Reserva a través de Expedia',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Teléfono',
                'descripcion' => 'Reserva por llamada telefónica',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Walk-in',
                'descripcion' => 'Cliente que llega sin reserva previa',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}