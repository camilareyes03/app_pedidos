<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicacion';

    protected $fillable = [
        'nombre', 'referencia', 'link', 'latitud', 'longitud', 'cliente_id'
    ];

    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,'cliente_id');
    }

}
