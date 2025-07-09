<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPago extends Model
{
    protected $table = 'historial_pagos';
    public $timestamps = false;

    protected $fillable = [
        'pago_id',
        'usuario_id',
        'accion',
        'monto',
        'tipo_pago',
        'fecha_evento',
        'observaciones',
    ];

    public function pago()
    {
        return $this->belongsTo(\App\Models\Pago::class, 'pago_id');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }
}
