<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'tipo_estudiante_id',
    ];

    public function tipo_estudiante()
    {
        return $this->belongsTo(Tipo_Estudiante::class, 'tipo_estudiante_id');
    }

}
