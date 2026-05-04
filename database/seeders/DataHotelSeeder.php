<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataHotelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hoteles')->insert([
            [
                'Nombre' => 'Hotel King Imperial',
                'codigo' => 'HKI-001',
                'Direccion' => 'Av. Principal 123, Centro Histórico',
                'Telefono' => '01-1234567',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Hostal Los Reyes',
                'codigo' => 'HLR-002',
                'Direccion' => 'Calle Secundaria 456, Norte',
                'Telefono' => '01-7654321',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}