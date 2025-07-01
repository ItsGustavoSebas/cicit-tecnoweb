<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'factura';
    protected $primaryKey = 'codigo';
    public $timestamps = true;
    protected $fillable = ['users_id','monto'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function items()
    {
        // items = registros de estudiante_curso pagados en esta factura
        return $this->hasMany(EstudianteCurso::class, 'factura_id', 'codigo')
                    ->where('estado_id', 2);
    }
}
