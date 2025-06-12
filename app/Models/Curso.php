<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'foto',
        'nombre',
        'duracion',
        'horarios',
        'precio',
        'instructor',
    ];

    // Si 'horarios' es un array/JSON, necesitas esto también:
    protected $casts = [
        'horarios' => 'array',
    ];
}
