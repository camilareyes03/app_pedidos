<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
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

        // Verifica si no hay productos y redirige al usuario a la creación de productos
        if ($productos->isEmpty()) {
            return redirect()->route('productos.create')
                ->with('warning', 'No tienes productos registrados. Debes crear al menos uno.');
        }

        return view('pedido.index', compact('pedidos', 'productos'));
    }


    public function proforma()
    {
        $pedidos = Pedido::where('tipo_pedido', 'proforma')->get();
        $productos = Producto::all();

        return view('pedido.index', compact('pedidos', 'productos'));
    }

    public function oficial()
    {
        $pedidos = Pedido::where('tipo_pedido', 'oficial')->get();
        $productos = Producto::all();

        return view('pedido.index', compact('pedidos', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function create()
    {
        $clientes = User::where('tipo_usuario', 'cliente')->get();
        if ($clientes->isEmpty()) {
            return redirect()->route('personas.create')
                ->with('warning', 'No tienes clientes registrados. Debes registrar al menos uno.');
        }
        return view('pedido.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validarDatos($request);

        $pedido = new Pedido();
        $pedido->fecha = $request->input('fecha');
        $pedido->cliente_id = $request->input('cliente_id');

        if ($request->input('tipo_pedido') === 'oficial') {
            $pedido->tipo_pago = $request->input('tipo_pago');
        } else {
            $pedido->tipo_pago = null;
        }

        $pedido->tipo_pedido = $request->input('tipo_pedido');
        $pedido->total = 0.0;

        $pedido->save();

        return redirect('pedidos')->with('success', 'El pedido se ha guardado exitosamente.');
    }


    public function validarDatos(Request $request)
    {
        $rules = [
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:users,id',
            'tipo_pedido' => 'required|in:proforma,oficial',
        ];

        $messages = [
            'fecha.required' => 'El campo Fecha es obligatorio.',
            'fecha.date' => 'El campo Fecha debe ser una fecha válida.',
            'cliente_id.required' => 'El campo Cliente es obligatorio.',
            'cliente_id.exists' => 'El Cliente seleccionado no es válido.',
            'tipo_pedido.required' => 'El campo Tipo de Pedido es obligatorio.',
            'tipo_pedido.in' => 'El Tipo de Pedido debe ser "proforma" o "oficial.', // Nuevo mensaje para tipo_pedido
        ];

        $request->validate($rules, $messages);
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
        $clientes = User::where('tipo_usuario', 'cliente')->get();
        $repartidores = User::where('tipo_usuario', 'repartidor')->get();

        return view('pedido.edit', compact('pedido', 'clientes', 'repartidores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validarDatos($request);

        $pedido = Pedido::find($id);
        $pedido->fecha = $request->get('fecha');
        $pedido->cliente_id = $request->input('cliente_id');
        $pedido->repartidor_id = $request->input('repartidor_id');

        // Agregar el estado del pedido
        $pedido->estado = $request->input('estado');

        $pedido->save();

        return redirect('pedidos')->with('edit-success', 'El pedido se ha actualizado exitosamente.');
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
