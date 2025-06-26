<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estudiante extends Model
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
        return $this->belongsTo(tipo_estudiante::class, 'tipo_estudiante_id');
    }

}
