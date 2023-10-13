<!DOCTYPE html>
<html>

<head>
    <title>
        @if ($pedido->tipo_pedido === 'proforma')
            Proforma
        @elseif ($pedido->tipo_pedido === 'oficial')
            Pedido Oficial
        @else
            Nota de Venta
        @endif
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 24px;
        }

        p {
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        thead {
            background-color: #333;
            color: #fff;
        }

        th {
            font-weight: bold;
        }

        tfoot {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        @if ($pedido->tipo_pedido === 'proforma')
            <h1>Proforma</h1>
        @elseif ($pedido->tipo_pedido === 'oficial')
            <h1>Pedido Oficial</h1>
        @else
            Nota de Venta
        @endif
        <p><strong>Cliente:</strong> {{ $pedido->user_cliente->name }}</p>
{{--         <p><strong>Dirección del Cliente:</strong> {{ $pedido->user_cliente->ubicacion->nombre }}</p> --}}
        <p><strong>Teléfono del Cliente:</strong> {{ $pedido->user_cliente->telefono }}</p>
        <p><strong>Carnet de Identidad del Cliente:</strong> {{ $pedido->user_cliente->ci}}</p>

        <!-- Otros campos del cliente que desees mostrar -->
        @if ($pedido->tipo_pedido === 'oficial')
            <p><strong>Método de Pago:</strong> {{ $pedido->tipo_pago }}</p>
        @endif
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedido->detallePedido as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->producto->precio }}</td>
                        <td>{{ $detalle->monto }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total:</td>
                    <td>{{ $pedido->total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
