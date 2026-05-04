<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataHabitaciones extends Seeder
{
    public function run(): void
    {
        $hotelKing = DB::table('hoteles')->where('Nombre', 'Hotel King Imperial')->value('IdHotel');
        $hotelReyes = DB::table('hoteles')->where('Nombre', 'Hostal Los Reyes')->value('IdHotel');
        
        $tipoSuite = DB::table('tipo_habitacion')->where('Nombre', 'Suite')->value('IdTipo');
        $tipoDoble = DB::table('tipo_habitacion')->where('Nombre', 'Doble')->value('IdTipo');
        $tipoIndividual = DB::table('tipo_habitacion')->where('Nombre', 'Individual')->value('IdTipo');
        $tipoMatrimonial = DB::table('tipo_habitacion')->where('Nombre', 'Matrimonial')->value('IdTipo');

        // Habitaciones Hotel King Imperial
        if ($hotelKing) {
            DB::table('habitaciones')->insert([
                // Piso 1
                ['Numero' => '101', 'Piso' => '1', 'IdTipo' => $tipoSuite, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '102', 'Piso' => '1', 'IdTipo' => $tipoDoble, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '103', 'Piso' => '1', 'IdTipo' => $tipoIndividual, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                // Piso 2
                ['Numero' => '201', 'Piso' => '2', 'IdTipo' => $tipoSuite, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '202', 'Piso' => '2', 'IdTipo' => $tipoMatrimonial, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '203', 'Piso' => '2', 'IdTipo' => $tipoDoble, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                // Piso 3
                ['Numero' => '301', 'Piso' => '3', 'IdTipo' => $tipoSuite, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '302', 'Piso' => '3', 'IdTipo' => $tipoMatrimonial, 'IdHotel' => $hotelKing, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Habitaciones Hostal Los Reyes
        if ($hotelReyes) {
            DB::table('habitaciones')->insert([
                // Piso 1
                ['Numero' => '101', 'Piso' => '1', 'IdTipo' => $tipoDoble, 'IdHotel' => $hotelReyes, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '102', 'Piso' => '1', 'IdTipo' => $tipoIndividual, 'IdHotel' => $hotelReyes, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '103', 'Piso' => '1', 'IdTipo' => $tipoIndividual, 'IdHotel' => $hotelReyes, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                // Piso 2
                ['Numero' => '201', 'Piso' => '2', 'IdTipo' => $tipoMatrimonial, 'IdHotel' => $hotelReyes, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['Numero' => '202', 'Piso' => '2', 'IdTipo' => $tipoDoble, 'IdHotel' => $hotelReyes, 'IdEstadoHabitacion' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}