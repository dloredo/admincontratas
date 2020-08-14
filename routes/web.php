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
Route::get('/detallesContrata/{idCliente}/{idContrata}' , 'ClientesController@detallesContrata')->name('detallesContrata');

Route::get('/verContratas/{id}' , 'ClientesController@verContratas')->name('verContratas');
Route::get('/imprimirPagosDiarios/{id}' , 'ClientesController@imprimirPagosDiarios')->name('imprimirPagosDiarios');
Route::get('/imprimirPagosSemanales/{id}' , 'ClientesController@imprimirPagosSemanales')->name('imprimirPagosSemanales');

Route::get('/cambiarEstatusCliente/{id}/{estatus}' , 'ClientesController@cambiarEstatusCliente')->name('edit.cambiarEstatusCliente');
Route::post('/clientes/asignarCobrador' , 'ClientesController@asignarCobrador')->name('clientes.asignarCobrador');


Route::post('obtenerFechasPagos' , 'ClientesController@obtenerFechasPagos')->name('clientes.obtenerFechasPagos');

/*
|--------------------------------------------------------------------------
|           FIN    ADMINISTRACION DE CONTRATAS Y DE CLIENTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
|               REPORTES
|--------------------------------------------------------------------------
*/
Route::get('/directorios' , 'ReportesController@reporteDirectorios')->name('reporteDirectorios');
/*
|--------------------------------------------------------------------------
|            FIN    REPORTES
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

Route::post('/agregarPago/{id}' , 'CobranzaController@agregarPago')->name('agregarPago');
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
Route::get('/principal' , 'PrincipalController@index')->name('vista.principal')->middleware('auth.admin');
Route::get('/pagos-del-dia' , 'PrincipalController@index')->name('vista.pagosDias');
Route::post('/liquidar-cliente/{id}' , 'PrincipalController@liquidar_cobrador')->name('liquidar_cobrador');
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

Route::get('/contratas-no-pagadas' , 'ListadosController@index')->name('vista.noPagadas');
Route::get('/contratas-pagadas' , 'ListadosController@Pagadas')->name('vista.Pagadas');

Route::get('/tipos-categorias' , 'TiposGastosController@index')->name('vista.categorias');
Route::post('/agregarCategoria' , 'TiposGastosController@AgregarCategoria')->name('agregarCategoria');
Route::get('/preedit/{id}' , 'TiposGastosController@pre_edit')->name('pre_edit');
Route::post('/edit/{id}' , 'TiposGastosController@edit')->name('edit.categoria');

Route::post('/destroy/{id}' , 'TiposGastosController@destroy')->name('destroy');

Route::get('/gastos' , 'TiposGastosController@vista_gastos')->name('vista.gastos');
Route::post('/agregarGasto' , 'TiposGastosController@agregarGasto')->name('agregarGasto');