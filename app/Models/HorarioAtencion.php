<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioAtencion extends Model
{
    protected $table = 'horarios_atencion';
    public $timestamps = false;

    protected $fillable = [
        'medico_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function medico()
    {
        return $this->belongsTo(\App\Models\Medico::class);
    }
}
