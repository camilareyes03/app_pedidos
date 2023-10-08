@extends('adminlte::page')

@section('title', 'Crear Persona')

@section('content_header')
<h1>Crear Persona</h1>
@stop

@section('content')
<form action="/personas" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre Completo</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" tabindex="1">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" tabindex="1">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="ci" class="form-label">Cedula de Identidad</label>
        <input type="text" id="ci" name="ci" class="form-control" value="{{ old('ci') }}" tabindex="1">
        @error('ci')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label">Telefono/Celular</label>
        <input type="number" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}" tabindex="1">
        @error('telefono')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="tipo_usuario" class="form-label">Tipo de Persona</label>
        <select name="tipo_usuario" id="tipo_usuario" class="form-control">
            <option value="nulo" {{ old('tipo_usuario') === 'nulo' ? 'selected' : '' }}>Selecciona el Tipo de Persona</option>
            <option value="cliente" {{ old('tipo_usuario') === 'cliente' ? 'selected' : '' }}>Cliente</option>
            <option value="repartidor" {{ old('tipo_usuario') === 'repartidor' ? 'selected' : '' }}>Repartidor</option>
        </select>
        @error('tipo_usuario')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="razon_social" class="form-label">Razon Social</label>
        <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{ old('razon_social') }}" tabindex="1">
        @error('razon_social')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="codigo_empleado" class="form-label">Codigo de Empleado</label>
        <input type="text" id="codigo_empleado" name="codigo_empleado" class="form-control" value="{{ old('codigo_empleado') }}" tabindex="1">
        @error('codigo_empleado')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>


    <a href="/personas" class="btn btn-secondary" tabindex="4">Cancelar</a>
    <button type="submit" class="btn btn-success" tabindex="3">Guardar</button>
</form>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop