<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'fecha_pago',
        'monto_pagado',
        'metodo_pago',
        'estado_pago',
    ];

    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class, 'venta_id');
    }
}
