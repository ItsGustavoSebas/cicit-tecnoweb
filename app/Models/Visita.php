<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;
    protected $table = "visita";
    protected $primaryKey = 'codigo';
    protected $guarded = [];
    public $timestamps = false;

    public function persona() {
        return $this->belongsTo(User::class, 'users_id', 'codigo');
    }

}
