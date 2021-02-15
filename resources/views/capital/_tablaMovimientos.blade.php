<form class="form-inline" action="{{ route('vista.capital.movimientos') }}" method="get" id="filtrar_rango_fecha">
    <div class="form-group">
        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{ $fecha_inicio ?? date('Y-m-d')}}">
        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="{{ $fecha_fin ?? date('Y-m-d')}}">
    </div>
    <button type="button" class="btn btn-success" onclick="document.getElementById('filtrar_rango_fecha').submit()">Filtrar</button>
</form>
<br>
<table id="tableMovimientos" class="table table-bordered table-striped table-vcenter js-dataTable-full">
    <thead>
        <tr>
            <th class="d-none d-sm-table-cell">Fecha</th>
            <th>Tipo</th>
            <th>Concepto</th>
            <th class="d-none d-sm-table-cell">Cantidad</th>
            <th>Saldo acumulado</th>
        </tr>
    </thead>
    <tbody>

    @php
        $saldo_acumulado = 0;
    @endphp
    @foreach($movimientos as $movimiento)
        <tr>
            <td>{{date('d-m-Y', strtotime($movimiento->created_at))}}</td>
            <td>
                @if ($movimiento->tipo_movimiento == "Abono")
                    Aportación
                @else
                    Retiro
                @endif
            </td>
            <td>{{$movimiento->concepto}}</td>
            <td>{{ "$" . number_format(round(((float)$movimiento->total)),0,'.',',') }}</td>
            @php
                if($movimiento->tipo_movimiento == "Abono"){
                    $saldo_acumulado+=$movimiento->total;
                }else {
                    $saldo_acumulado-=$movimiento->total;
                }
            @endphp
            <td>{{ "$" . number_format(round(((float)$saldo_acumulado)),0,'.',',') }}</td>
        </tr>
    @endforeach

    </tbody>
</table>
