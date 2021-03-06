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



<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Historial de cobros del dia</h3>
        <select name="cobrador" id="cobrador" class="form-control col-sm-3">
            <option value="" selected>Cobrador..</option>
            @foreach ($cobradores as $cobrador)
            <option value="{{$cobrador->id}}">{{$cobrador->nombres}}</option>
            @endforeach
        </select>
        <input class="form-control col-sm-3" type="date" name="date" id="date"/>
        <button class="btn btn-primary" id="btnPlicarFiltro" onclick="filtrar();">Aplicar</button>
    </div>
    <div class="block-content">
        <div class="block">
            <div class="table-responsive-sm">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Cobrador</th>
                            <th style="text-align: center;">Cliente</th>
                            <th style="text-align: center;">No. Contrada</th>
                            <th style="text-align: center;">Cantidad pagada</th>
                            <th style="text-align: center;">Fecha</th>
                            <th style="text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($cobros as $cobro)
                        <tr>
                            <td>
                                {{$cobro->cobrador->name}}
                            </td>
                            <td>
                                {{$cobro->cliente->nombres}}
                            </td>
                            <td>
                                {{$cobro->contrata->id}}
                            </td>
                            <td>
                                {{$cobro->cantidad}}
                            </td>
                            <td>
                                {{$cobro->fecha}}
                            </td>
                            <td>
                                @if($cobro->confirmado == 0)
                                    <button class="btn btn-primary btn-sm" onclick="showModal('{{$cobro->contrata->id}}','{{$cobro->id}}')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="showModalEliminar('{{$cobro->contrata->id}}','{{$cobro->id}}')">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                    <div style="text-align: right;">
                        <h3 style="text-align: right; display:inline-block; margin-bottom:5px;">Total cobrado: ${{$cobroTotal}}</h3> 
                        @if($confirmar > 0)
                            <button onclick="document.getElementById('confirmarPagos').submit()" class="btn btn-success" style="margin-bottom:10px; margin-left:10px;">Confirmar pagos</button>
                            <form id="confirmarPagos" action="{{route('historialCobranza.confirmarPagos')}}" method="POST"> 
                                    @csrf
                            </form>
                        @endif
                    </div>
                {{ $cobros->links() }}
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="modal-editar-cobro" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Editar cobro</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="formAsignar" action="{{route('historialCobranza.editar')}}" method="POST"> 
                        @csrf
                        <input type="hidden" id="contrata_id" name="contrata_id" />
                        <input type="hidden" id="cobro_id" name="cobro_id" />

                        <div class="form-group col-md-8 offset-md-2">
                            <label for="nuevo_cobro">Cobro</label>
                            <input name="nuevo_cobro" id="nuevo_cobro" type="text"  class="form-control"/>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formAsignar').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Actualizar cobro
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-eliminar-cobro" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Eliminar cobro</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content" style="text-align: center;">
                    <form id="formEliminar" action="{{ route('historialCobranza.eliminar') }}" method="POST"> 
                        @csrf
                        <input type="hidden" id="id_contrata" name="id_contrata"/>
                        <input type="hidden" id="id_cobro" name="id_cobro"/>

                        <span style="font-size: 25px;">Estas seguro de eliminarlo?</span>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formEliminar').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Eliminar cobro
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    td{
        text-align: center;
    }
</style>
@endsection


@section('scripts')

<script>

    const filtrar = () =>
    {
        fecha = document.getElementById("date").value;
        cobrador = document.getElementById("cobrador").value;
        console.log(cobrador);
        if(fecha != "" && cobrador != "")
        {
            location.href = "/cobranza/historial/" + fecha + "/" + cobrador
        }
        else if(fecha != "")
        {
            location.href = "/cobranza/historial/" + fecha
        }
        else if(cobrador != "")
        {
            location.href = "/cobranza/historial/" + cobrador
        } 
        else
        {
            location.href = "/cobranza/historial/" 
        }    
    }

    const showModal = (contrataId,cobroId) => {
        $('#contrata_id').val(contrataId);
        $('#cobro_id').val(cobroId);
        $("#modal-editar-cobro").modal("toggle");
    }

    const showModalEliminar = (id_contrata,id_cobro) => {
        $('#id_contrata').val(id_contrata);
        $('#id_cobro').val(id_cobro);
        $("#modal-eliminar-cobro").modal("toggle");
    }
</script>

@endsection