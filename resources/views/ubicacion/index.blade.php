@extends('adminlte::page')

@section('title', 'Ubicaciones')

@section('content_header')
<h1>Listado de Ubicaciones</h1>
@stop

@section('content')
<a style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" href="{{route('ubicaciones.create', $cliente_id)}}" class="btn btn-primary ">Registrar</a>
<br> <br>
<table id="ubicaciones" class="table table-striped table-bordered" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col" style="background-color: #4b545c">ID</th>
            <th scope="col" style="background-color: #4b545c">Nombre</th>
            <th scope="col" style="background-color: #4b545c">Referencia</th>
            <th scope="col" style="background-color: #4b545c">Link</th>
            <th scope="col" style="background-color: #4b545c">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ubicaciones as $ubicacion)
        <tr>
            <td>{{ $ubicacion->id }}</td>
            <td>{{ $ubicacion->nombre }}</td>
            <td>{{ $ubicacion->referencia }}</td>
            <td><a href="{{ $ubicacion->link }}" target="_blank">{{ $ubicacion->link }}</a></td>
            <td>
                <form class="formulario-eliminar" action="{{ route('ubicaciones.destroy', $ubicacion->id) }}" method="POST">
                    <a href="https://www.google.com/maps?q=<?php echo $ubicacion->latitud . ',' . $ubicacion->longitud; ?>" class="btn btn-info" target="_blank">Ver Mapa</a>
                    <a href="{{ route('ubicaciones.edit', $ubicacion->id) }}" class="btn btn-warning">Editar</a>
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
    $('#ubicaciones').DataTable();
</script>

@if (session('eliminar') == 'ok')
<script>
    Swal.fire(
        'Eliminado!',
        'La ubicacion se ha eliminado exitosamente',
        'success'
    )
</script>
@endif
@if (session('success'))
<script>
    Swal.fire(
        'Exito!',
        'La ubicacion se ha guardado exitosamente.',
        'success'
    )
</script>
@endif
@if (session('edit-success'))
<script>
    Swal.fire(
        'Exito!',
        'La ubicacion se ha actualizado exitosamente',
        'success'
    )
</script>
@endif

<script>
    $('.formulario-eliminar').submit(function(evento) {
        evento.preventDefault();

        Swal.fire({
            title: 'Estas seguro?',
            text: "Esta ubicacion se eliminará definitivamente",
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