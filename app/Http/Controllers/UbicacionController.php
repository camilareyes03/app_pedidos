<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($cliente_id)
    {
        $ubicaciones = Ubicacion::all()->where('cliente_id', $cliente_id);
        return view('ubicacion.index', compact('ubicaciones', 'cliente_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($cliente_id)
    {
        return view('ubicacion.create', compact('cliente_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $cliente_id)
    {
        $this->validarDatos($request);

        $ubicacion = new Ubicacion();
        $ubicacion->nombre = $request->input('nombre');
        $ubicacion->referencia = $request->input('referencia');
        $ubicacion->link = $request->input('link');
        $ubicacion->latitud = $request->input('latitud');
        $ubicacion->longitud = $request->input('longitud');
        $ubicacion->cliente_id = $cliente_id;

        $ubicacion->save();

        return redirect()->route('ubicaciones.index', $cliente_id)->with('success', 'La ubicación se ha guardado exitosamente.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ubicacion_id)
    {
        $ubicacion = Ubicacion::findOrFail($ubicacion_id);
        return view('ubicacion.edit', compact('ubicacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ubicacion_id)
    {
        $this->validarDatos($request);

        $ubicacion = Ubicacion::findOrFail($ubicacion_id);
        $ubicacion->nombre = $request->input('nombre');
        $ubicacion->referencia = $request->input('referencia');
        $ubicacion->link = $request->input('link');
        $ubicacion->latitud = $request->input('latitud');
        $ubicacion->longitud = $request->input('longitud');

        $ubicacion->save();

        return redirect()->route('ubicaciones.index', $ubicacion->cliente_id)->with('edit-success', 'La ubicación se ha actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ubicacion_id)
    {
        $ubicacion = Ubicacion::findOrFail($ubicacion_id);
        $cliente_id = $ubicacion->cliente_id;
        $ubicacion->delete();
        return redirect()->route('ubicaciones.index', $cliente_id)->with('eliminar', 'ok');
    }

    public function validarDatos(Request $request)
    {
        $reglas = [
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
        ];

        $mensajes = [
            'nombre.required' => 'Coloque un nombre a la ubicacion.',
            'latitud.required' => 'Este campo es obligatorio.',
            'longitud.required' => 'Este campo es obligatorio.',
        ];

        $request->validate($reglas, $mensajes);
    }
}
