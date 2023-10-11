@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
    <form action="/detallepedido" method="POST">
        @csrf
        <div class="mb-3">
            <input type="hidden" name="pedido_id" value="{{ $pedidoId }}">
            <label for="producto_id" class="form-label">Selecciona el Producto</label>
            <select name="producto_id" id="select-room" class="form-control" onchange="habilitar()">
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}"> {{ $producto->nombre }}</option>
                @endforeach
            </select>
            @error('producto_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="text" id="cantidad" name="cantidad" class="form-control" tabindex="2">
            @error('cantidad')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="/pedidos" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
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
                text: 'El producto se ha agregado exitosamente en el pedido',
                icon: 'success'
            });
        </script>
    @endif
    @parent
@stop
