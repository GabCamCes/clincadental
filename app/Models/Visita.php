<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $fillable = ['ruta', 'contador'];
    public $timestamps = true;
}
