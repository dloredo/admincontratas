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
        @foreach ($contratas as $contrata)
        @if ($cliente->id == $contrata->id_cliente)
            <tbody>
                <tr>
                    <td style="text-align: center">{{ $contrata->tipo_plan_contrata }}</td>
                    <td style="text-align: center">{{ $contrata->fecha_inicio }}</td>
                    <td style="text-align: center">{{ $contrata->fecha_termino }}</td>
                    <td style="text-align: center">
                        @if ($contrata->tipo_plan_contrata == "Pagos diarios" )
                            <a href="{{ route('imprimirPagosDiarios',$contrata->id) }}" type="button" class="btn btn-primary">Imprimir boleta a {{ $contrata->dias_plan_contrata }}</a>
                        @elseif($contrata->tipo_plan_contrata == "Pagos por semana" )
                            <a href="{{ route('imprimirPagosSemanales',$contrata->id) }}" type="button" class="btn btn-primary">Imprimir boleta a {{ $contrata->dias_plan_contrata }}</a>
                        @endif
                    </td>
                </tr>
            </tbody>
        @endif
        @endforeach
        
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