@extends('layouts.layout')
@section('main')
@if($message = Session::get('message'))
<div class="alert alert-success alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Correcto</h3>
    <p class="mb-0">{{ $message }}</p>
</div>
@endif
<h2 class="content-heading">Control de clientes</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Clientes</h3>
            <div style="float: right">
            <a href="{{ route('vista.agregarCliente') }}"><button type="button" class="btn btn-primary">Añadir nuevo cliente</button></a>
            </div>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">NOMBRE</th>
                        <th class="text-center">Telefono</th>
                        <th class="text-center">Direccion</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td class="text-center">{{ $cliente->id }}</td>
                        <td class="font-w600">{{ $cliente->nombres }}  {{ $cliente->apellidos }}</td>
                        <td class="d-none d-sm-table-cell">{{ $cliente->telefono }}</td>
                        <td class="d-none d-sm-table-cell">{{ $cliente->direccion }}</td>
                        <td class="text-center">
                            <a href="{{ route('vista.agregarContrata' , $cliente->id) }}"><button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" title="Dar contrata">Dar contrata</button></a>
                            <button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" title="Dar contrata">Ver pagos</button>
                            <a href="{{ route('edit.cambiarEstatusCliente' , [ 'id' => $cliente->id, 'estatus' => $cliente->activo]) }}" type="button" class="btn btn-sm {{($cliente->activo == 'Activo')? 'btn-warning' : 'btn-success' }}">{{($cliente->activo == 'Activo')? 'Inactivar' : 'Activar' }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="modal-popin" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Eliminar este cliente</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p id="modalTextContent">¿Esta seguro de eliminar a este cliente?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                    <a id="linkEliminar" type="button" class="btn btn-alt-danger">
                        <i class="fa fa-check"></i> Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
@endsection


@section('scripts')
    <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/pages/be_tables_datatables.min.js"></script>
@endsection