<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';
    protected $primaryKey = 'codigo';
    public $timestamps = true;
    protected $fillable = [
      'nombre','apellido','ci','tipo_estudiante_id'
    ];

    public function tipoEstudiante()
    {
        return $this->belongsTo(TipoEstudiante::class, 'tipo_estudiante_id');
    }
    public function tipo()
    {
        return $this->belongsTo(TipoEstudiante::class, 'tipo_estudiante_id');
    }
}
