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
                                <button class="btn btn-primary btn-sm" onclick="showModal('{{$cobro->contrata->id}}','{{$cobro->id}}')">Editar</button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                <h3 style="text-align: right;">Total cobrado: ${{$cobroTotal}}</h3>
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
        if(fecha != "")
            location.href = "/cobranza/historial/" + fecha
    }

    const showModal = (contrataId,cobroId) => {
        $('#contrata_id').val(contrataId);
        $('#cobro_id').val(cobroId);
        $("#modal-editar-cobro").modal("toggle");
    }
</script>

@endsection