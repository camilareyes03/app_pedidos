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
                <th scope="col">Fecha</th>
                <th scope="col">Monto Total</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->fecha }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('pedidos.destroy', $pedido->id) }}"
                            method="POST">
                            <a href="{{ route('detallepedido.show', $pedido->id) }}" class="btn btn-info">Ver Detalles</a>
                            <button type="button" class="btn btn-secondary btn-detalles"
                                data-pedido-id="{{ $pedido->id }}" data-toggle="modal"
                                data-target="#agregarProductoModal">
                                Agregar Productos
                            </button>
                            <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-info">Editar</a>

                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>

                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="agregarProductoModal" tabindex="-1" role="dialog"
        aria-labelledby="agregarProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarProductoModalLabel">Agregar Producto al Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="agregarProductoForm" action="{{ route('detallepedido.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="producto_id">Producto:</label>
                            <select class="form-control" id="producto_id" name="producto_id">
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1">
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" class="form-control" id="precio" name="precio" min="0"
                                step="0.01">
                        </div>

                        <input type="hidden" id="pedido_id" name="pedido_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        $('#pedidos').DataTable();


        // Mostrar el modal de agregar producto al pedido
        //capturar el ID del pedido guardarlo en un campo oculto en el formulario del modal

        $('.btn-detalles').click(function() {
            var pedidoId = $(this).data('pedido-id');
            $('#pedido_id').val(pedidoId);
        });

        $('#agregarProductoForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            //petición AJAX para agregar el producto al pedido
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#agregarProductoModal').modal('hide');
                    // Mostrar notificación de éxito
                    Swal.fire(
                        'Producto agregado',
                        'El producto se ha agregado al pedido exitosamente.',
                        'success'
                    );
                    // petición AJAX para Actualizar la tabla de detalles del pedido
                    $.ajax({
                        url: '/detallepedido/show/' + response
                            .pedido_id,
                        type: 'GET',
                        success: function(response) {
                            $('#detalles-pedido-table').DataTable().clear().rows.add(
                                response).draw();
                            location.reload(); // Recargar la pagina
                        },
                        error: function(xhr) {
                            // Manejar errores
                        }
                    });
                },
                error: function(xhr) {
                    // Aquí puedes manejar los errores y mostrar mensajes de error al usuario
                }
            });
        });
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
