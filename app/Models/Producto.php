<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'IdProducto';
    protected $fillable = ['IdHotel', 'Nombre', 'PrecioVenta', 'StockMinimo', 'StockActual'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'IdHotel', 'IdHotel');
    }
}