<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class factura extends Model
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
        return $this->belongsTo(users::class, 'users_id');
    }
}
