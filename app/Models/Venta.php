<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    public $timestamps = false; // Según tu tabla

    protected $fillable = [
        'usuario_id',      // Quien hizo la venta (admin)
        'paciente_id',     // A quién se le vendió
        'fecha_venta',
        'total',
        'metodo_pago'
    ];

    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class, 'paciente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }

    public function productos()
    {
        return $this->hasMany(\App\Models\ProductoVenta::class, 'venta_id');
    }

    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class, 'venta_id');
    }
}
