@extends('adminlte::page')

@section('title', 'Crear Ubicacion')

@section('content_header')
    <h1>Crear Ubicacion</h1>
@stop

@section('content')
    <form action="{{ route('ubicaciones.store', $cliente_id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <div class="mapform">
                <div class="row">
                    <div class="col-6">
                        <label for="latitud">Latitud:</label>
                        <input type="text" class="form-control" id="latitud" name="latitud" placeholder="Latitud"
                            value="{{ old('latitud') }}">
                        @error('latitud')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="longitud">Longitud:</label>
                        <input type="text" class="form-control" id="longitud" name="longitud" placeholder="Longitud"
                            value="{{ old('longitud') }}">
                        @error('longitud')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div id="map" style="height: 400px; width: 100%" class="my-3"></div>
            </div>
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Ubicacion</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}"
                tabindex="1">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="referencia" class="form-label">Referencia</label>
            <input type="text" id="referencia" name="referencia" class="form-control" value="{{ old('referencia') }}"
                tabindex="1">
            @error('referencia')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="link" class="form-label">Link</label>
            <input type="text" id="link" name="link" class="form-control" value="{{ old('link') }}"
                tabindex="1">
            @error('link')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="/ubicaciones" class="btn btn-secondary" tabindex="4">Cancelar</a>
        <button style="background-color: rgb(1, 130, 5); border: 1px solid rgb(1, 130, 5);" type="submit" class="btn btn-success" tabindex="3">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop


@section('js')
    <script>
        let map;
        let marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: -17.783,
                    lng: -63.182
                },
                zoom: 13,
                scrollwheel: true,
            });

            marker = new google.maps.Marker({
                position: {
                    lat: -17.783,
                    lng: -63.182
                },
                map: map,
                draggable: true,
            });

            google.maps.event.addListener(marker, "dragend", function(event) {
                let lat = event.latLng.lat();
                let lng = event.latLng.lng();
                document.getElementById("latitud").value = lat;
                document.getElementById("longitud").value = lng;
            });

            google.maps.event.addListener(map, "click", function(event) {
                marker.setPosition(event.latLng);
                let lat = event.latLng.lat();
                let lng = event.latLng.lng();
                document.getElementById("latitud").value = lat;
                document.getElementById("longitud").value = lng;
            });
        }
    </script>
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLxbZNLoifOFOmedMfeZjKh2532vpRY0s&callback=initMap" async defer></script>
@stop
