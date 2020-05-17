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


<div class="block" >
<ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#btabs-static-home">Cortes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#btabs-static-profile">Movimientos</a>
                                    </li>
                                    <li class="nav-item ml-auto">
                                        <a class="nav-link" href="#btabs-static-settings">
                                            <i class="si si-settings"></i>
                                        </a>
                                    </li>
                                </ul>
    <div class="block-content block-content-full">

        <div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
            <div class="col-xl-4 d-flex align-items-stretch">
                <div class="block block-transparent bg-primary-dark d-flex align-items-center w-100">
                    <div class="block-content block-content-full">
                        <div class="py-15 px-20 clearfix border-black-op-b">
                            <div class="float-right mt-15 d-none d-sm-block">
                                <i class="si si-book-open fa-2x text-success"></i>
                            </div>
                            <div class="font-size-h3 font-w600 text-success js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="750">750</div>
                            <div class="font-size-sm font-w600 text-uppercase text-success-light">Capital total</div>
                        </div>
                        <div class="py-15 px-20 clearfix border-black-op-b">
                            <div class="float-right mt-15 d-none d-sm-block">
                                <i class="si si-wallet fa-2x text-danger"></i>
                            </div>
                            <div class="font-size-h3 font-w600 text-danger">$<span data-toggle="countTo" data-speed="1000" data-to="980" class="js-count-to-enabled">980</span></div>
                            <div class="font-size-sm font-w600 text-uppercase text-danger-light">Capital neto</div>
                        </div>
                        <div class="py-15 px-20 clearfix border-black-op-b">
                            <div class="float-right mt-15 d-none d-sm-block">
                                <i class="si si-envelope-open fa-2x text-warning"></i>
                            </div>
                            <div class="font-size-h3 font-w600 text-warning js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="38">38</div>
                            <div class="font-size-sm font-w600 text-uppercase text-warning-light">Capital en prestamo</div>
                        </div>
                        <div class="py-15 px-20 clearfix border-black-op-b">
                            <div class="float-right mt-15 d-none d-sm-block">
                                <i class="si si-users fa-2x text-info"></i>
                            </div>
                            <div class="font-size-h3 font-w600 text-info js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="260">0</div>
                            <div class="font-size-sm font-w600 text-uppercase text-info-light">Ganancia por comisiones</div>
                        </div>
                        <div class="py-15 px-20 clearfix">
                            <div class="float-right mt-15 d-none d-sm-block">
                                <i class="si si-drop fa-2x text-elegance"></i>
                            </div>
                            <div class="font-size-h3 font-w600 text-elegance js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="59">0</div>
                            <div class="font-size-sm font-w600 text-uppercase text-elegance-light">Retiros</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row #3 -->
            <div class="col-xl-8 d-flex align-items-stretch" >
                <div class="block block-themed block-mode-loading-inverse block-transparen w-100" >
                    <div class="block-header ">
                        <h3 class="block-title">
                            Información de movimientos
                        </h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-plus"></i> Agregar movimiento
                            </button>
                        </div>
                    </div>
                    <div class="block-content " style="overflow-y: scroll; max-height:400px;">
                        @if(Route::has('/capital-corte'))
                            @include('capital._tablaCortes')
                        @else
                            @include('capital._tablaMovimientos')
                        @endif
                    </div>
                </div>
            </div>

            <!-- END Row #3 -->
        </div>

    </div>
</div>


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


</script>

@endsection