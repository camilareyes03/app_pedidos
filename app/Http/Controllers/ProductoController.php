<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        $productos->load('categoria');
        $pdfRoute = route('producto.pdf');
        $csvRoute = route('producto.csv');

        return view('producto.index', compact('productos', 'pdfRoute','csvRoute'));
    }


    public function create()
    {
        $categorias= Categoria::all();
        if ($categorias->isEmpty()) {
            // Si no hay categorías, muestra una alerta SweetAlert
            return redirect()->route('categorias.create')
                ->with('warning', 'No tienes categorías creadas. Debes crear al menos una.');
        }
        return view('producto.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validarDatos($request);

        $producto = new Producto();
        $producto->nombre = $request->input('nombre');
        $producto->precio = $request->input('precio');
        $producto->stock = $request->input('stock');
        $producto->categoria_id = $request->input('categoria_id');
        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => ['image', 'nullable', 'max:2048', 'mimes:png,jpg,jpeg,gif'],
            ]);

            $foto = $request->file('foto')->store('public/productos_imagenes');
            $url = Storage::url($foto);
            $producto->foto = $url;
        }
        $producto->save();

        return redirect('productos')->with('success', 'El producto se ha guardado exitosamente.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        $categorias= Categoria::all();
        return view('producto.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validarDatos($request, true); // Llama a la función de validación con el parámetro $update = true

        $producto = Producto::find($id);
        $producto->nombre = $request->get('nombre');
        $producto->precio = $request->get('precio');
        $producto->stock = $request->input('stock');
        $producto->categoria_id = $request->input('categoria_id');

        if ($request->hasFile('foto')) {
            if ($producto->foto) {
                Storage::delete($producto->foto);
            }
            $foto = $request->file('foto')->store('public/productos_imagenes');
            $url = Storage::url($foto);
            $producto->foto = $url;
        }
        $producto->save();

        return redirect('productos')->with('edit-success', 'El producto se ha actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        $producto->delete();
        return redirect('productos')->with('eliminar', 'ok');
    }

    public function validarDatos(Request $request, $update = false)
    {
        $reglas = [
            'nombre' => 'required',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required',
        ];

        $mensajes = [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'precio.required' => 'El campo precio es obligatorio.',
            'precio.numeric' => 'El campo precio debe ser numérico.',
            'precio.min' => 'El campo precio debe ser mayor o igual a 0.',
            'categoria_id.required' => 'El campo categoría es obligatorio.',
            'stock.required' => 'El campo stock es obligatorio.', // Mensaje para el campo "stock" requerido
            'stock.integer' => 'El campo stock debe ser un número entero.', // Mensaje para el campo "stock" no entero
            'stock.min' => 'El campo stock debe ser mayor o igual a 0.', // Mensaje para el campo "stock" no válido
        ];

        $request->validate($reglas, $mensajes);
    }


    public function generarPdf()
    {
        $productos = Producto::all();
        $dompdf = new Dompdf();

        $html = View::make('producto.pdf', compact('productos'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->stream("Catalogo_Productos.pdf");
    }

    public function generarCsv()
    {
        $productos = Producto::all();
        // Crear el contenido CSV
        $csvData = '';
        $csvHeader = ['ID', 'Nombre', 'Precio', 'Stock'];
        $csvData .= implode(',', $csvHeader) . "\n";
        foreach ($productos as $producto) {
            $csvRow = [
                $producto->id,
                $producto->nombre,
                $producto->precio,
                $producto->stock
            ];
            $csvData .= implode(',', $csvRow) . "\n";
        }

        // Establecer las cabeceras de respuesta
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Catalogo_Productos.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $response = new Response($csvData, 200, $headers);
        return $response;
    }
}
