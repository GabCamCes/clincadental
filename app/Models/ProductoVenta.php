<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoVenta extends Model
{
    protected $table = 'producto_venta';
    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario'
    ];

    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
    }
}
