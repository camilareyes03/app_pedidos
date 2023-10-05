<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categoria.index')->with('categorias', $categorias);
    }


    public function create()
    {
        return view('categoria.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
        ],
        [
            'nombre.required' => 'El campo nombre es obligatorio.',
        ]);
        $categoria = new Categoria();
        $categoria->nombre = $request->input('nombre');

        $categoria->save();

        return redirect('categorias')->with('success', 'La categoría se ha guardado exitosamente.');

    }


    public function edit($id)
    {
        $categoria = Categoria::find($id);
        return view('categoria.edit', compact('categoria'));
    }

    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);
        $categoria->nombre = $request->get('nombre');

        $categoria->save();

        return redirect('categorias')->with('edit-success', 'La categoría se ha actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);
        $categoria->delete();
        return redirect('categorias')->with('eliminar', 'ok');
    }
}
