<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataProductosSeeder extends Seeder
{
    public function run(): void
    {
        $hotelKing = DB::table('hoteles')->where('Nombre', 'Hotel King Imperial')->value('IdHotel');
        $hotelReyes = DB::table('hoteles')->where('Nombre', 'Hostal Los Reyes')->value('IdHotel');

        $productos = [
            // Bebidas
            ['Nombre' => 'Agua Mineral 500ml', 'PrecioVenta' => 3.00, 'StockMinimo' => 10, 'StockActual' => 50, 'categoria' => 'Bebidas', 'activo' => true],
            ['Nombre' => 'Agua Mineral 1L', 'PrecioVenta' => 5.00, 'StockMinimo' => 10, 'StockActual' => 40, 'categoria' => 'Bebidas', 'activo' => true],
            ['Nombre' => 'Gaseosa Personal', 'PrecioVenta' => 5.00, 'StockMinimo' => 10, 'StockActual' => 30, 'categoria' => 'Bebidas', 'activo' => true],
            ['Nombre' => 'Gaseosa 1.5L', 'PrecioVenta' => 10.00, 'StockMinimo' => 5, 'StockActual' => 20, 'categoria' => 'Bebidas', 'activo' => true],
            ['Nombre' => 'Cerveza Nacional', 'PrecioVenta' => 8.00, 'StockMinimo' => 20, 'StockActual' => 60, 'categoria' => 'Bebidas', 'activo' => true],
            ['Nombre' => 'Cerveza Importada', 'PrecioVenta' => 15.00, 'StockMinimo' => 10, 'StockActual' => 25, 'categoria' => 'Bebidas', 'activo' => true],
            
            // Snacks
            ['Nombre' => 'Papas Fritas', 'PrecioVenta' => 5.00, 'StockMinimo' => 10, 'StockActual' => 35, 'categoria' => 'Snacks', 'activo' => true],
            ['Nombre' => 'Galletas', 'PrecioVenta' => 3.00, 'StockMinimo' => 10, 'StockActual' => 40, 'categoria' => 'Snacks', 'activo' => true],
            ['Nombre' => 'Chocolate', 'PrecioVenta' => 6.00, 'StockMinimo' => 10, 'StockActual' => 30, 'categoria' => 'Snacks', 'activo' => true],
            ['Nombre' => 'Piqueos Mixtos', 'PrecioVenta' => 15.00, 'StockMinimo' => 5, 'StockActual' => 15, 'categoria' => 'Snacks', 'activo' => true],
            
            // Servicios
            ['Nombre' => 'Lavandería', 'PrecioVenta' => 20.00, 'StockMinimo' => 0, 'StockActual' => 999, 'categoria' => 'Servicios', 'descripcion' => 'Servicio de lavandería por carga', 'activo' => true],
            ['Nombre' => 'Estacionamiento', 'PrecioVenta' => 15.00, 'StockMinimo' => 0, 'StockActual' => 999, 'categoria' => 'Servicios', 'descripcion' => 'Estacionamiento por noche', 'activo' => true],
            ['Nombre' => 'Desayuno Adicional', 'PrecioVenta' => 12.00, 'StockMinimo' => 0, 'StockActual' => 999, 'categoria' => 'Servicios', 'descripcion' => 'Desayuno para persona adicional', 'activo' => true],
            ['Nombre' => 'Late Check-out', 'PrecioVenta' => 30.00, 'StockMinimo' => 0, 'StockActual' => 999, 'categoria' => 'Servicios', 'descripcion' => 'Extensión de salida hasta las 6pm', 'activo' => true],
        ];

        foreach ($productos as $producto) {
            // Para Hotel King Imperial
            if ($hotelKing) {
                DB::table('productos')->insert(array_merge($producto, [
                    'IdHotel' => $hotelKing,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            
            // Para Hostal Los Reyes
            if ($hotelReyes) {
                DB::table('productos')->insert(array_merge($producto, [
                    'IdHotel' => $hotelReyes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}