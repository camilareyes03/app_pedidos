<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Http\Request;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = ModelsUser::where('tipo_usuario', '!=', 'admin')->get();
        return view('persona.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('persona.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validarDatos($request);

        $persona = new ModelsUser();
        $persona->name = $request->input('name');
        $persona->email = $request->input('email');
        $persona->ci = $request->input('ci');
        $persona->telefono = $request->input('telefono');
        if ($request->input('tipo_usuario') === 'cliente') {
            $persona->tipo_usuario = $request->input('tipo_usuario');
            $persona->razon_social = $request->input('razon_social');
            $persona->codigo_empleado = null;
        } else {
            $persona->tipo_usuario = $request->input('tipo_usuario');
            $persona->razon_social = null;
            $persona->codigo_empleado = $request->input('codigo_empleado');
        }
        $persona->save();

        return redirect('personas')->with('success', 'La persona se ha guardado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $persona = ModelsUser::findOrFail($id);
        return view('persona.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $persona = ModelsUser::findOrFail($id);
        return view('persona.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $persona = ModelsUser::findOrFail($id);

        $this->validarDatos($request);

        $persona->name = $request->input('name');
        $persona->email = $request->input('email');
        $persona->ci = $request->input('ci');
        $persona->telefono = $request->input('telefono');
        if ($request->input('tipo_usuario') === 'cliente') {
            $persona->tipo_usuario = $request->input('tipo_usuario');
            $persona->razon_social = $request->input('razon_social');
            $persona->codigo_empleado = null;
        } else {
            $persona->tipo_usuario = $request->input('tipo_usuario');
            $persona->razon_social = null;
            $persona->codigo_empleado = $request->input('codigo_empleado');
        }

        $persona->update();

        return redirect('personas')->with('edit-success', 'La persona se ha actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $persona = ModelsUser::find($id);
        $persona->delete();
        return redirect('personas')->with('eliminar', 'ok');
    }

    public function validarDatos(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' => 'required',
            'ci' => 'min:7',
            'telefono' => 'min:8',
            'tipo_usuario' => ['required', 'not_in:nulo'],
        ];

        $mensajes = [
            'name.required' => 'Este campo es obligatorio.',
            'email.required' => 'Este campo es obligatorio.',
            'ci.min' => 'Este campo debe tener mínimo 7 valores.',
            'telefono.min' => 'Este campo debe tener un mínimo de 8 dígitos.',
            'tipo_usuario.not_in' => 'Por favor, selecciona una opción válida.',
        ];

        $tipo_usuario = $request->input('tipo_usuario');

        switch ($tipo_usuario) {
            case 'cliente':
                $reglas['razon_social'] = 'required';
                $mensajes['razon_social.required'] = 'Este campo es obligatorio para el cliente.';
                break;
            case 'repartidor':
                $reglas['codigo_empleado'] = 'required';
                $mensajes['codigo_empleado.required'] = 'Este campo es obligatorio para el repartidor.';
                break;
        }

        $request->validate($reglas, $mensajes);
    }
}
