<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataFormasPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('formas_pago')->insert([
            [
                'Nombre' => 'Efectivo',
                'descripcion' => 'Pago en efectivo',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Tarjeta de Crédito',
                'descripcion' => 'Pago con tarjeta de crédito (Visa, Mastercard)',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Tarjeta de Débito',
                'descripcion' => 'Pago con tarjeta de débito',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Transferencia',
                'descripcion' => 'Transferencia bancaria',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Yape/Plin',
                'descripcion' => 'Pago por aplicativo móvil',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}