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
        @if(Auth::user()->id_rol == 1)
        <li class="nav-item">
            <a class="nav-link  {{ (Request::is('principal'))? 'active' : '' }}" href="{{ route('vista.principal') }}">Saldo de cobradores</a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('pagos-del-dia'))? 'active' : '' }}" href="{{ route('vista.pagosDias') }}">Pagos del día</a>
        </li>

    </ul>
    <div class="block-content block-content-full">

        <div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
            <!-- Row #3 -->
            <div class="block-content tab-content">
                <div class="block block-themed block-mode-loading-inverse block-transparen w-100">
                   
                        @if(Request::is('principal'))
                            @include ('principal._saldosCobradores')
                        @else
                            @include ('principal._cobrosDelDia')
                        @endif
                    
                </div>
            </div>

            <!-- END Row #3 -->
        </div>

    </div>
</div>



<!--<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Lista de pagos del dia</h3>
    </div>
    <div class="block-content block-content-full">
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th class="d-none d-sm-table-cell">Dirección</th>
                    <th class="d-none d-sm-table-cell">Pago</th>
                    <th class="d-none d-sm-table-cell">Estatus</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
    </div>
</div> -->


@endsection

@section('styles')

@endsection


@section('scripts')

<script>


</script>

@endsection