<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';

    protected $fillable = [
        'fecha', 'total', 'estado', 'cliente_id', 'repartidor_id'
    ];

    use HasFactory;

    public function user_cliente(){
        return $this->belongsTo(User::class,'cliente_id');
    }
    public function user_repartidor(){
        return $this->belongsTo(User::class,'repartidor_id');
    }

    public function detallePedido()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }
    public function actualizarMontoTotal()
    {
        $montoTotal = $this->detallePedido->sum('monto');
        $this->total = $montoTotal;
        $this->save();

        return $this->detallePedido(); // Return the updated detallesPedido relationship

    }

}
