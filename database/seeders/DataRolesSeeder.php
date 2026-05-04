<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataRolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['NombreRol' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['NombreRol' => 'Recepcionista', 'created_at' => now(), 'updated_at' => now()],
            ['NombreRol' => 'Mantenimiento', 'created_at' => now(), 'updated_at' => now()],
            ['NombreRol' => 'Master', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}