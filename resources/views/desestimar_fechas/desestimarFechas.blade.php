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
<h2 class="content-heading">Fechas no laborales</h2>
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title"><button class="btn btn-info" id="btnAgregarFecha">Agregar Fecha</button></h3>
        <select class="form-control" name="searchYear" id="searchYear" style="width: 100px !important;">
            @foreach($años as $año)
                <option value="{{$año->anio}}">{{$año->anio}}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" id="btnPlicarFiltro" onclick="obtenerFechasPorAño()">Aplicar</button>
    </div>
    <div class="block-content block-content-full">

        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
                <tr>
                    <th class="text-center">Fecha de inicio</th>
                    <th class="text-center">Fecha de termino</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Accion</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach($fechas as $fecha)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($fecha->fecha_inicio))}}</td>
                        <td>{{date('d-m-Y', strtotime($fecha->fecha_termino))}}</td>
                        <td>{{$fecha->descripcion}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block">Opciones</span>
                                    <i class="fa fa-angle-down ml-5"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                    <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                                    <a class="dropdown-item" onclick="obtenerFecha('{{$fecha->id}}')">
                                        <i class="si si-pencil mr-5"></i> Editar
                                    </a>
                                    <a class="dropdown-item" href="{{route('desestimarFechas.eliminarFecha',$fecha->id)}}">
                                        <i class="si si-trash mr-5"></i> Eliminar
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

<div class="modal fade" id="modal-agregar-fecha" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
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
                    <form id="formDesestimarFechas" action="{{route('desestimarFechas.guardarFechas')}}" method="POST"> 
                        @csrf
                        <input type="hidden" id="id" name="id" disabled>
                        <input type="hidden" id="fechaUpdate" name="fechaUpdate" disabled>
                        <div class="form-group col-md-12">
                            <label for="fechaIncio">Fechas a desestimar</label>
                            
                            <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">Desde</span>
                                </div>
                                <input type="text" class="form-control datepicker" id="fechaInicio" name="fecha_inicio"  data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">Hasta</span>
                                </div>
                                <input type="text" class="form-control datepicker" id="fechaTermino" name="fecha_termino" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            </div>
                            
                        </div>

                        <div class="form-group col-md-12">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formDesestimarFechas').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
@endsection


@section('scripts')
<script src="{{asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(() => {
        //Codebase.helpers([ 'datepicker']);
        $('.datepicker')
            .datepicker({
                format: 'yyyy-mm-dd'
            });
            
        $("#btnAgregarFecha").click( () => $('#modal-agregar-fecha').modal("show") )

        $('#modal-agregar-fecha').on('hidden.bs.modal', function () {
            $('.datepicker').val("").datepicker("update");
            $("#descripcion").val("");
            $("#id").prop("disabled",true);
            $("#id").val("");
            $("#fechaUpdate").val("");
            $("#fechaUpdate").prop("disabled",true);
        });


    })

    const obtenerFecha = id => {
        axios.post("{{route('desestimarFechas.obtenerFecha')}}", {id:id })
            .then(response => {
                let fecha = response.data.fecha;

                $('#fechaInicio').val(fecha.fecha_inicio).datepicker("update");
                $('#fechaTermino').val(fecha.fecha_termino).datepicker("update");
                $("#descripcion").val(fecha.descripcion);

                $("#id").val(fecha.id);
                $("#id").prop("disabled",false);

                $("#fechaUpdate").val(fecha.fecha_inicio);
                $("#fechaUpdate").prop("disabled",false);

                $('#modal-agregar-fecha').modal("show");

            })
    }


    const obtenerFechasPorAño = () => {
        let año = $("#searchYear").val();

        axios.post("{{route('desestimarFechas.obtenerFechasPorAño')}}", {año:año })
            .then(response => {
                let fechas = response.data.fechas;

                $('#tbody').empty();

                for(let fecha of fechas)
                    $('#tbody').append( generateRowTable(fecha) );
                
                console.log(response)
            })
    }


    const generateRowTable = fecha => {
        return `
        <tr>
            <td>${fecha.fecha_inicio}</td>
            <td>${fecha.fecha_termino}</td>
            <td>${fecha.descripcion}</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user d-sm-none"></i>
                        <span class="d-none d-sm-inline-block">Opciones</span>
                        <i class="fa fa-angle-down ml-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                        <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                        <a class="dropdown-item" onclick="obtenerFecha('${fecha.id}')">
                            <i class="si si-pencil mr-5"></i> Editar
                        </a>
                        <a class="dropdown-item" href="/eliminarFecha/${fecha.id}">
                            <i class="si si-trash mr-5"></i> Eliminar
                        </a>

                    </div>
                </div>
            </td>
        </tr>
        `;
    }
</script>
 
@endsection

