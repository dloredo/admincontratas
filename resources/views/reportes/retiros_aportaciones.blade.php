@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Reporte retiros y aportaciones</h2>
<div class="block">
    <div class="block-content">
        <form class="form-inline" action="{{ route('comisiones_gastos') }}" method="get" id="filtrar_rango_fecha">
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
                <th colspan="4" scope="col" style="font-size: 24px;" >REPORTE DE CORTES GRAL </th>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col" style="font-size: 24px;" >COMISIONES Y GASTOS </th>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col"> <br> </th>
            </tr>
            <tr style="text-align: center;">
                <th scope="col">FECHA</th>
                <th scope="col">COMISIONES</th>
                <th scope="col">GASTOS</th>
                <th scope="col">SALDO</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $saldo = 0;
                @endphp
                    <tr style="text-align: center; font-size: 15px;">
                        <td>{{date('d-m-Y', strtotime('d-m-y'))}}</td>
                        <td></td>
                        <td></td>
                        
                        <td></td>
                    </tr>    
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