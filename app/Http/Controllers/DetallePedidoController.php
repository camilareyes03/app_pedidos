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

            // Verificar el tipo de pedido y actualizar el stock del producto
            $pedido = Pedido::find($pedidoId);
            if ($pedido->tipo_pedido === 'oficial') {
                $producto->stock -= $cantidad;
                $producto->save();
            }
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
        // Validar los datos del formulario
        $this->validate($request, [
            'producto_id' => 'required|exists:producto,id',
            'cantidad' => 'required|numeric|min:1',
        ]);

        // Obtener el detalle a editar
        $detalle = DetallePedido::findOrFail($id);

        // Obtener la nueva cantidad y el producto seleccionado
        $nuevaCantidad = $request->input('cantidad');
        $nuevoProductoId = $request->input('producto_id');
        $pedidoId = $detalle->pedido_id; // Obtener el pedido al que pertenece el detalle

        // Calcular la diferencia en la cantidad
        $diferenciaCantidad = $nuevaCantidad - $detalle->cantidad;

        // Actualizar el detalle con los nuevos valores
        $detalle->producto_id = $nuevoProductoId;
        $detalle->cantidad = $nuevaCantidad;

        // Obtener el precio del producto seleccionado
        $producto = Producto::find($nuevoProductoId);
        $precioProducto = $producto->precio;

        // Calcular el monto del detalle (precio del producto * cantidad)
        $detalle->monto = $precioProducto * $nuevaCantidad;

        $detalle->save();

        // Actualizar el stock del producto según la diferencia en la cantidad
        $producto->stock -= $diferenciaCantidad;
        $producto->save();

        // Actualizar el campo montoTotal del pedido correspondiente
        $pedido = Pedido::find($pedidoId);
        $pedido->actualizarMontoTotal();

        // Redirigir a donde desees después de la actualización
        return redirect('/pedidos')->with('success', 'El detalle del pedido se ha actualizado exitosamente.');
    }



protected function validarDetallePedido(Request $request)
{
    return $request->validate([
        'producto_id' => 'required',
        'cantidad' => [
            'required',
            'numeric',
            'min:1',
            Rule::unique('detalle_pedido')->where(function ($query) use ($request) {
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
    public function destroy(string $id)
    {
        $detalle = DetallePedido::find($id);
        $pedidoId = $detalle->pedido_id;

        // Obtener la cantidad del detalle a eliminar
        $cantidadEliminada = $detalle->cantidad;

        // Obtener el producto del detalle
        $producto = $detalle->producto;

        // Actualizar el stock del producto al eliminar el detalle
        $producto->stock += $cantidadEliminada;
        $producto->save();

        $detalle->delete();

        $pedido = Pedido::find($pedidoId);
        $pedido->actualizarMontoTotal();

        return redirect('pedidos')->with('eliminar-detalle', 'ok');
    }


}
