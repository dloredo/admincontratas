@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Reporte recuperacion general por dia</h2>
<div class="block">
    <div class="block-content">
        <table style="width: 100%;" class="table">
            <thead>
            <tr style="text-align: center;">
                <td colspan="3">Fecha: {{ date('d-m-Y') }}</td>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="3" scope="col" style="font-size: 24px;" >REPORTE RECUPERACION GRAL POR DIA</th>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="3" scope="col"> <br> </th>
            </tr>
            <tr style="text-align: center;">
                <th scope="col">FECHA</th>
                <th scope="col">RECUPERACION GRAL</th>
                <th scope="col">TOTAL</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($recuperacion as $rec)
                    <tr style="text-align: center; font-size: 15px;">
                        <td>{{date('d-m-Y', strtotime($rec->fecha))}}</td>
                        <td>{{"$" . number_format(round(((float)$rec->recuperacion_gral)),0,'.',',')}}</td>
                        @php
                            $total += $rec->recuperacion_gral
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