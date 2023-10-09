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

        return view('pedido.index', compact('pedidos', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function create()
     {
         // Obtener la lista de usuarios que son clientes
         $clientes = User::where('tipo_usuario', 'cliente')->get();
         //dd($clientes);
         $repartidores = User::where('tipo_usuario', 'repartidor')->get();

         return view('pedido.create', compact('clientes', 'repartidores'));
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
        $pedido->repartidor_id = $request->input('repartidor_id');

        // Agregar el estado del pedido
        $pedido->estado = $request->input('estado');
        $pedido->total = 0.0;

        $pedido->save();

        return redirect('pedidos')->with('success', 'El pedido se ha guardado exitosamente.');
    }


    public function validarDatos(Request $request)
    {
        $reglas = [
            'fecha' => 'required',
            'cliente_id' => 'required|exists:users,id,tipo_usuario,cliente',
            'repartidor_id' => 'required|exists:users,id,tipo_usuario,repartidor',
            'estado' => 'required|in:entregado,cancelado,espera', // Agrega esta línea para validar el estado
        ];

        $mensajes = [
            'fecha.required' => 'El campo fecha es obligatorio.',
            'cliente_id.required' => 'El campo cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'repartidor_id.required' => 'El campo repartidor es obligatorio.',
            'repartidor_id.exists' => 'El repartidor seleccionado no es válido.',
            'estado.required' => 'El campo estado es obligatorio.', // Mensaje para el estado requerido
            'estado.in' => 'El estado seleccionado no es válido.', // Mensaje para estados no válidos
        ];

        $request->validate($reglas, $mensajes);
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
