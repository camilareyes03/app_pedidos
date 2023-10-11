<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';

    protected $fillable = [
        'fecha', 'total', 'estado', 'tipo_pedido', 'tipo_pago', 'cliente_id'
    ];

    use HasFactory;

    public function user_cliente(){
        return $this->belongsTo(User::class,'cliente_id');
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
