@extends('adminlte::page')

@section('title', 'Editar Pedido')

@section('content_header')
    <h1>Editar Pedido</h1>
@stop

@section('content')
    <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" id="fecha" name="fecha" class="form-control" tabindex="1" value="{{ $pedido->fecha }}">
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado del Pedido</label>
            <select id="estado" name="estado" class="form-control" tabindex="2">
                <option value="esperando" {{ $pedido->estado === 'esperando' ? 'selected' : '' }}>En Espera</option>
                <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
            @error('estado')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select id="cliente_id" name="cliente_id" class="form-control" tabindex="3">
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $pedido->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->name }}</option>
                @endforeach
            </select>
            @error('cliente_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="repartidor_id" class="form-label">Repartidor</label>
            <select id="repartidor_id" name="repartidor_id" class="form-control" tabindex="4">
                @foreach ($repartidores as $repartidor)
                    <option value="{{ $repartidor->id }}" {{ $pedido->repartidor_id == $repartidor->id ? 'selected' : '' }}>{{ $repartidor->name }}</option>
                @endforeach
            </select>
            @error('repartidor_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="/pedidos" class="btn btn-secondary" tabindex="6">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit" class="btn btn-primary" tabindex="7">Guardar</button>
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
                text: 'El pedido se ha guardado exitosamente.',
                icon: 'success'
            });
        </script>
    @endif
    @parent
@stop
