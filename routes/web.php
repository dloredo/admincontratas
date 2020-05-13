<?php

use Illuminate\Support\Facades\Route;
use App\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba2', function () {
    return view('prueba');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/prueba', 'Prueba@index')->name('prueba');

/*
|--------------------------------------------------------------------------
|               ADMINISTRACION DE CONTRATAS Y DE CLIENTES
|--------------------------------------------------------------------------
*/
Route::get('/clientes' , 'ClientesController@index')->name('vista.clientes');
Route::get('/agregarCliente' , 'ClientesController@vista_agregarCliente')->name('vista.agregarCliente');
/*
|--------------------------------------------------------------------------
|           FIN    ADMINISTRACION DE CONTRATAS Y DE CLIENTES
|--------------------------------------------------------------------------
*/
