<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Precio extends Model
{
    use HasFactory;

    protected $table = 'precio';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'precio',
        'tipo_estudiante_id',
        'curso_id'
    ];

    public function tipoEstudiante()
    {
        return $this->belongsTo(TipoEstudiante::class, 'tipo_estudiante_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }


    public static function bulkStore(array $rows, int $cursoId): void
    {
        foreach ($rows as $row) {
            static::create($row + ['curso_id' => $cursoId]);
        }
    }


    public static function replaceAll(int $cursoId, array $rows): void
    {
        DB::transaction(function () use ($cursoId, $rows) {
            static::where('curso_id', $cursoId)->delete();
            static::bulkStore($rows, $cursoId);
        });
    }

}