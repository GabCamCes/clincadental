<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $fillable = ['usuario_id'];
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }
}
