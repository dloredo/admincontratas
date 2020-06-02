@extends('layouts.layout')

@section('main')
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">

    <div class="col-6 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-wallet fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="780" class="js-count-to-enabled">{{ $total_clientes_asignados }}</span></div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Clientes asignados</div>
            </div>
        </a>
    </div>
    
</div>
<h2 class="content-heading">Clientes asignados</h2>

<div class="block">
    <div class="block-content">
        <div class="block">
            
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="text-align: center;">#</th>
                            <th style="text-align: center;">Cliente</th>
                            <th class="d-none d-sm-table-cell" style="text-align: center;">Dirección</th>
                            <th class="d-none d-sm-table-cell" style="text-align: center;">Teléfono</th>
                            <th class="d-none d-sm-table-cell" style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td style="text-align: center;">{{ $cliente->id }}</td>
                            <td style="text-align: center;">{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                            <td style="text-align: center;">{{ $cliente->direccion }}</td>
                            <td style="text-align: center;">{{ $cliente->telefono }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('verContratasCliente' , $cliente->id) }}" type="button" class="btn btn-success">Ver contratas</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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