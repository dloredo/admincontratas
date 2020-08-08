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
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Saldo de cobradores</h3>
        </div>
        <div class="block-content block-content-full">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cobradores as $cobrador)
                    <tr>
                        <th scope="row">{{ $cobrador->id }}</th>
                        <td>{{ $cobrador->nombres }}</td>
                        <td>{{ $cobrador->telefono }}</td>
                        <td><?php echo "$" . number_format(round(((float)$cobrador->saldo)),2,'.',',');?></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
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