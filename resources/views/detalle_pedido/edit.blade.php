@extends('adminlte::page')

@section('title', 'Editar Detalle de Pedido')

@section('content_header')
    <h1>Editar Detalle de Pedido</h1>
@stop

@section('content')
    <form action="/detallepedido/{{ $detalle->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="producto_id" class="form-label">Selecciona el Producto</label>
            <select name="producto_id" id="producto_id" class="form-control">
                <option value="nulo">Seleccione un Producto</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}"
                        data-stock="{{ $producto->stock }}" data-foto="{{ asset($producto->foto) }}"
                        {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                        {{ $producto->nombre }} - Stock: {{ $producto->stock }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="text" id="cantidad" name="cantidad" class="form-control" tabindex="2"
                value="{{ $detalle->cantidad }}">
        </div>

        <!-- Agrega un elemento para mostrar el stock seleccionado -->
        <div id="stock-seleccionado" class="text-muted">Stock Disponible:</div>

        <a href="/pedidos" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
    </form>

    <script>
        function actualizarStock() {
            var productoSelect = document.getElementById('producto_id');
            var stockSeleccionado = document.getElementById('stock-seleccionado');

            var selectedOption = productoSelect.options[productoSelect.selectedIndex];
            var stock = selectedOption.getAttribute('data-stock');

            stockSeleccionado.textContent = 'Stock Disponible: ' + stock;
        }

        // Llama a actualizarStock al cargar la página
        actualizarStock();
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    </style>

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success') == 'ok')
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'El producto se ha editado exitosamente en el pedido',
                icon: 'success'
            });
        </script>
    @endif


@stop
