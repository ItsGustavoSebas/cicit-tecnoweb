<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cronograma extends Model
{
    use HasFactory;

    protected $table = 'cronograma';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'dia',
        'hora_inicio',
        'hora_fin',
    ];

}