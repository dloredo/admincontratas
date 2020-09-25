@extends('layouts.layout')

@section('main')

@if(Session::get('saved'))
<div class="alert alert-success alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Correcto</h3>
    <p class="mb-0">{{Session::get('message')}}</p>
</div>
@endif

<h2 class="content-heading">Control de usuarios</h2>
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Clientes</h3>
        <div style="float: right">
            <a href="{{ route('vista.agregarUsuario') }}"><button type="button" class="btn btn-primary">Añadir nuevo usuario</button></a>
        </div>
    </div>
    <div class="block-content block-content-full">
        <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th class="d-none d-sm-table-cell">Direccion</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Telefono</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Rol</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Estatus</th>
                    <th class="text-center" style="width: 15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr>
                    <td class="font-w600">{{$usuario->nombres}} {{$usuario->apellidos}}</td>
                    <td class="d-none d-sm-table-cell">{{$usuario->direccion}}</td>
                    <td class="d-none d-sm-table-cell">{{$usuario->telefono}}</td>
                    <td class="d-none d-sm-table-cell">{{$usuario->rol->rol}}</td>
                    <td class="d-none d-sm-table-cell">{{$usuario->activo}}</td>
                    <td class="text-center">
                        <a href="{{ route('edit.cambiarEstatus' , [ 'id' => $usuario->id, 'estatus' => $usuario->activo]) }}" type="button" class="btn btn-sm {{($usuario->activo == 'Activo')? 'btn-warning' : 'btn-success' }}">{{($usuario->activo == 'Activo')? 'Inactivar' : 'Activar' }}</a>
                        <!-- <button style="margin-top:10px;" type="button" class="btn btn-sm btn-danger" onclick="showModal({{$usuario->id}}, '{{$usuario->nombres}}')">Eliminar</button> -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL PARA ELIMINAR USUARIO -->
<div class="modal fade" id="modal-popin" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Eliminar este usuario</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <p id="modalTextContent">¿Esta seguro de eliminar a este usuario?</p>
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

@endsection


@section('scripts')

<script>
    function showModal(idUsuario, nombre) {
        $('#modalTextContent').html(`¿Esta seguro de eliminar al usuario ${nombre}?`)
        $("#linkEliminar").attr("href", "/eliminarUsuario/" + idUsuario)
        $('#modal-popin').modal('show');
    }
</script>

@endsection