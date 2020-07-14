@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contratas de todos los usuarios</h2>

<div class="block">
    <div class="block-content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Teléfono</th>
                    <th style="text-align: center;">Cantidad prestada</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contratas as $contrata)
                        <tr>
                            <th scope="row">{{$contrata->cliente->id}}</th>
                            <td>{{ $contrata->cliente->nombres }} {{ $contrata->cliente->apellidos }}</td>
                            <td>{{ $contrata->cliente->direccion }}</td>
                            <td>{{ $contrata->cliente->telefono }}</td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?> </td>
                            <td>
                            <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block">Opciones</span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                                <a class="dropdown-item" href="{{ route('detallesContrata',[$contrata->cliente->id,$contrata->id]) }}">
                                    <i class="fa fa-money mr-5"></i> Ver detalles y pagos
                                </a>
                                
                                @if ($contrata->tipo_plan_contrata == "Pagos diarios" )
                                    <a class="dropdown-item" href="{{ route('imprimirPagosDiarios',$contrata->id) }}">
                                        <i class="si si-printer mr-5"></i> Imprimir boleta a {{ $contrata->dias_plan_contrata }} dias
                                    </a>
                                @elseif($contrata->tipo_plan_contrata == "Pagos por semana" )
                                    <a class="dropdown-item" href="{{ route('imprimirPagosSemanales',$contrata->id) }}">
                                        <i class="si si-printer mr-5"></i> Imprimir boleta a {{ $contrata->dias_plan_contrata }} semanas
                                    </a>
                                @endif

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