<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        $pdfRoute = route('categoria.pdf');
        $csvRoute = route('categoria.csv');
        return view('categoria.index', compact('categorias', 'pdfRoute','csvRoute'));
    }


    public function create()
    {
        return view('categoria.create');
    }


    public function store(Request $request)
    {
        $this->validarDatos($request);

        $categoria = new Categoria();
        $categoria->nombre = $request->input('nombre');
        $categoria->descripcion = $request->input('descripcion');

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
        $this->validarDatos($request);

        $categoria->nombre = $request->input('nombre');
        $categoria->descripcion = $request->input('descripcion');

        $categoria->save();

        return redirect('categorias')->with('edit-success', 'La categoría se ha actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);
        $categoria->delete();
        return redirect('categorias')->with('eliminar', 'ok');
    }

    private function validarDatos(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ],
        [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'descripcion.required' => 'El campo descripcion es obligatorio.',
        ]);
    }

    public function generarPdf()
    {
        $categorias = Categoria::all();
        $dompdf = new Dompdf();

        $html = View::make('categoria.pdf', compact('categorias'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->stream("Categorias.pdf");
    }

    public function generarCsv()
    {
        $categorias = Categoria::all();
        $csvData = '';
        $csvHeader = ['ID', 'Nombre', 'Descripcion'];
        $csvData .= implode(',', $csvHeader) . "\n";
        foreach ($categorias as $categoria) {
            $csvRow = [
                $categoria->id,
                $categoria->nombre,
                $categoria->descripcion
            ];
            $csvData .= implode(',', $csvRow) . "\n";
        }

        // Establecer las cabeceras de respuesta
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Categorias.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $response = new Response($csvData, 200, $headers);
        return $response;
    }
}
