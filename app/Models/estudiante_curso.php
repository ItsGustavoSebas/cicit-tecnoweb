<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estudiante_curso extends Model
{
    use HasFactory;

    protected $table = 'estudiante_curso';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'monto',
        'estudiante_id',
        'curso_id',
        'estado_id',
        'factura_id',
    ];

    public function estudiante()
    {
        return $this->belongsTo(estudiante::class, 'estudiante_id');
    }

    public function curso()
    {
        return $this->belongsTo(curso::class, 'curso_id');
    }

    public function estado()
    {
        return $this->belongsTo(estado::class, 'estado_id');
    }

    public function factura()
    {
        return $this->belongsTo(factura::class, 'factura_id');
    }
}
