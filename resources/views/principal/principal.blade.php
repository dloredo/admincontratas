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
    @include('principal._principalCobrador')
@endif

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