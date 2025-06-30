<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;
    protected $table = "permiso";
    protected $primaryKey = "codigo";
    public $timestamps = false;
    protected $guarded = [ ];


    public function role() {
        return $this->belongsTo(Role::class, 'rol_id', 'codigo');
    }

    public function funcionalidad() {
        return $this->belongsTo(Funcionalidad::class, 'funcionalidad_id', 'codigo');
    }

    public function recursos() {
        return $this->belongsToMany(Recurso::class, "permiso_recurso", "permiso_id", "recurso_id", "codigo", "codigo")
                    ->withPivot("activo");
    }
}
