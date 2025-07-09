<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo_promocion',
        'porcentaje_descuento',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    public $timestamps = false;

    public function productos()
    {
        return $this->belongsToMany(\App\Models\Producto::class, 'promocion_producto', 'promocion_id', 'producto_id');
    }
    public function servicios()
    {
        return $this->belongsToMany(\App\Models\Servicio::class, 'promocion_servicio', 'promocion_id', 'servicio_id');
    }

}
