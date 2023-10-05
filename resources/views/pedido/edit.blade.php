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
                text: 'El pedido se ha guardado exitosamente.',
                icon: 'success'
            });
        </script>
    @endif
    @parent
@stop
