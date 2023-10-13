@extends('adminlte::page')

@section('title', 'Crear Persona')

@section('content_header')
    <h1>Crear Persona</h1>
@stop

@section('content')
    <form action="/personas" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre Completo</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" tabindex="1">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ci" class="form-label">Cedula de Identidad</label>
            <input type="text" id="ci" name="ci" class="form-control" value="{{ old('ci') }}"
                tabindex="1">
            @error('ci')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Telefono/Celular</label>
            <input type="number" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}"
                tabindex="1">
            @error('telefono')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tipo_usuario" class="form-label">Tipo de Persona</label>
            <select name="tipo_usuario" id="tipo_usuario" class="form-control">
                <option value="nulo" {{ old('tipo_usuario') === 'nulo' ? 'selected' : '' }}>Selecciona el Tipo de Persona
                </option>
                <option value="cliente" {{ old('tipo_usuario') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                <option value="administrador" {{ old('tipo_usuario') === 'administrador' ? 'selected' : '' }}>Administrador
                </option>
            </select>
            @error('tipo_usuario')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="foto_container" style="display:none;">
            <br>
            <label for="foto" class="form-label">{{ __('Selecciona una Imagen') }}</label>
            <input type="file" id="foto" class="form-control" name="foto" accept="image/*">
            <br>
            @error('foto')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3" id="email_container" style="display:none;">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                tabindex="1">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="password_container" style="display:none;">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}"
                tabindex="1">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="/personas" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit"
            class="btn btn-success" tabindex="3">Guardar</button>
    </form>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tipo_usuario').change(function() {
                var selectedOption = $(this).val();

                // Establece las variables para los contenedores
                var fotoContainer = $('#foto_container');
                var emailContainer = $('#email_container');
                var passwordContainer = $('#password_container');

                // Muestra u oculta los contenedores según la opción seleccionada
                if (selectedOption === 'cliente') {
                    fotoContainer.show();
                    emailContainer.hide();
                    passwordContainer.hide();
                } else if (selectedOption === 'administrador') {
                    fotoContainer.hide();
                    emailContainer.show();
                    passwordContainer.show();
                }
            });
        });
    </script>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
