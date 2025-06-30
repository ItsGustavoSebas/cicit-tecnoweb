<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;
    protected $table = "recurso";
    protected $primaryKey = "codigo";
    public $timestamps = false;
    protected $guarded = [ ];


    public function permisos() {
        return $this->belongsToMany(Permiso::class, "permiso_recurso", "recurso_id", "permiso_id");
    }
}
