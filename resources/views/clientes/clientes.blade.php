@extends('layouts.layout')
@section('main')
@if($message = Session::get('message'))
<div class="alert {{ (Session::get('estatus'))? 'alert-success' : 'alert-danger' }} alert-dismissable" role="alert">
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
        @if(Auth::user()->id_rol == 1)
        <div style="float: right">
            <a href="{{ route('vista.agregarCliente') }}"><button type="button" class="btn btn-primary">Añadir nuevo cliente</button></a>
        </div>
        @endif
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
                    <th class="text-center">Cobrador</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                <tr>
                    <td class="text-center">{{ $cliente->id }}</td>
                    <td class="font-w600">{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                    <td class="d-none d-sm-table-cell">{{ $cliente->telefono }}</td>
                    <td class="d-none d-sm-table-cell">{{ $cliente->direccion }}</td>
                    <td class="d-none d-sm-table-cell">{{ ($cliente->cobrador)? $cliente->cobrador->nombres : 'Sin cobrador' }}</td>
                    <td class="text-center">

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block">Opciones</span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                                <a class="dropdown-item" href="{{ route('verContratas' , $cliente->id) }}">
                                    <i class="si si-printer mr-5"></i> Ver contratas
                                </a>
                                @if(Auth::user()->id_rol == 1)
                                <a class="dropdown-item" href="{{ route('vista.agregarContrata' , $cliente->id) }}">
                                    <i class="si si-user mr-5"></i> Dar contrata
                                </a>

                                <a class="dropdown-item" href="{{ route('edit.cambiarEstatusCliente' , [ 'id' => $cliente->id, 'estatus' => $cliente->activo]) }}">
                                    <i class="si {{($cliente->activo == 'Activo')? 'si-close' : 'si-check' }} mr-5"></i> {{($cliente->activo == 'Activo')? 'Inactivar' : 'Activar' }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- Toggle Side Overlay -->
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                <a class="dropdown-item" href="javascript:void(0)" onclick="mostrarAgregarCobrador('{{$cliente->id}}')">
                                    <i class="fa fa-handshake-o mr-5"></i> Agregar cobrador
                                </a>
                                @endif

                            </div>
                        </div>


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


<div class="modal fade" id="modal-asignar-cobrador" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Asingar cobrador</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="formAsignar" action="{{route('clientes.asignarCobrador')}}" method="POST"> 
                        @csrf
                        <input type="hidden" id="cliente_id" name="cliente_id">
                        <div class="form-group col-md-12">
                            <label for="cobrador_id">Cobrador</label>
                            <select id="cobrador_id" name="cobrador_id" class="form-control @error('cobrador_id') is-invalid @enderror" require>
                                <option disabled>Elija el usuario cobrador</option>
                                @foreach($usuarios as $usuario)
                                    <option  value="{{$usuario->id}}">{{$usuario->nombres}} {{$usuario->apellidos}}</option>
                                @endforeach
                            </select>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formAsignar').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Asignar cobrador
                </button>
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


<script>
    function mostrarAgregarCobrador(idCliente) {
        document.getElementById('cliente_id').value  = idCliente;
        $('#modal-asignar-cobrador').modal('show');
    }
</script>
@endsection