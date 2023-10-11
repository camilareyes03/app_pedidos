<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $personas = User::all();
        return view('persona.index', compact('personas'));
    }

    public function clientes()
    {
        $personas = User::where('tipo_usuario', 'cliente')->get();
        return view('persona.index', compact('personas'));
    }

    public function administradores()
    {
        $personas = User::where('tipo_usuario', 'administrador')->get();
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

        $persona = new User();
        $persona->name = $request->input('name');
        $persona->ci = $request->input('ci');
        $persona->telefono = $request->input('telefono');
        $persona->tipo_usuario = $request->input('tipo_usuario');

        if ($request->input('tipo_usuario') === 'cliente') {
            if ($request->hasFile('foto')) {
                $request->validate([
                    'foto' => ['image', 'nullable', 'max:2048', 'mimes:png,jpg,jpeg,gif'],
                ]);

                $foto = $request->file('foto')->store('public/productos_imagenes');
                $url = Storage::url($foto);
                $persona->foto = $url;
            }
            $persona->email = null;
            $persona->password = null;
        } else {
            $persona->email = $request->input('email');
            // Encriptar la contraseña antes de guardarla
            $persona->password = bcrypt($request->input('password'));
        }

        $persona->save();

        return redirect('personas')->with('success', 'La persona se ha guardado exitosamente.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $persona = User::findOrFail($id);
        return view('persona.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $persona = User::findOrFail($id);
        return view('persona.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validarDatos($request);

        // Recuperar el modelo existente desde la base de datos
        $persona = User::find($id);

        if (!$persona) {
            return redirect('personas')->with('error', 'Persona no encontrada.');
        }

        // Actualizar los atributos del modelo con los nuevos valores
        $persona->name = $request->input('name');
        $persona->ci = $request->input('ci');
        $persona->telefono = $request->input('telefono');
        $persona->tipo_usuario = $request->input('tipo_usuario');

        if ($request->input('tipo_usuario') === 'cliente') {
            if ($request->hasFile('foto')) {
                $request->validate([
                    'foto' => ['image', 'nullable', 'max:2048', 'mimes:png,jpg,jpeg,gif'],
                ]);

                $foto = $request->file('foto')->store('public/productos_imagenes');
                $url = Storage::url($foto);
                $persona->foto = $url;
            }
            $persona->email = null;
            $persona->password = null;
        } else {
            $persona->email = $request->input('email');

            // Verificar si la contraseña se está actualizando
            if ($request->input('password')) {
                // Encriptar la nueva contraseña antes de guardarla
                $persona->password = bcrypt($request->input('password'));
            }
        }

        // Guardar el modelo actualizado en la base de datos
        $persona->save();

        return redirect('personas')->with('success', 'La persona se ha actualizado exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $persona = User::find($id);
        $persona->delete();
        return redirect('personas')->with('eliminar', 'ok');
    }

    public function validarDatos(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'ci' => 'min:7',
            'telefono' => 'min:8',
            'tipo_usuario' => ['required', 'not_in:nulo'],
        ];

        $mensajes = [
            'name.required' => 'Este campo es obligatorio.',
            'ci.min' => 'Este campo debe tener mínimo 7 valores.',
            'telefono.min' => 'Este campo debe tener un mínimo de 8 dígitos.',
            'tipo_usuario.not_in' => 'Por favor, selecciona una opción válida.',
        ];

        $tipo_usuario = $request->input('tipo_usuario');

        switch ($tipo_usuario) {
            case 'cliente':
                $reglas['foto'] = 'required';
                $mensajes['foto.required'] = 'Este campo es obligatorio para el cliente.';
                break;
        }

        $request->validate($reglas, $mensajes);
    }
}
