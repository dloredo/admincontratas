@extends('layouts.layout')

@section('main')
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-6 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-wallet fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="780" class="js-count-to-enabled">{{ "$" . number_format(round(((float)$saldo_cobrador)),0,'.',',') }}</span></div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Saldo actual</div>
            </div>
        </a>
    </div>
</div>
<h2 class="content-heading">Historial de saldo</h2>
<div>
    <form class="form-inline" action="{{ route('filtroSaldoCobrador') }}" method="get" id="filtrar_fecha">
        <div class="form-group">
            <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $fecha ?? date('Y-m-d')}}">
        </div>
        <button type="button" class="btn btn-success" onclick="document.getElementById('filtrar_fecha').submit()">Filtrar</button>
    </form>
</div>

<br> <br>

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