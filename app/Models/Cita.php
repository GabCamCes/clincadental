<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'servicio_id',
        'sala_id',
        'fecha',
        'hora',
        'estado',
        'observaciones',
    ];

    // Relaciones (opcional, pero útil)
    public function paciente()    { return $this->belongsTo(\App\Models\Paciente::class); }
    public function medico()      { return $this->belongsTo(\App\Models\Medico::class); }
    public function servicio()    { return $this->belongsTo(\App\Models\Servicio::class); }
    public function sala()        { return $this->belongsTo(\App\Models\Sala::class); }

    public function historialClinico()
    {
        return $this->hasOne(\App\Models\HistorialClinico::class, 'cita_id');
    }

}
