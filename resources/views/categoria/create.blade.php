@extends('adminlte::page')

@section('title', 'Crear Categoria')

@section('content_header')
    <h1>Crear Categoria</h1>
@stop

@section('content')
    <form action="/categorias" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" tabindex="1">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="/categorias" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

