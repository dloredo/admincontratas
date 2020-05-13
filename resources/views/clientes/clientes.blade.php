@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Control de Usuarios</h2>
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
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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