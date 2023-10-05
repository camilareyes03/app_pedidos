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

            <select name="producto_id" id="select-room" class="form-control" onchange="habilitar()">
                <option value="nulo">Productos: </option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}"
                        @if ($detalle->producto_id == $producto->id) selected
                        @endif>
                        {{ $producto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="text" id="cantidad" name="cantidad" class="form-control" tabindex="2"
                value="{{ $detalle->cantidad }}">
        </div>

        <a href="/pedidos" class="btn btn-secondary" tabindex="4">Cancelar</a>
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
                text: 'El producto se ha editado exitosamente en el pedido',
                icon: 'success'
            });
        </script>
    @endif
    @parent
@stop
