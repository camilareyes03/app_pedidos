@extends('adminlte::page')

@section('title', 'Detalle de Pedido')

@section('content_header')
    <h1>Detalle de Pedido</h1>
@stop

@section('content')
    <br>
    <a href="{{ route('pedidos.index') }}" class="btn btn-primary">Volver</a> <!-- Botón Volver -->
    <br> <br>
    <h2>Monto Total: {{ $montoTotal }}</h2>

    <table id="detallepedido" class="table table-striped table-bordered" style="width: 100%">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>ID Pedido</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->id }}</td>
                    <td>{{ $detalle->pedido_id }}</td>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->precio }}</td>
                    <td>{{ $detalle->monto }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('detallepedido.destroy', $detalle->id) }}"
                            method="POST">
                            <button type="button" class="btn btn-info btn-editar" data-detalle-id="{{ $detalle->id }}"
                                data-toggle="modal" data-target="#editarDetalleModal{{ $detalle->id }}">Editar</button>

                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Editar Detalle -->
                <div class="modal fade" id="editarDetalleModal{{ $detalle->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editarDetalleModalLabel{{ $detalle->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarDetalleModalLabel{{ $detalle->id }}">Editar Detalle de
                                    Pedido</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editarDetalleForm{{ $detalle->id }}"
                                action="{{ route('detallepedido.update', $detalle->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="idProducto">Producto:</label>
                                        <select class="form-control" id="idProducto{{ $detalle->id }}" name="idProducto">
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}" {{ $detalle->idProducto == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad:</label>
                                        <input type="number" class="form-control" id="cantidad{{ $detalle->id }}"
                                            name="cantidad" min="1" value="{{ $detalle->cantidad }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="precio">Precio:</label>
                                        <input type="number" class="form-control" id="precio{{ $detalle->id }}"
                                            name="precio" min="0" step="0.01" value="{{ $detalle->precio }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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

        $('.btn-editar').click(function() {
            var detalleId = $(this).data('detalle-id');
            var productoId = $('#producto' + detalleId).val();
            var cantidad = $('#cantidad' + detalleId).val();
            var precio = $('#precio' + detalleId).val();

            $('#editarDetalleForm' + detalleId + ' #idProducto').val(
            productoId); // Actualizar el campo "idProducto"
            $('#editarDetalleForm' + detalleId + ' #cantidad').val(cantidad);
            $('#editarDetalleForm' + detalleId + ' #precio').val(precio);
        });
    </script>
@stop
