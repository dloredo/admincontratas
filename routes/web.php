<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    return redirect("/login");
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
Route::post('/agregarCliente' , 'ClientesController@agregarClienteNuevo')->name('agregarClienteNuevo');
Route::get('/agregarContrata/{id}' , 'ClientesController@vista_agregarContrata')->name('vista.agregarContrata');
Route::post('/añadirContrata/{id}' , 'ClientesController@agregarContrataNueva')->name('agregarContrataNueva');

Route::get('/verContratas/{id}' , 'ClientesController@verContratas')->name('verContratas');
Route::get('/imprimirPagosDiarios/{id}' , 'ClientesController@imprimirPagosDiarios')->name('imprimirPagosDiarios');
Route::get('/imprimirPagosSemanales/{id}' , 'ClientesController@imprimirPagosSemanales')->name('imprimirPagosSemanales');

Route::get('/cambiarEstatusCliente/{id}/{estatus}' , 'ClientesController@cambiarEstatusCliente')->name('edit.cambiarEstatusCliente');
Route::post('/clientes/asignarCobrador' , 'ClientesController@asignarCobrador')->name('clientes.asignarCobrador');

/*
|--------------------------------------------------------------------------
|           FIN    ADMINISTRACION DE CONTRATAS Y DE CLIENTES
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
|               VER CONTRATAS Y HISTORIAL DE PAGOS Y PAGOS
|--------------------------------------------------------------------------
*/
Route::get('/contratas' , 'ContratasController@index')->name('vista.contratas');

Route::get('/cobranza' , 'CobranzaController@index')->name('vista.contratas_cobrar');
Route::get('/cobranza/contratas/{id}' , 'CobranzaController@verContratasCliente')->name('verContratasCliente');
Route::get('/cobranza/contratas/pagos/{id}' , 'CobranzaController@verPagosContrata')->name('verPagosContrata');


/*
|--------------------------------------------------------------------------
|            FIN    VER CONTRATAS Y HISTORIAL DE PAGOS
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
|               ADMINISTRACION DE USUARIOS
|--------------------------------------------------------------------------
*/
Route::get('/usuarios' , 'UsuariosController@index')->name('vista.usuarios');
Route::get('/agregarUsuario' , 'UsuariosController@agregarUsuario')->name('vista.agregarUsuario');
Route::post('/agregarUsuario' , 'UsuariosController@create')->name('create.agregarUsuario');
Route::get('/cambiarEstatusUsuario/{id}/{estatus}' , 'UsuariosController@cambiarEstatus')->name('edit.cambiarEstatus');
Route::get('/eliminarUsuario/{id}' , 'UsuariosController@eliminarUsuario')->name('delete.usuario');

/*
|--------------------------------------------------------------------------
|           FIN    ADMINISTRACION USUARIOS
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
|               PAGINA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/principal' , 'PrincipalController@index')->name('vista.principal');
/*
|--------------------------------------------------------------------------
|           FIN PAGINA PRINCIPAL
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
|               PAGINA CAPITAL
|--------------------------------------------------------------------------
*/
Route::get('/capital-corte' , 'CapitalController@index')->name('vista.capital.cortes');
Route::get('/generarCorte' , 'CapitalController@generarCorte')->name('generar.corte');
Route::get('/capital-movimientos' , 'CapitalController@movimientosCapital')->name('vista.capital.movimientos');
Route::post('/crearMovimientoCapital' , 'CapitalController@crearMovimientoCapital')->name('create.movimientoCapital');
/*
|--------------------------------------------------------------------------
|           FIN PAGINA CAPITAL
|--------------------------------------------------------------------------
*/
