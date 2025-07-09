<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'nombre',
        'ruta',
        'icono',
        'rol',
        'orden',
        'activo',
    ];

    // Puedes agregar relaciones aquí si luego quieres (por ejemplo, submenús)
}
