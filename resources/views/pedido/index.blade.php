@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    @if (Request::is('proforma*'))
        <h1>Listado de Proformas</h1>
    @elseif (Request::is('oficial*'))
        <h1>Listado de Pedidos Oficiales</h1>
    @else
        <h1>Listado de Pedidos</h1>
    @endif
@stop

@section('content')
    <a href="pedidos/create" style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);"
        class="btn btn-primary">Registrar</a>
    <br> <br>
    <table id="pedidos" class="table table-striped table-bordered" style="width: 100%; ">
        <thead class="bg-primary text-white">
            <tr>
                <th style="background-color: #4b545c" scope="col">ID</th>
                <th style="background-color: #4b545c" scope="col">Cliente</th>
                <th style="background-color: #4b545c" scope="col">Fecha</th>
                <th style="background-color: #4b545c" scope="col">Tipo</th>
                @if (request()->is('oficial*'))
                    <th style="background-color: #4b545c" scope="col">Método de Pago</th>
                @endif
                <th style="background-color: #4b545c" scope="col">Monto Total</th>
                <th style="background-color: #4b545c" scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->user_cliente->name }}</td>
                    <td>{{ $pedido->fecha }}</td>
                    <td>{{ $pedido->tipo_pedido }}</td>
                    @if (request()->is('oficial*'))
                        <td>{{ $pedido->tipo_pago }}</td>
                    @endif
                    <td>{{ $pedido->total }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('pedidos.destroy', $pedido->id) }}"
                            method="POST">
                            <a href="{{ route('pedido.pdf', ['id' => $pedido->id]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a>

                            <a href="{{ route('pedido.csv', ['id' => $pedido->id]) }}" class="btn btn-success">
                                <i class="fas fa-file-csv"></i>
                            </a>

                            <a href="{{ route('detallepedido.show', $pedido->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </a>

                            <button type="button" class="btn btn-secondary btn-detalles"
                                data-pedido-id="{{ $pedido->id }}" data-toggle="modal"
                                data-target="#agregarProductoModal">
                                Agregar Productos
                            </button>

                            <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning">
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
                <form id="agregarProductoForm" action="{{ route('detallepedido.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="producto_id">Producto:</label>
                            <select class="form-control" id="producto_id" name="producto_id">
                                <option value="nulo">Seleccione un Producto</option>
                                @foreach ($productos as $producto)
                                    @if ($producto->stock == 0)
                                        <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}"
                                            data-foto="{{ asset($producto->foto) }}" disabled>
                                            {{ $producto->nombre }} - No hay stock
                                        </option>
                                    @else
                                        <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}"
                                            data-foto="{{ asset($producto->foto) }}">
                                            {{ $producto->nombre }} - Stock: {{ $producto->stock }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1">
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="text" class="form-control" id="precio" name="precio" readonly>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto:</label>
                            <img id="foto" src="" alt="Foto del producto" width="100" height="100">
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
    @if (session('eliminar-detalle') == 'ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'El producto ha sido eliminado del pedido exitosamente',
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
    <script>
        $(document).ready(function() {
            // Al cargar la página, ocultamos el campo de precio y la imagen del producto
            $('#precio').val('');
            $('#foto').attr('src', ''); // Establece el atributo src a una cadena vacía

            $('#producto_id').change(function() {
                // Cuando cambia la selección de producto
                var selectedOption = $(this).find(':selected');
                var precio = selectedOption.data('precio');
                var foto = selectedOption.data('foto');

                // Actualizamos el campo de precio y la imagen del producto
                $('#precio').val(precio);
                $('#foto').attr('src', foto); // Actualiza el atributo src
            });
        });
    </script>
    <script>
        $('#pedidos').DataTable();

        // Mostrar el modal de agregar producto al pedido
        $('.btn-detalles').click(function() {
            var pedidoId = $(this).data('pedido-id');
            $('#pedido_id').val(pedidoId);
        });

        $('#agregarProductoForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            // Petición AJAX para agregar el producto al pedido
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
                    // Petición AJAX para actualizar la tabla de detalles del pedido
                    $.ajax({
                        url: '/detallepedido/show/' + response.pedido_id,
                        type: 'GET',
                        success: function(response) {
                            $('#detalles-pedido-table').DataTable().clear().rows.add(
                                response).draw();
                        },
                        error: function(xhr) {
                            // Manejar errores
                        }
                    });
                },
            });
        });
    </script>


@stop
