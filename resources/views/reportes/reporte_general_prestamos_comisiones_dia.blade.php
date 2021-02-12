@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Reporte general de prestamos y comisiones al dia</h2>
<div class="block">
    <div class="block-content">
        <form class="form-inline" action="{{ route('prestamos_comisiones_dia') }}" method="get" id="filtrar_rango_fecha">
            <div class="form-group">
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{ $fecha_inicio ?? date('Y-m-d')}}">
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="{{ $fecha_fin ?? date('Y-m-d')}}">
            </div>
            <button type="button" class="btn btn-success" onclick="document.getElementById('filtrar_rango_fecha').submit()">Filtrar</button>
        </form>
        <table style="width: 100%;" class="table">
            <thead>
            <tr style="text-align: center;">
                <td colspan="4">Fecha: {{ date('d-m-Y') }}</td>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col" style="font-size: 24px;" >REPORTE GENERAL DE PRESTAMOS Y COMISIONES POR DIA</th>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col"> <br> </th>
            </tr>
            <tr style="text-align: center;">
                <th scope="col">FECHA</th>
                <th scope="col">PRESTAMOS SIN COMISION</th>
                <th scope="col">COMISIONES</th>
                <th scope="col">PRESTAMOS TOTALES</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($prestamos as $rec)
                    <tr style="text-align: center; font-size: 15px;">
                        <td>{{date('d-m-Y', strtotime($rec->fecha_entrega))}}</td>
                        <td>{{"$" . number_format(round(((float)$rec->cantidad_prestada)),0,'.',',')}}</td>
                        <td>{{"$" . number_format(round(((float)$rec->comision)),0,'.',',')}}</td>
                        @php
                            $total += $rec->cantidad_prestada+$rec->comision
                        @endphp
                        <td>{{"$" . number_format(round(((float)$total)),0,'.',',')}}</td>
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