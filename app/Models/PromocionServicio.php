<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocionServicio extends Model
{
    protected $table = 'promocion_servicio';
    public $timestamps = false;

    protected $fillable = [
        'promocion_id',
        'servicio_id',
    ];
}
