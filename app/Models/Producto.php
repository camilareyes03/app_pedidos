<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = [
        'nombre', 'precio', 'stock', 'foto', 'categoria_id'
    ];

    use HasFactory;

    public function categoria(){
        return $this->belongsTo(Categoria::class,'categoria_id');
    }

    public function detallePedido()
    {
        return $this->hasMany(DetallePedido::class, 'producto_id');
    }
}
