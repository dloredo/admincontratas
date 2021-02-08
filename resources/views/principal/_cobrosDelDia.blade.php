<h3>Pagos del día</h3>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr style="text-align: center;">
                <th scope="col">Nombre</th>
                <th scope="col">No.</th>
                <th scope="col">Pago actual</th>
                <th scope="col">Atraso</th>
                <th scope="col">Adelanto</th>
                <th scope="col">Pago total</th>
                <th scope="col">Fecha de vencimiento</th>
                <th scope="col" style="text-align: left;">Pago</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php $pago_total = 0;
                $atraso_total = 0;
                $adelanto_total = 0;
                $actual_total = 0;
            @endphp
            @foreach($infoTable as $contrata)
                <tr style="text-align: center;">
                    <td style="text-align: left;">
                        {{substr(ucwords(strtolower($contrata->nombres)), 0, 18)}}
                    </td>
                    <td>
                        {{$contrata->numero_contrata}}
                    </td>
                    <td>
                        {{"$" . number_format(round(((float)($contrata->dia_pago_anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata)),0,'.',',')}}
                        <?php $actual_total += ($contrata->dia_pago_anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata ?>
                    </td>
                    <td>
                        {{"$" . number_format(round(((float)$contrata->adeudo)),0,'.',',')}}
                        <?php $atraso_total += $contrata->adeudo ?>
                    </td>
                    <td>
                        {{"$" . number_format(round(((float)$contrata->cantidad_pagada)),0,'.',',')}}
                        <?php $adelanto_total += $contrata->cantidad_pagada ?>
                    </td>
                    <td>
                        {{"$" . number_format(round(((float)(($contrata->dia_pago_anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata) + $contrata->adeudo - $contrata->cantidad_pagada)),0,'.',',')}}
                        <?php $pago_total += (($contrata->dia_pago_anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata) + $contrata->adeudo - $contrata->cantidad_pagada ?>
                    </td>
                    <td>
                        {{date('d-m-Y', strtotime($contrata->fecha_termino))}}
                    </td>
                    <form action="{{ route('agregarPago' , $contrata->idPago) }}" method="post" id="form_{{ $contrata->idPago }}">
                    <td width="30%">
                        @csrf
                        <input type="number" name="cantidad_pagada" id="cantidad_pagada" class="form-control" style="min-width:150px !important; ">  
                    </td>
                    </form>
                    <td>
                        <button style="font-size: 12px;" type="button" class="btn btn-primary btn-sm" onclick=" document.getElementById('form_{{ $contrata->idPago }}').submit() " >Agregar</button>
                    </td>
                </tr>
            @endforeach
                <tr style="text-align: center;">
                    <td></td>
                    <td style="text-align:right;">TOTAL:</td>
                    <td>{{"$" . number_format(round(((float)$actual_total)),0,'.',',')}}</td>
                    <td>{{"$" . number_format(round(((float)$atraso_total)),0,'.',',')}}</td>
                    <td>{{"$" . number_format(round(((float)$adelanto_total)),0,'.',',')}}</td>
                    <td>{{"$" . number_format(round(((float)$pago_total)),0,'.',',')}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
</div>
