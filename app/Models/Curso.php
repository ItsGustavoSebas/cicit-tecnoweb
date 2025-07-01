<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'curso';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'nombre',
        'duracion',
        'cupo',
        'presencial',
        'profesor_id',
        'users_id',
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function cronogramas()
    {
        return $this->hasMany(Cronograma::class, 'curso_id');
    }

    public function precios()
    {
        return $this->hasMany(Precio::class, 'curso_id');
    }


    public static function crearCurso(array $data, int $userId): self
    {
        return DB::transaction(function () use ($data, $userId) {

            $curso = static::create([
                'nombre'       => $data['nombre'],
                'cupo'         => $data['cupo'],
                'duracion'         => $data['duracion'],
                'presencial'   => $data['presencial'],
                'profesor_id'  => $data['profesor_id'],
                'users_id'     => $userId,
            ]);

            Cronograma::bulkStore($data['cronogramas'], $curso->codigo);
            Precio::bulkStore($data['precios'],         $curso->codigo);

            return $curso;
        });
    }

    public function actualizarCurso(array $data, int $userId): void
    {
        DB::transaction(function () use ($data, $userId) {

            $this->update([
                'nombre'       => $data['nombre'],
                'cupo'         => $data['cupo'],
                'duracion'         => $data['duracion'],
                'presencial'   => $data['presencial'],
                'profesor_id'  => $data['profesor_id'],
                'users_id'     => $userId,
            ]);

            Cronograma::replaceAll($this->codigo, $data['cronogramas']);
            Precio::replaceAll($this->codigo,     $data['precios']);
        });
  
    }
}
