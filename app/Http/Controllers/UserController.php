<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index()
    {
        $personas = User::all();
        $pdfRoute = route('user.pdf', ['tipo' => 'todos']);
        $csvRoute = route('user.csv', ['tipo' => 'todos']);
        return view('persona.index', compact('personas', 'pdfRoute', 'csvRoute'));
    }

    public function clientes()
    {
        $personas = User::where('tipo_usuario', 'cliente')->get();
        $pdfRoute = route('user.pdf', ['tipo' => 'cliente']);
        $csvRoute = route('user.csv', ['tipo' => 'cliente']);
        return view('persona.index', compact('personas', 'pdfRoute', 'csvRoute'));
    }

    public function administradores()
    {
        $personas = User::where('tipo_usuario', 'administrador')->get();
        $pdfRoute = route('user.pdf', ['tipo' => 'administrador']);
        $csvRoute = route('user.csv', ['tipo' => 'administrador']);
        return view('persona.index', compact('personas', 'pdfRoute', 'csvRoute'));
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

    public function generarPdf($tipo)
    {
        if ($tipo === 'todos') {
            $users = User::all();
        } else {
            $users = User::where('tipo_usuario', $tipo)->get();
        }
        $dompdf = new Dompdf();
        $html = View::make('persona.pdf', compact('users'))->render();

        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->stream("listado_{$tipo}_usuarios.pdf");
    }

    public function generarCsv($tipo)
    {
        if ($tipo === 'todos los') {
            $users = User::all();
        } else {
            $users = User::where('tipo_usuario', $tipo)->get();
        }
        // Crear el contenido CSV
        $csvData = '';
        $csvHeader = ['ID', 'Nombre Completo', 'Telefono', 'CI', 'Tipo de Usuario'];
        $csvData .= implode(',', $csvHeader) . "\n";
        foreach ($users as $user) {
            $csvRow = [
                $user->id,
                $user->name,
                $user->telefono,
                $user->ci,
                $user->tipo_usuario,
            ];
            $csvData .= implode(',', $csvRow) . "\n";
        }

        // Establecer las cabeceras de respuesta
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=listado_' . $tipo . '_usuarios.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Crear la respuesta con el contenido CSV
        $response = new Response($csvData, 200, $headers);

        // Devolver la respuesta
        return $response;
    }

}
