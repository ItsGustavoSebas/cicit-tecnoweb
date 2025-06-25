<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class curso extends Model
{
    use HasFactory;

    protected $table = 'curso';
    protected $primaryKey = 'codigo';

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
