@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
    <form action="/productos" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" tabindex="1">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" id="precio" name="precio" class="form-control" tabindex="2">
            @error('precio')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" id="stock" name="stock" class="form-control" tabindex="3">
            @error('stock')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="categoria_id" class="form-label">Selecciona la Categoria</label>

            <select name="categoria_id" id="select-room" class="form-control" onchange="habilitar()">
                <option value="nulo">Categorias: </option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}"> {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>


            <div class="mb-3">
                <br>
                <label for="foto" class="form-label">{{ __('Selecciona una Imagen') }}</label>
                <input type="file" id="foto" class="form-control" name="foto" accept="image/*">
                <br>
                @error('foto')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <a href="/productos" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit" class="btn btn-primary" tabindex="3">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('warning'))
        <script>
            Swal.fire(
                'Advertencia',
                '{{ session('warning') }}',
                'warning'
            );
        </script>
    @endif
@stop
