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

@if(Auth::user()->id_rol == 1)
    @include('principal._principalAdmin')
@else
    @include ('principal._principalCobrador')
@endif

<div class="block">
    <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link  {{ (Request::is('principal'))? 'active' : '' }}" href="{{ route('vista.principal') }}">Saldo de cobradores</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('pagos-del-dia'))? 'active' : '' }}" href="{{ route('vista.pagosDias') }}">Pagos del día</a>
        </li>
        @if(Auth::user()->id_rol == 1)
            <li class="nav-item">
                <a class="nav-link  {{ (Request::is('contratas-a-vencer'))? 'active' : '' }}" href="{{ route('vista.contratas.vencer') }}">Contratas a vencer</a>
            </li>
        @endif
    </ul>
    <div class="block-content block-content-full">

        <div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
            <!-- Row #3 -->
            <div class="block-content tab-content">
                <div class="block block-themed block-mode-loading-inverse block-transparen w-100">
                   
                        @if(Request::is('principal'))
                            @include ('principal._saldosCobradores')
                        @elseif(Request::is('pagos-del-dia'))
                            @include ('principal._cobrosDelDia')
                        @else
                            @include ('principal._contratasVencer')
                        @endif
                    
                </div>
            </div>

            <!-- END Row #3 -->
        </div>

    </div>

    @if(Request::is('pagos-del-dia'))
    <hr style="width: 100%;"/>
    <div class="block-content block-content-full">

        <div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
            <div class="block-content tab-content">
                <div class="block block-themed block-mode-loading-inverse block-transparen w-100">
                @include ('principal._deudores')
                </div>
            </div>
        </div>

    </div>
    @endif
</div>

@foreach ($infoTable as $cobrador)
    <div class="modal fade" id="entregar{{ $cobrador->id }}" tabindex="-1" role="dialog" aria-labelledby="entregar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Dar dinero</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <form action="{{ route('entregar.cobrador' , $cobrador->id) }}" method="post">
                            @csrf
                            <label>Cantidad a entregar</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control">
                            <label>Concepto del cargo</label>
                            <textarea name="tipo" id="tipo" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-alt-success">
                        <i class="fa fa-check"></i> Entregar dinero
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@foreach ($infoTable as $cobrador)
    <div class="modal fade" id="recibi{{ $cobrador->id }}" tabindex="-1" role="dialog" aria-labelledby="recibi" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Recibir dinero</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <form  action="{{ route('recibi.cobrador' , $cobrador->id) }}" method="post">
                            @csrf
                            <label>Cantidad a recibir</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad">
                            <label>Concepto del cargo</label>
                            <textarea name="tipo" id="tipo" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-alt-success">
                        <i class="fa fa-check"></i> Recibi dinero
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
    @section('styles')
@endsection


@section('scripts')

<script>


</script>

@endsection