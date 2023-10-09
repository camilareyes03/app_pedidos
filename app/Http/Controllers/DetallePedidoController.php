<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $this->validarDetallePedido($request);

        // Obtener los datos del formulario
        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad');
        $pedidoId = $request->input('pedido_id');

        // Validar si el producto ya existe en el detalle del pedido
        $detallePedidoExistente = DetallePedido::where('pedido_id', $pedidoId)
            ->where('producto_id', $productoId)
            ->first();

        if ($detallePedidoExistente) {
            // Si el producto ya existe, simplemente suma la cantidad
            $detallePedidoExistente->cantidad += $cantidad;
            $detallePedidoExistente->monto = $detallePedidoExistente->cantidad * $detallePedidoExistente->producto->precio;
            $detallePedidoExistente->save();
        } else {
            // Si el producto no existe, crea un nuevo registro
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
        }

        // Actualizar el campo montoTotal del pedido correspondiente
        $pedido = Pedido::find($pedidoId);
        $pedido->actualizarMontoTotal();

        return redirect()->route('pedidos.index')->with('success-detalle', 'El producto se ha guardado exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pedido = Pedido::findOrFail($id);
        $detalles = $pedido->detallePedido;
        $productos = Producto::all();

        // Calcular el monto total sumando todos los subtotales
        $montoTotal = $detalles->sum('monto');

        return view('detalle_pedido.index', compact('detalles', 'productos', 'montoTotal'));
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


protected function validarDetallePedido(Request $request)
{
    return $request->validate([
        'producto_id' => 'required',
        'cantidad' => [
            'required',
            'numeric',
            'min:1',
            Rule::unique('detalle_pedidos')->where(function ($query) use ($request) {
                return $query->where('producto_id', $request->input('producto_id'))
                    ->where('pedido_id', $request->input('pedido_id'));
            }),
        ],
        'pedido_id' => 'required',
    ], [
        'producto_id.required' => 'El campo producto es obligatorio.',
        'cantidad.required' => 'El campo cantidad es obligatorio.',
        'cantidad.numeric' => 'El campo cantidad debe ser numérico.',
        'cantidad.min' => 'El campo cantidad debe ser mayor o igual a 1.',
        'cantidad.unique' => 'Este producto ya existe en el detalle del pedido. Actualiza la cantidad si es necesario.',
        'pedido_id.required' => 'El campo pedido es obligatorio.',
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetallePedido $detallePedido)
    {
        //
    }
}
