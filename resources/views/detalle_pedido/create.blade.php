@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
    <form action="/detallepedido" method="POST">
        @csrf
        <div class="mb-3">
            <label for="idProducto" class="form-label">Selecciona el Producto</label>

            <select name="idProducto" id="select-room" class="form-control" onchange="habilitar()">
                <option value="nulo">Productos: </option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}"> {{ $producto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="text" id="cantidad" name="cantidad" class="form-control" tabindex="2">
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" id="precio" name="precio" class="form-control" tabindex="3">
        </div>

        <a href="/detallepedido" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success') == 'ok')
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'El detalle del pedido se ha guardado exitosamente.',
                icon: 'success'
            });
        </script>
    @endif
    @parent
@stop
