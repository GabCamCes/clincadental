<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    // Si tienes campos created_at y updated_at, esto es correcto.
    public $timestamps = true;

    // Los campos que pueden ser asignados masivamente
    protected $fillable = [
        'ci',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'correo',
        'edad',
        'genero',
        'tipo_usuario',
        'password',
    ];

    // Campos que se ocultan en arrays y JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Laravel usará el id como identificador de sesión (es lo correcto para la tabla sessions)
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    // Relación con Paciente
    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'usuario_id');
    }
    
    // Relación con Médico
    public function medico()
    {
        return $this->hasOne(Medico::class, 'usuario_id');
    }
}

