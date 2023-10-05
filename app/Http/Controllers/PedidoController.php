<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedidos = Pedido::all();
        $productos = Producto::all();

        return view('pedido.index', compact('pedidos', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pedido.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required',
        ], [
            'fecha.required' => 'El campo fecha es obligatorio.',
        ]);

        $pedido = new Pedido();
        $pedido->fecha = $request->input('fecha');

        // Actualizar el monto total del pedido
        $pedido->total = $pedido->detallePedido->sum('monto');
        $pedido->save();

        return redirect('pedidos')->with('success', 'El pedido se ha guardado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pedido = Pedido::find($id);
        return view('pedido.edit')->with('pedido', $pedido);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pedido = Pedido::find($id);
        $pedido->delete();
        return redirect('pedidos')->with('eliminar', 'ok');
    }
}
