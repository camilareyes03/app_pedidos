@extends('adminlte::page')

@section('title', 'Crear Pedido')

@section('content_header')
    <h1>Crear Pedido</h1>
@stop

@section('content')
    <form action="{{ route('pedidos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" id="fecha" name="fecha" class="form-control" tabindex="1">
            @error('fecha')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado del Pedido</label>
            <select id="estado" name="estado" class="form-control" tabindex="2">
                <option value="espera">En Espera</option>
                <option value="entregado">Entregado</option>
                <option value="cancelado">Cancelado</option>
            </select>
            @error('estado')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select id="cliente_id" name="cliente_id" class="form-control" tabindex="3">
                <option value="">Seleccionar un cliente</option> <!-- Opción inicial -->
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                @endforeach
            </select>
            @error('cliente_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="repartidor_id" class="form-label">Repartidor</label>
            <select id="repartidor_id" name="repartidor_id" class="form-control" tabindex="4">
                <option value="">Seleccionar un repartidor</option> <!-- Opción inicial -->
                @foreach ($repartidores as $repartidor)
                    <option value="{{ $repartidor->id }}">{{ $repartidor->name }}</option>
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
