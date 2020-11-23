@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contratas de {{ $cliente->nombres }} {{ $cliente->apellidos }}</h2>
<div class="block">
    <div class="block-content">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
        <thead>
            <tr>
                <th style="text-align: center">Tipos de pagos</th>
                <th style="text-align: center">Fecha de inicio</th>
                <th style="text-align: center">Fecha de termino</th>
                <th style="text-align: center">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($contratas as $contrata)
            
                <tr>
                    <td style="text-align: center">{{ $contrata->tipo_plan_contrata }}</td>
                    <td style="text-align: center">{{ $contrata->fecha_inicio }}</td>
                    <td style="text-align: center">{{ $contrata->fecha_termino }}</td>
                    <td style="text-align: center">

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block">Opciones</span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                                <a class="dropdown-item" target="_blank" href="{{ route('detallesContrata',[$cliente->id,$contrata->id]) }}">
                                    <i class="fa fa-money mr-5"></i> Ver detalles y pagos
                                </a>
                                <a class="dropdown-item" href="{{ route('verPagosContrata' , $contrata->id) }}">
                                    <i class="fa fa-money mr-5"></i> Agregar pago
                                </a>
                                <a class="dropdown-item" target="_blank" href="{{ route('estadoCuenta',$contrata->id) }}">
                                    <i class="si si-printer mr-5"></i> Imprimir estado de cuenta
                                </a>
                                <a class="dropdown-item" target="_blank" href="{{ route('descargarTarjetaContrata' , $contrata->id) }}">
                                    <i class="fa fa-book mr-5"></i> Descargar tarjeta
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
@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection