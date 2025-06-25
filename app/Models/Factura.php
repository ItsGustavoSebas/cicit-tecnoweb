<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'factura';
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'users_id',
        'monto'
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
