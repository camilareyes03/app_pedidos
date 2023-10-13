@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <h1>Listado de Categorias</h1>
@stop

@section('content')
    <a style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" href="categorias/create" class="btn btn-primary ">Registrar</a>
    <a href="{{ $pdfRoute }}" class="btn btn-danger"> <i class="fas fa-file-pdf"></i></a>
    <a href="{{ $csvRoute }}" class="btn btn-success"><i class="fa fa-file-excel"></i></a>

    <br> <br>
    <table id="categorias" class="table table-striped table-bordered" style="width: 100%">
        <thead class="bg-primary text-white">
            <tr>
                <th style="background-color: #4b545c" scope="col">ID</th>
                <th style="background-color: #4b545c" scope="col">Nombre</th>
                <th style="background-color: #4b545c" scope="col">Descripcion</th>
                <th style="background-color: #4b545c" scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion }}</td>
                    <td>
                        <form class="formulario-eliminar" action="{{ route('categorias.destroy', $categoria->id) }}"
                            method="POST">
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-info">
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
        $('#categorias').DataTable();
    </script>
    @if (session('eliminar') == 'ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'Tu categoria ha sido eliminada exitosamente',
                'success'
            )
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire(
                'Exito!',
                'Tu categoria ha sido creada exitosamente',
                'success'
            )
        </script>
    @endif

    @if (session('edit-success'))
        <script>
            Swal.fire(
                'Exito!',
                'Tu categoria ha sido editada exitosamente',
                'success'
            )
        </script>
    @endif

    <script>
        $('.formulario-eliminar').submit(function(evento) {
            evento.preventDefault();

            Swal.fire({
                title: 'Estas seguro?',
                text: "Esta categoria se eliminara definitivamente",
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
