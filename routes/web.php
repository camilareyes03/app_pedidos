<?php

use App\Http\Controllers\UbicacionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('personas', 'App\Http\Controllers\UserController')->middleware('auth');
Route::get('user/pdf/{tipo}', 'App\Http\Controllers\UserController@generarPdf')->name('user.pdf');
Route::get('user/csv/{tipo}', 'App\Http\Controllers\UserController@generarCsv')->name('user.csv');

Route::get('/clientes', 'App\Http\Controllers\UserController@clientes')->name('clientes.index');
Route::get('/administradores', 'App\Http\Controllers\UserController@administradores')->name('administradores.index');

Route::prefix('ubicaciones')->middleware('auth')->group(function () {
    Route::get('{cliente_id}', [UbicacionController::class, 'index'])->name('ubicaciones.index');
    Route::get('create/{cliente_id}', [UbicacionController::class, 'create'])->name('ubicaciones.create');
    Route::post('store/{cliente_id}', [UbicacionController::class, 'store'])->name('ubicaciones.store');
    Route::get('edit/{ubicacion_id}', [UbicacionController::class, 'edit'])->name('ubicaciones.edit');
    Route::put('update/{ubicacion_id}', [UbicacionController::class, 'update'])->name('ubicaciones.update');
    Route::delete('destroy/{ubicacion_id}', [UbicacionController::class, 'destroy'])->name('ubicaciones.destroy');
});

Route::resource('pedidos', 'App\Http\Controllers\PedidoController')->middleware('auth');
Route::get('/proforma', 'App\Http\Controllers\PedidoController@proforma')->name('pedidos.proforma');
Route::get('/oficial', 'App\Http\Controllers\PedidoController@oficial')->name('pedidos.oficial');

Route::get('pedido/{id}/pdf', 'App\Http\Controllers\PedidoController@descargarPdf')->name('pedido.pdf');
Route::get('pedido/{id}/csv', 'App\Http\Controllers\PedidoController@descargarCsv')->name('pedido.csv');

Route::resource('detallepedido', 'App\Http\Controllers\DetallePedidoController')->middleware('auth');

Route::resource('categorias', 'App\Http\Controllers\CategoriaController')->middleware('auth');
Route::get('/categoria/pdf', 'App\Http\Controllers\CategoriaController@generarPdf')->name('categoria.pdf');
Route::get('/categoria/csv', 'App\Http\Controllers\CategoriaController@generarCsv')->name('categoria.csv');


Route::resource('productos', 'App\Http\Controllers\ProductoController')->middleware('auth');
Route::get('/producto/pdf', 'App\Http\Controllers\ProductoController@generarPdf')->name('producto.pdf');
Route::get('/producto/csv', 'App\Http\Controllers\ProductoController@generarCsv')->name('producto.csv');

Route::get('/cargar-productos-por-categoria/{categoria}', 'App\Http\Controllers\PedidoController@cargarProductosPorCategoria');
