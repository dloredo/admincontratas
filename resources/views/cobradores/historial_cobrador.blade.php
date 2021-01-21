@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Historial de saldo</h2>
<div class="block">
    <div class="block-content">
        <div class="responsive-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr style="text-align: center;">
                            <th scope="col"  colspan="2">Cargos</th>
                        </tr>
                        <tr  style="text-align: center;">
                            <th scope="col">Concepto</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    @php
                        $cargo_suma = 0;
                    @endphp
                    <tbody>
                        @foreach ($cargos as $cargo)
                            <tr style="text-align: center;">
                                <td>{{ $cargo->tipo }}</td>
                                <td>{{ "$" . number_format(round(((float)$cargo->cantidad)),0,'.',',') }}</td>
                                @php
                                    $cargo_suma+=$cargo->cantidad;
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr  style="text-align: right;">
                            <td scope="col"> Total: </td>
                            <td scope="col" style="text-align: left">{{ "$" . number_format(round(((float)$cargo_suma)),0,'.',',') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="block">
    <div class="block-content">
        <div class="responsive-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr style="text-align: center;">
                            <th scope="col"  colspan="2">Abonos</th>
                        </tr>
                        <tr  style="text-align: center;">
                            <th scope="col">Concepto</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    @php
                        $abonos_suma = 0;   
                    @endphp
                    <tbody>
                        @foreach ($abonos as $abono)
                            <tr style="text-align: center;">
                                <td>{{ $abono->tipo }}</td>
                                <td>{{ "$" . number_format(round(((float)$abono->cantidad)),0,'.',',') }}</td>
                                @php
                                    $abonos_suma += $abono->cantidad;
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr  style="text-align: right;">
                            <td scope="col">Total:</td>
                            <td scope="col" style="text-align: left">{{ "$" . number_format(round(((float)$abonos_suma)),0,'.',',') }}</td>
                        </tr>
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