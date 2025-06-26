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
        'nombre',
        'duracion',
        'cupo',
        'presencial',
        'profesor_id',
        'users_id',
    ];

    public function profesor()
    {
        return $this->belongsTo(profesor::class, 'profesor_id');
    }
    public function usuario()
    {
        return $this->belongsTo(users::class, 'users_id');
    }
}
