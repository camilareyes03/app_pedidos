<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedidos = Pedido::all();
        $productos = Producto::all();
        if ($productos->isEmpty()) {
            return redirect()->route('productos.create')
                ->with('warning', 'No tienes productos registrados. Debes crear al menos uno.');
        }

        // Mapea los pedidos a sus enlaces de descarga de PDF
        $pdfLinks = $pedidos->map(function ($pedido) {
            return [
                'pedido' => $pedido,
                'pdfRoute' => route('pedido.pdf', ['id' => $pedido->id]),
                'csvRoute' => route('pedido.csv', ['id' => $pedido->id]),
            ];
        });

        return view('pedido.index', compact('pdfLinks', 'productos', 'pedidos'));
    }


    public function proforma()
    {
        $tipo = 'proforma';
        $pedidos = Pedido::where('tipo_pedido', 'proforma')->get();
        $productos = Producto::all();

        // Mapea los pedidos a sus enlaces de descarga de PDF
        $pdfLinks = $pedidos->map(function ($pedido) {
            return [
                'pedido' => $pedido,
                'pdfRoute' => route('pedido.pdf', ['id' => $pedido->id]),
                'csvRoute' => route('pedido.csv', ['id' => $pedido->id]),
            ];
        });

        return view('pedido.index', compact('pdfLinks', 'productos', 'pedidos'));
    }

    public function oficial()
    {
        $tipo = 'oficial';
        $pedidos = Pedido::where('tipo_pedido', 'oficial')->get();
        $productos = Producto::all();

        // Mapea los pedidos a sus enlaces de descarga de PDF
        $pdfLinks = $pedidos->map(function ($pedido) {
            return [
                'pedido' => $pedido,
                'pdfRoute' => route('pedido.pdf', ['id' => $pedido->id]),
                'csvRoute' => route('pedido.csv', ['id' => $pedido->id]),
            ];
        });

        return view('pedido.index', compact('pdfLinks', 'productos', 'pedidos'));
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

        return view('pedido.edit', compact('pedido', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $this->validarDatos($request);

        // Verificar si el tipo de pedido ha cambiado
        $tipoPedidoAnterior = $pedido->tipo_pedido;
        $tipoPedidoNuevo = $request->input('tipo_pedido');

        if ($tipoPedidoAnterior !== $tipoPedidoNuevo) {
            if ($tipoPedidoNuevo === 'oficial') {
                // El tipo de pedido cambió a 'oficial', por lo que necesitamos actualizar el stock de los productos en los detalles del pedido
                $detallesPedido = DetallePedido::where('pedido_id', $pedido->id)->get();

                foreach ($detallesPedido as $detalle) {
                    $producto = Producto::find($detalle->producto_id);
                    $producto->stock -= $detalle->cantidad;
                    $producto->save();
                }
            } elseif ($tipoPedidoAnterior === 'oficial') {
                // El tipo de pedido cambió de 'oficial' a otro tipo, por lo que necesitamos revertir la actualización del stock
                $detallesPedido = DetallePedido::where('pedido_id', $pedido->id)->get();

                foreach ($detallesPedido as $detalle) {
                    $producto = Producto::find($detalle->producto_id);
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }
        }

        // Actualiza los campos del pedido con los datos del formulario
        $pedido->fecha = $request->input('fecha');
        $pedido->cliente_id = $request->input('cliente_id');

        if ($tipoPedidoNuevo === 'oficial') {
            $pedido->tipo_pago = $request->input('tipo_pago');
        } else {
            $pedido->tipo_pago = null;
        }

        $pedido->tipo_pedido = $tipoPedidoNuevo;
        $pedido->save();

        return redirect('pedidos')->with('success', 'El pedido se ha actualizado exitosamente.');
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


    public function descargarPdf($id)
    {
        $pedido = Pedido::find($id);

        $pdf = new Dompdf();
        $html = view('pedido.pdf', compact('pedido'))->render();
        $pdf->loadHtml($html);
        $pdf->render();

        return $pdf->stream("pedido_{$id}.pdf");
    }

    public function descargarCsv($id)
    {
        $pedido = Pedido::find($id);
        $detalles = $pedido->detallePedido;

        // Crear el contenido CSV para los detalles del pedido
        $csvData = '';
        $csvHeader = ['Producto', 'Cantidad', 'Monto'];
        $csvData .= implode(',', $csvHeader) . "\n";
        foreach ($detalles as $detalle) {
            $csvRow = [
                $detalle->producto->nombre,
                $detalle->cantidad,
                $detalle->monto,
            ];
            $csvData .= implode(',', $csvRow) . "\n";
        }

        // Establecer las cabeceras de respuesta
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=pedido_{$id}_detalles.csv",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $response = new Response($csvData, 200, $headers);

        return $response;
    }







}
