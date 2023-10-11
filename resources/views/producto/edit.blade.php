@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1>Editar Producto</h1>
@stop

@section('content')
    <form action="/productos/{{ $producto->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                value="{{ old('nombre', $producto->nombre) }}">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" id="precio" name="precio" class="form-control"
                value="{{ old('precio', $producto->precio) }}">
            @error('precio')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" id="stock" name="stock" class="form-control"
                value="{{ old('stock', $producto->stock) }}">
            @error('stock')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="mb-3">
            <label for="categoria_id" class="form-label">{{ __('Categoria') }}</label>
            <select id="categoria_id" class="form-control" name="categoria_id">
                <option value="{{ old('categoria_id', $producto->categoria_id) }}">{{ $producto->categoria->nombre }}
                </option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">{{ __('Foto del Producto') }}</label>
            <input type="file" id="foto" class="form-control" name="foto">
            @error('foto')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="/productos" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
    </form>
@stop
