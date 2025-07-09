<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    protected $table = 'historial_clinico';
    public $timestamps = false; // Si tu tabla no tiene created_at/updated_at

    protected $fillable = [
        'cita_id',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'fecha_registro',
    ];

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}
