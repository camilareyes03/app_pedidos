@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>Listado de Pedidos</h1>
@stop

@section('content')
    <a href="pedidos/create" class="btn btn-primary ">Registrar</a>
    <br> <br>
    <table id="pedidos" class="table table-striped table-bordered" style="width: 100%">
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Cliente</th>
                <th scope="col">Repartidor</th>
                <th scope="col">Fecha</th>
                <th scope="col">Estado</th>
                <th scope="col">Monto Total</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->user_cliente->name }}</td>
                    <td>{{ $pedido->user_repartidor->name }}</td>
                    <td>{{ $pedido->fecha }}</td>
                    <td>{{ $pedido->estado }}</td>
                    <td>{{ $pedido->total }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('pedidos.destroy', $pedido->id) }}"
                            method="POST">
                            <a href="{{ route('detallepedido.show', $pedido->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </a>

                            @if ($pedido->estado !== 'entregado')
                                <a href="{{ route('detallepedido.create', ['pedido_id' => $pedido->id]) }}"
                                    class="btn btn-success">
                                    <i class="fas fa-plus"></i> Agregar Productos
                                </a>

                                <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            @else
                                <!-- Si el pedido está entregado, muestra un mensaje o icono de "No disponible" -->
                                <span class="text-muted"><i class="fas fa-ban"></i> No disponible</span>
                            @endif
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        $('#pedidos').DataTable();
    </script>

    @if (session('eliminar') == 'ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'El pedido ha sido eliminado exitosamente',
                'success'
            )
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire(
                'Exito!',
                'Tu pedido ha sido creado exitosamente',
                'success'
            )
        </script>
    @endif
    @if (session('success-detalle'))
        <script>
            Swal.fire(
                'Exito!',
                'El producto ha sido agregado correctamente al pedido',
                'success'
            )
        </script>
    @endif
    @if (session('edit-success'))
        <script>
            Swal.fire(
                'Exito!',
                'El pedido ha sido editada exitosamente',
                'success'
            )
        </script>
    @endif

    <script>
        $('.formulario-eliminar').submit(function(evento) {
            evento.preventDefault();

            Swal.fire({
                title: 'Estas seguro?',
                text: "Este pedido se eliminará definitivamente",
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
