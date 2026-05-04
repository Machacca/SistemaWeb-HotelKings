<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $rolMaster = DB::table('roles')->where('NombreRol', 'Master')->value('IdRol');
        $rolAdmin = DB::table('roles')->where('NombreRol', 'Administrador')->value('IdRol');
        $rolRecepcionista = DB::table('roles')->where('NombreRol', 'Recepcionista')->value('IdRol');
        
        $hotelKing = DB::table('hoteles')->where('Nombre', 'Hotel King Imperial')->value('IdHotel');
        $hotelReyes = DB::table('hoteles')->where('Nombre', 'Hostal Los Reyes')->value('IdHotel');

        DB::table('usuarios')->insert([
            // Master - Acceso total a todos los hoteles
            [
                'IdRol' => $rolMaster,
                'IdHotel' => null,
                'Username' => 'master',
                'PasswordHash' => Hash::make('master123'),
                'Email' => 'master@hotelkings.com',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Admin Hotel King Imperial
            [
                'IdRol' => $rolAdmin,
                'IdHotel' => $hotelKing,
                'Username' => 'admin_king',
                'PasswordHash' => Hash::make('admin123'),
                'Email' => 'admin@hotelking.com',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Admin Hostal Los Reyes
            [
                'IdRol' => $rolAdmin,
                'IdHotel' => $hotelReyes,
                'Username' => 'admin_reyes',
                'PasswordHash' => Hash::make('admin123'),
                'Email' => 'admin@hostalreyes.com',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Recepcionista Hotel King Imperial
            [
                'IdRol' => $rolRecepcionista,
                'IdHotel' => $hotelKing,
                'Username' => 'recepcion_king',
                'PasswordHash' => Hash::make('recepcion123'),
                'Email' => 'recepcion@hotelking.com',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Recepcionista Hostal Los Reyes
            [
                'IdRol' => $rolRecepcionista,
                'IdHotel' => $hotelReyes,
                'Username' => 'recepcion_reyes',
                'PasswordHash' => Hash::make('recepcion123'),
                'Email' => 'recepcion@hostalreyes.com',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}