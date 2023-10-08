@extends('adminlte::page')

@section('title', 'Personas')

@section('content_header')
<h1>Listado de Personas</h1>
@stop

@section('content')
<a href="personas/create" class="btn btn-primary ">Registrar</a>
<br> <br>
<table id="personas" class="table table-striped table-bordered" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Email</th>
            <th scope="col">Telefono</th>
            <th scope="col">Tipo Usuario</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($personas as $persona)
        <tr>
            <td>{{ $persona->id }}</td>
            <td>{{ $persona->name }}</td>
            <td>{{ $persona->email }}</td>
            <td>{{ $persona->telefono }}</td>
            <td>{{ $persona->tipo_usuario }}</td>
            <td>
                <form class="formulario-eliminar" action="{{ route('personas.destroy', $persona->id) }}" method="POST">
                    <a href="{{ route('personas.show', $persona->id) }}" class="btn btn-info">Ver Mas</a>
                    <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-warning">Editar</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
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