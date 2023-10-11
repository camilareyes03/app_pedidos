@extends('adminlte::page')

@section('title', 'Detalles de Persona')

@section('content_header')
<h1>Detalles de Persona</h1>
@stop

@section('content')
<div class="mb-3">
    <label for="name" class="form-label">Nombre Completo</label>
    <input type="text" class="form-control" value="{{ $persona->name }}" readonly>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" value="{{ $persona->email }}" readonly>
</div>

<div class="mb-3">
    <label for="ci" class="form-label">Cedula de Identidad</label>
    <input type="text" class="form-control" value="{{ $persona->ci }}" readonly>
</div>

<div class="mb-3">
    <label for="telefono" class="form-label">Telefono/Celular</label>
    <input type="number" class="form-control" value="{{ $persona->telefono }}" readonly>
</div>

<div class="mb-3">
    <label for="tipo_usuario" class="form-label">Tipo de Persona</label>
    <input type="text" class="form-control" value="{{ $persona->tipo_usuario }}" readonly>
</div>

<a href="/personas" class="btn btn-secondary" tabindex="4">Volver</a>
@if($persona->tipo_usuario === 'cliente')
    <a style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" href="{{ route('ubicaciones.index', $persona->id) }}" class="btn btn-primary">Ubicaciones</a>
@endif

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop