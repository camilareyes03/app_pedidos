<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pedidoId = $request->input('pedido_id');
        $productos = Producto::all();
        return view('detalle_pedido.create', compact( 'productos', 'pedidoId'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required',
            'cantidad' => 'required|numeric|min:1',
            'pedido_id' => 'required'
        ]);

        // Obtener los datos del formulario
        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad');
        $pedidoId = $request->input('pedido_id');

        // Obtener el precio del producto seleccionado
        $producto = Producto::find($productoId);
        $precioProducto = $producto->precio;

        // Calcular el subtotal
        $subTotal = $cantidad * $precioProducto;

        // Guardar el detalle del pedido en la base de datos
        $detallePedido = new DetallePedido();
        $detallePedido->pedido_id = $pedidoId;
        $detallePedido->producto_id = $productoId;
        $detallePedido->cantidad = $cantidad;
        $detallePedido->monto = $subTotal;
        $detallePedido->save();

        // Actualizar el campo montoTotal del pedido correspondiente
        $pedido = Pedido::find($pedidoId);
        $pedido->actualizarMontoTotal();

        return redirect()->action([PedidoController::class, 'show'], ['pedido' => $pedido])->with('success', 'Detalle de pedido agregado exitosamente');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pedido = Pedido::findOrFail($id);
        $detalles = $pedido->detallePedido;
        $productos = Producto::all();


        return view('detalle_pedido.index', compact('detalles', 'productos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detalle = DetallePedido::find($id);
        $productos = Producto::all();
        return view('detalle_pedido.edit', compact('detalle', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $detalle = DetallePedido::find($id);

    if (!$detalle) {
        return redirect()->route('detallepedido')->with('error', 'Detalle de pedido no encontrado');
    }

    $detalle->cantidad = $request->input('cantidad');

    // Obtener el precio del producto seleccionado
    $producto = Producto::find($request->input('producto_id'));
    $precioProducto = $producto->precio;

    // Calcular el monto
    $detalle->monto = $detalle->cantidad * $precioProducto;

    $detalle->producto_id = $request->input('producto_id'); // Actualizar el campo idProducto
    $detalle->save();

    // Actualizar el campo montoTotal del pedido correspondiente
    $pedido = Pedido::find($detalle->pedido_id);
    $pedido->actualizarMontoTotal();

    return redirect()->route('detallepedido.show', $detalle->pedido_id)->with('edit-success', 'Detalle de pedido actualizado exitosamente');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetallePedido $detallePedido)
    {
        //
    }
}
