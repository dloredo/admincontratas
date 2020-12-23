@extends('layouts.layout')

@section('main')
@if($message = Session::get('message'))
<div class="alert {{ (Session::get('estatus'))? 'alert-success' : 'alert-danger' }} alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">{{ (Session::get('estatus'))? 'Correcto' : 'Error' }}</h3>
    <p class="mb-0">{{ $message }}</p>
</div>
@endif
<h2 class="content-heading">Contratas de todos los usuarios</h2>

<div class="block">
    <div class="block-content">
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr style="text-align: center;">
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Fecha termino</th>
                            <th scope="col">Prestamo sin Comisión</th>
                            <th scope="col">Comisión</th>
                            <th scope="col">Prestamo total</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contratas as $contrata)
                                <tr style="text-align: center;">
                                    <th scope="row">{{$contrata->numero_contrata}}</th>
                                    <td>{{ $contrata->nombres }}</td>
                                    <td>{{date('d-m-Y', strtotime($contrata->fecha_inicio))}}</td>
                                    <td>{{date('d-m-Y', strtotime($contrata->fecha_termino))}}</td>
                                    <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),0,'.',',');?> </td>
                                    <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->comision)),0,'.',',');?> </td>
                                    <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_pagar)),0,'.',',');?> </td>
                                    <td>
                                    <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user d-sm-none"></i>
                                        <span class="d-none d-sm-inline-block">Opciones</span>
                                        <i class="fa fa-angle-down ml-5"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                        <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                                        <a class="dropdown-item" href="{{ route('verPagosContrata' , $contrata->id) }}">
                                            <i class="fa fa-money mr-5"></i> Agregar pago
                                        </a>
                                        <a class="dropdown-item" href="{{ route('editarContrata' , $contrata->id) }}">
                                            <i class="fa fa-edit mr-5"></i> Editar contrata
                                        </a>
                                        <a class="dropdown-item" href="{{ route('editRenovar' , $contrata->id) }}">
                                            <i class="fa fa-edit mr-5"></i> Renovar contrata
                                        </a>
                                        <a class="dropdown-item" target="_blank" href="{{ route('descargarTarjetaContrata' , $contrata->id) }}">
                                            <i class="fa fa-book mr-5"></i> Descargar tarjeta
                                        </a>
                                    </div>
                                </div>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
        
    </div>
</div>
@endsection

@section('styles')
    
@endsection


@section('scripts')

<script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/pages/be_tables_datatables.min.js"></script>

@endsection