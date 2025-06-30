<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = "rol";
    protected $primaryKey = "codigo";
    public $timestamps = false;
    protected $fillable = ["nombre", "descripcion"];

    public function personas() {
        return $this->hasMany(User::class, 'rol_id', 'codigo');
    }

    public function permisos() {
        return $this->hasMany(Permiso::class, 'rol_id', 'codigo')->with('funcionalidad');;
    }


}
