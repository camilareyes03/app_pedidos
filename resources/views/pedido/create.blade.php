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
        <a href="/pedidos" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
