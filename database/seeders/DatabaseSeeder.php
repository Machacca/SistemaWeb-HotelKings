<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DataRolesSeeder::class,
            DataHotelSeeder::class,
            DataUsuarioSeeder::class,
            DataTipoHabitacionesSeeder::class,
            DataHabitaciones::class,
            DataHuespedesSeeder::class,
            DataCanalesReservaSeeder::class,
            DataFormasPagoSeeder::class,
            DataProductosSeeder::class,
        ]);
    }
}