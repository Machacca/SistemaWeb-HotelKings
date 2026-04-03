<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aquí llamas a tus otros seeders en orden
        $this->call([
            HotelBaseSeeder::class,
            HotelDemoSeeder::class,
        ]);
    }
}