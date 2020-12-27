<style>
  table, th, td {
    border: 1px solid black;
    height: 30px;
    border-collapse: collapse;
  }
</style>

<table style="width: 100%;">
    <thead>
        <tr style="text-align: center;">
            <td colspan="8">Fecha: {{ date('d-m-Y') }}</td>
        </tr>
        <tr style="text-align: center;"> 
            <th colspan="8" scope="col" style="font-size: 24px;" >REPORTE GENERAL DE COBRANZA</th>
        </tr>
        <tr style="text-align: center;"> 
            <th colspan="8" scope="col"> <br> </th>
        </tr>
        <tr style="text-align: center;">
            <th scope="col" style="width: 5%;">No.</th>
            <th scope="col" style="width: 24%;">Nombre</th>
            <th scope="col" style="width: 15%;">Actual</th>
            <th scope="col" style="width: 15%;">Atraso</th>
            <th scope="col" style="width: 15%;">Adelanto</th>
            <th scope="col" style="width: 15%;">Total</th>
            <th scope="col" style="width: 15%;">Fecha V</th>
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
                    {{$contrata->numero_contrata}}
                </td>
                <td>
                    {{substr(ucwords(strtolower($contrata->nombres)), 0, 18)}}
                </td>
                <td>
                    {{"$" . number_format(round(((float)$contrata->pagos_contrata)),0,'.',',')}}
                    <?php $actual_total += $contrata->pagos_contrata ?>
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
                    {{"$" . number_format(round(((float)$contrata->pagos_contrata + $contrata->adeudo - $contrata->cantidad_pagada)),0,'.',',')}}
                    <?php $pago_total += $contrata->pagos_contrata + $contrata->adeudo - $contrata->cantidad_pagada ?>
                </td>
                <td>
                    {{ date('d-m-Y', strtotime($contrata->fecha_termino)) }}
                </td>
                <td>
           
                </td>
            </tr>
        @endforeach
            <tr style="text-align: center;">
                <td></td>
                <td style="text-align: right;">SUMA: </td>
                <td>{{"$" . number_format(round(((float)$actual_total)),0,'.',',')}}</td>
                <td>{{"$" . number_format(round(((float)$atraso_total)),0,'.',',')}}</td>
                <td>{{"$" . number_format(round(((float)$adelanto_total)),0,'.',',')}}</td>
                <td>{{"$" . number_format(round(((float)$pago_total)),0,'.',',')}}</td>
                <td></td>
                <td></td>
            </tr>
    </tbody>
</table>