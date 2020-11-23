<style>
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }
</style>

<table style="width: 100%;">
    <thead>
        <tr style="text-align: center;">
            <td colspan="9">Fecha: {{ date('d-m-Y') }}</td>
        </tr>
        <tr style="text-align: center;"> 
            <th colspan="9" scope="col" style="font-size: 24px;" >REPORTE GENERAL DE COBRANZA</th>
        </tr>
        <tr style="text-align: center;"> 
            <th colspan="9" scope="col"> <br> </th>
        </tr>
        <tr style="text-align: center;">
            <th scope="col">No.</th>
            <th scope="col">Nombre</th>
            <th scope="col">Actual</th>
            <th scope="col">Atraso</th>
            <th scope="col">Adelanto</th>
            <th scope="col">Pago total</th>
            <th scope="col">Fecha V</th>
            <th scope="col">Hora</th>
            <th scope="col" style="width: 15%;">Pago</th>
        </tr>
    </thead>
    <tbody>
        <?php $pago_total = 0 ?>
        <?php $atraso_total = 0 ?>
        <?php $adelanto_total = 0 ?>
        <?php $actual_total = 0 ?>
        @foreach($cobranza as $contrata)
            <tr style="text-align: center;">
                <td>
                    {{$contrata->id}}
                </td>
                <td>
                    {{$contrata->nombres}}
                </td>
                <td>
                    {{"$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',')}}
                    <?php $actual_total += $contrata->pagos_contrata ?>
                </td>
                <td>
                    {{"$" . number_format(round(((float)$contrata->adeudo)),2,'.',',')}}
                    <?php $atraso_total += $contrata->adeudo ?>
                </td>
                <td>
                    {{"$" . number_format(round(((float)$contrata->cantidad_pagada)),2,'.',',')}}
                    <?php $adelanto_total += $contrata->cantidad_pagada ?>
                </td>
                <td>
                    {{"$" . number_format(round(((float)$contrata->pagos_contrata + $contrata->adeudo - $contrata->cantidad_pagada)),2,'.',',')}}
                    <?php $pago_total += $contrata->pagos_contrata + $contrata->adeudo - $contrata->cantidad_pagada ?>
                </td>
                <td>
                    {{ date('d-m-Y', strtotime($contrata->fecha_termino)) }}
                </td>
                <td>
                    {{ $contrata->hora_cobro }}
                </td>
                <td>
           
                </td>
            </tr>
        @endforeach
            <tr style="text-align: center;">
                <td></td>
                <td></td>
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