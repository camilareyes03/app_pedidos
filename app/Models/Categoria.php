<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//modelo de categiria 
class Categoria extends Model
{
    protected $table = 'categoria';

    protected $fillable = [
        'nombre', 'descripcion'
    ];

    use HasFactory;

    public function producto(){
        return $this->hasMany(Producto::class,'idCategoria');
    }
}
