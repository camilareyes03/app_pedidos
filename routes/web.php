<?php

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

Route::resource('personas', 'App\Http\Controllers\User')->middleware('auth');

Route::resource('pedidos', 'App\Http\Controllers\PedidoController')->middleware('auth');
Route::resource('detallepedido', 'App\Http\Controllers\DetallePedidoController')->middleware('auth');

Route::resource('categorias', 'App\Http\Controllers\CategoriaController')->middleware('auth');
Route::resource('productos', 'App\Http\Controllers\ProductoController')->middleware('auth');
