@extends('adminlte::page')

@section('title', 'Personas')

@section('content_header')
    @if (Request::is('clientes*'))
        <h1>Listado de Cliente</h1>
    @elseif (Request::is('administradores*'))
        <h1>Listado de Administradores</h1>
    @else
        <h1>Listado de Personas</h1>
    @endif
@stop

@section('content')
    <a style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" href="personas/create"
        class="btn btn-primary ">Registrar</a>
    <a href="{{ $pdfRoute }}" class="btn btn-danger"> <i class="fas fa-file-pdf"></i></a>
    <a href="{{ $csvRoute }}" class="btn btn-success"><i class="fa fa-file-excel"></i></a>

    <br> <br>
    <table id="personas" class="table table-striped table-bordered" style="width: 100%">
        <thead class="bg-primary text-white">
            <tr>
                <th style="background-color: #4b545c" scope="col">ID</th>
                <th style="background-color: #4b545c" scope="col">Nombre</th>
                <th style="background-color: #4b545c" scope="col">Telefono</th>
                @if (Request::is('clientes*'))
                    <th style="background-color: #4b545c" scope="col">Foto</th>
                @elseif (Request::is('administradores*'))
                    <th style="background-color: #4b545c" scope="col">Email</th>
                @endif
                <th style="background-color: #4b545c" scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personas as $persona)
                <tr>
                    <td>{{ $persona->id }}</td>
                    <td>{{ $persona->name }}</td>
                    <td>{{ $persona->telefono }}</td>
                    @if (Request::is('clientes*'))
                        <td>
                            @if ($persona->foto)
                                <img src="{{ asset($persona->foto) }}" alt="Foto del usuario" width="70" height="70">
                            @else
                                <p>No se ha cargado ninguna foto</p>
                            @endif
                        </td>
                    @elseif (Request::is('administradores*'))
                        <td>{{ $persona->email }}</td>
                    @endif
                    <td>
                        <form class="formulario-eliminar" action="{{ route('personas.destroy', $persona->id) }}"
                            method="POST">
                            <a href="{{ route('personas.show', $persona->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver Mas
                            </a>
                            <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-warning">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        $('#personas').DataTable();
    </script>

    @if (session('eliminar') == 'ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'La persona se ha eliminado exitosamente',
                'success'
            )
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire(
                'Exito!',
                'La persona se ha guardado exitosamente.',
                'success'
            )
        </script>
    @endif
    @if (session('edit-success'))
        <script>
            Swal.fire(
                'Exito!',
                'La persona se ha actualizado exitosamente',
                'success'
            )
        </script>
    @endif

    <script>
        $('.formulario-eliminar').submit(function(evento) {
            evento.preventDefault();

            Swal.fire({
                title: 'Estas seguro?',
                text: "Esta persona se eliminará definitivamente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
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
