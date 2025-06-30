<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionalidad extends Model
{
    use HasFactory;
    protected $table = "funcionalidad";
    protected $primaryKey = "codigo";
    public $timestamps = false;
    protected $guarded = [ ];

    public function permisos() {
        return $this->hasMany(Permiso::class, 'funcionalidad_id', 'codigo');
    }
}
