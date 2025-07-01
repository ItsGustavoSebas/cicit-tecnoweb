<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesor';
    protected $primaryKey = 'codigo';
    public $timestamps = true;

    protected $fillable = [
      'nombre','apellido','titulo','foto','ci'
    ];
}
