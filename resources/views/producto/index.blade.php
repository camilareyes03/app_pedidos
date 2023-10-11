@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <h1>Listado de Productos</h1>
@stop

@section('content')
    <a style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" href="productos/create" class="btn btn-primary ">Registrar</a>
    <br> <br>
    <table id="productos" class="table table-striped table-bordered" style="width: 100%">
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col" style="background-color: #4b545c">ID</th>
                <th scope="col" style="background-color: #4b545c">Nombre</th>
                <th scope="col" style="background-color: #4b545c">Precio</th>
                <th scope="col" style="background-color: #4b545c">Categoria</th>
                <th scope="col" style="background-color: #4b545c">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->precio }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('productos.destroy', $producto->id) }}"
                            method="POST">
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-info">
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
        $('#productos').DataTable();
    </script>
    @if (session('eliminar') == 'ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'El producto ha sido eliminado exitosamente',
                'success'
            )
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire(
                'Exito!',
                'Tu Producto ha sido creado exitosamente',
                'success'
            )
        </script>
    @endif
    @if (session('edit-success'))
        <script>
            Swal.fire(
                'Exito!',
                'El producto ha sido editado exitosamente',
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
