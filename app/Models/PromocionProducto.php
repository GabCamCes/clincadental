<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocionProducto extends Model
{
    protected $table = 'promocion_producto';
    public $timestamps = false;

    protected $fillable = [
        'promocion_id',
        'producto_id',
    ];
}
