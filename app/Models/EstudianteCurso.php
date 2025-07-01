<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstudianteCurso extends Model
{
    use HasFactory;

    protected $table = 'estudiante_curso';
    protected $primaryKey = 'codigo';
    public $timestamps = true;
    protected $fillable = [
      'monto','estudiante_id','curso_id','estado_id','factura_id'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'codigo');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'codigo');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id', 'codigo');
    }
}
