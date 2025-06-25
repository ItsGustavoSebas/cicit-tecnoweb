<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipo_estudiante extends Model
{
    use HasFactory;

    protected $table = 'tipo_estudiante';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'nombre',
        'status'
    ];

}
