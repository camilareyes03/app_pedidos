@extends('adminlte::page')

@section('title', 'Editar Persona')

@section('content_header')
<h1>Editar Persona</h1>
@stop

@section('content')
<form action="/personas/{{ $persona->id }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nombre Completo</label>
        <input type="text" class="form-control" name="name" value="{{ old('name', $persona->name) }}">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="{{ old('email', $persona->email) }}">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="ci" class="form-label">Cedula de Identidad</label>
        <input type="text" class="form-control" name="ci" value="{{ old('ci', $persona->ci) }}">
        @error('ci')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label">Telefono/Celular</label>
        <input type="number" class="form-control" name="telefono" value="{{ old('telefono', $persona->telefono) }}">
        @error('telefono')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="tipo_usuario" class="form-label">Tipo de Persona</label>
        <select name="tipo_usuario" id="tipo_usuario" class="form-control">
            <option value="cliente" {{ old('tipo_usuario', $persona->tipo_usuario) === 'cliente' ? 'selected' : '' }}>Cliente</option>
            <option value="repartidor" {{ old('tipo_usuario', $persona->tipo_usuario) === 'repartidor' ? 'selected' : '' }}>Repartidor</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="razon_social" class="form-label">Razon Social</label>
        <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{ $persona->razon_social }}" tabindex="1">
        @error('razon_social')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="codigo_empleado" class="form-label">Codigo de Empleado</label>
        <input type="text" id="codigo_empleado" name="codigo_empleado" class="form-control" value="{{ $persona->codigo_empleado }}" tabindex="1">
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
