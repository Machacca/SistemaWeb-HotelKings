<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataHuespedesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('huespedes')->insert([
            [
                'Nombre' => 'Carlos',
                'Apellido' => 'García López',
                'TipoDocumento' => 'DNI',
                'NroDocumento' => '12345678',
                'Email' => 'carlos.garcia@email.com',
                'Telefono' => '987654321',
                'Nacionalidad' => 'Peruana',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'María',
                'Apellido' => 'Fernández Ruiz',
                'TipoDocumento' => 'DNI',
                'NroDocumento' => '87654321',
                'Email' => 'maria.fernandez@email.com',
                'Telefono' => '912345678',
                'Nacionalidad' => 'Peruana',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'John',
                'Apellido' => 'Smith',
                'TipoDocumento' => 'Pasaporte',
                'NroDocumento' => 'USA123456',
                'Email' => 'john.smith@email.com',
                'Telefono' => '999888777',
                'Nacionalidad' => 'Estadounidense',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Ana',
                'Apellido' => 'Martínez Torres',
                'TipoDocumento' => 'DNI',
                'NroDocumento' => '45678912',
                'Email' => 'ana.martinez@email.com',
                'Telefono' => '955443322',
                'Nacionalidad' => 'Peruana',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Roberto',
                'Apellido' => 'Díaz Castro',
                'TipoDocumento' => 'CE',
                'NroDocumento' => 'CE987654',
                'Email' => 'roberto.diaz@email.com',
                'Telefono' => '933221100',
                'Nacionalidad' => 'Argentina',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}