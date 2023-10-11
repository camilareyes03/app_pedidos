@extends('adminlte::page')

@section('title', 'Detalle de Pedido')

@section('content_header')
    <h1>Detalle de Pedido</h1>
@stop

@section('content')
    <br>
    <a style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" href="{{ route('pedidos.index') }}" class="btn btn-primary">Volver</a> <!-- Botón Volver -->
    <br> <br>
    <h2>Monto Total: {{ number_format($montoTotal, 2) }}</h2>


    <table id="detallepedido" class="table table-striped table-bordered" style="width: 100%">
        <thead class="bg-primary text-white">
            <tr>
                <th style="background-color: #4b545c">ID</th>
                <th style="background-color: #4b545c">ID Pedido</th>
                <th style="background-color: #4b545c">Producto</th>
                <th style="background-color: #4b545c">Precio</th>
                <th style="background-color: #4b545c">Cantidad</th>
                <th style="background-color: #4b545c">Subtotal</th>
                <th style="background-color: #4b545c">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->id }}</td>
                    <td>{{ $detalle->pedido_id }}</td>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->producto->precio }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->monto }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('detallepedido.destroy', $detalle->id) }}"
                            method="POST">
                            <a href="{{ route('detallepedido.edit', $detalle->id) }}" class="btn btn-info">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#detallepedido').DataTable();
    </script>

    @if (session('eliminar') == 'ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'Tu producto ha sido eliminado exitosamente',
                'success'
            )
        </script>
    @endif

    @if (session('edit-success'))
        <script>
            Swal.fire(
                'Exito!',
                'El detalle del pedido ha sido editada exitosamente',
                'success'
            )
        </script>
    @endif

    <script>
        $('.formulario-eliminar').submit(function(evento) {
            evento.preventDefault();

            Swal.fire({
                title: 'Estas seguro?',
                text: "Este producto se eliminara definitivamente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        })
    </script>
@stop
