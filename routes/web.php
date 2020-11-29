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

Route::get('/home',function () {
    return redirect("/principal");
})->name('home');

Route::get('/prueba', 'Prueba@pruebaExcel')->name('prueba');

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
Route::get('/getAllDesestimateDays' , 'ClientesController@getDesestimateDays');

Route::get('/verContratas/{id}' , 'ClientesController@verContratas')->name('verContratas');
Route::get('/imprimirPagosDiarios/{id}' , 'ClientesController@imprimirPagosDiarios')->name('imprimirPagosDiarios');
Route::get('/imprimirPagosSemanales/{id}' , 'ClientesController@imprimirPagosSemanales')->name('imprimirPagosSemanales');

Route::get('/cambiarEstatusCliente/{id}/{estatus}' , 'ClientesController@cambiarEstatusCliente')->name('edit.cambiarEstatusCliente');
Route::post('/clientes/asignarCobrador' , 'ClientesController@asignarCobrador')->name('clientes.asignarCobrador');


Route::post('obtenerFechasPagos' , 'ClientesController@obtenerFechasPagos')->name('clientes.obtenerFechasPagos');

//NUMEROS HABILES
Route::get('numeros-habiles' , 'ClientesHabilesController@index')->name('numerosHabiles');

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
Route::get('/reporte_general_cobranza' , 'ReportesController@reporte_general_cobranza')->name('reporte_general_cobranza');
Route::get('/estadoCuenta/{id}' , 'ReportesController@estadoCuenta')->name('estadoCuenta');
Route::get('/saldo-global-clientes' , 'ReportesController@SaldoGlobalClientes')->name('SaldoGlobalClientes');
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
Route::get('/cobranza/contratas/descargarTarjetaContrata/{id}' , 'CobranzaController@descargarTarjetaContrata')->name('descargarTarjetaContrata');
Route::get('/cobranza/historial/' , 'CobranzaController@hitorialCobros')->name('historialCobranza');
Route::get('/cobranza/historial/{fecha}/{id}' , 'CobranzaController@hitorialCobros')->name('historialCobranza.filtro');
Route::get('/cobranza/historial/{fecha}' , 'CobranzaController@hitorialCobros')->name('historialCobranza.filtro_fecha');
Route::get('/cobranza/historial/{id}' , 'CobranzaController@hitorialCobros')->name('historialCobranza.filtro_cobrador');
Route::post('/cobranza/historial/editarCobro' , 'CobranzaController@editarCobro')->name('historialCobranza.editar');

Route::post('/cobranza/historial/eliminar' , 'CobranzaController@eliminarCobro')->name('historialCobranza.eliminar');

Route::post('/cobranza/historial/confirmarPagos' , 'CobranzaController@confirmarPagos')->name('historialCobranza.confirmarPagos');
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

Route::middleware(['auth.admin'])->group(function () {

    Route::get('/usuarios' , 'UsuariosController@index')->name('vista.usuarios');
    Route::get('/agregarUsuario' , 'UsuariosController@agregarUsuario')->name('vista.agregarUsuario');
    Route::post('/agregarUsuario' , 'UsuariosController@create')->name('create.agregarUsuario');
    Route::get('/cambiarEstatusUsuario/{id}/{estatus}' , 'UsuariosController@cambiarEstatus')->name('edit.cambiarEstatus');
    Route::get('/eliminarUsuario/{id}' , 'UsuariosController@eliminarUsuario')->name('delete.usuario');

});

Route::get('/cambiarContraseña' , 'UsuariosController@cambiarContraseña')->name('cambiar.contraseña');
Route::post('/cambiarContraseña/store' , 'UsuariosController@guardarNuevaContraseña')->name('cambiar.contraseña.store');

/*
|--------------------------------------------------------------------------
|           FIN    ADMINISTRACION USUARIOS
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
|               DESESTIMAR FECHAS
|--------------------------------------------------------------------------
*/
Route::get('/desestimarFechas' , 'AdministracionController@desestimarFechas')->name('vista.desestimarFechas');
Route::post('/desestimarFechas' , 'AdministracionController@guardarFechas')->name('desestimarFechas.guardarFechas');
Route::post('/obtenerFecha' , 'AdministracionController@obtenerFecha')->name('desestimarFechas.obtenerFecha');
Route::get('/eliminarFecha/{id}' , 'AdministracionController@eliminarFecha')->name('desestimarFechas.eliminarFecha');
Route::post('/obtenerFechasPorAño' , 'AdministracionController@obtenerFechasPorAño')->name('desestimarFechas.obtenerFechasPorAño');
/*
|--------------------------------------------------------------------------
|           FIN    DESESTIMAR FECHAS
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
|               PAGINA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/principal' , 'PrincipalController@index')->name('vista.principal');
Route::get('/contratas-a-vencer' , 'PrincipalController@contratasAVencer')->name('vista.contratas.vencer')->middleware('auth.admin');
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

//SUBCATEGORIAS
Route::get('/sub-categorias/{id}' , 'TiposGastosController@VerSubcategorias')->name('VerSubcategorias');
Route::post('/agregar/sub-categorias/{id}' , 'TiposGastosController@agregarSubcategoria')->name('agregarSubcategoria');
Route::post('/editar/sub-categorias/{id}' , 'TiposGastosController@editarSubcategoria')->name('editarSubcategoria');
Route::get('/eliminar/sub-categorias/{id}' , 'TiposGastosController@eliminarSubcategoria')->name('eliminarSubcategoria');

Route::post('/agregarCategoria' , 'TiposGastosController@AgregarCategoria')->name('agregarCategoria');
Route::get('/preedit/{id}' , 'TiposGastosController@pre_edit')->name('pre_edit');
Route::post('/edit/{id}' , 'TiposGastosController@edit')->name('edit.categoria');

Route::get('/destroy/{id}' , 'TiposGastosController@destroy')->name('destroy');

Route::get('/gastos' , 'TiposGastosController@vista_gastos')->name('vista.gastos');
Route::post('/agregarGasto' , 'TiposGastosController@agregarGasto')->name('agregarGasto');
Route::post('/edit-gastos/{id}' , 'TiposGastosController@edit_gasto_categoria')->name('editarGasto');

Route::post('/entregar-cobrador/{id}' , 'TiposGastosController@Entregar')->name('entregar.cobrador');
Route::post('/recibi-cobrador/{id}' , 'TiposGastosController@Recibi')->name('recibi.cobrador');