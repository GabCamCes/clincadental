<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = 'salas';
    protected $fillable = ['nombre', 'descripcion'];
    public $timestamps = false; // tu tabla no tiene created_at ni updated_at
}
