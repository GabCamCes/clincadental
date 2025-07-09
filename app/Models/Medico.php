<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $table = 'medicos';
    protected $fillable = ['usuario_id', 'especialidad_id'];
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(\App\Models\Especialidad::class, 'especialidad_id');
    }
}
