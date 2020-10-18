<h3>Pagos del día</h3>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">No.</th>
                <th scope="col">Pago actual</th>
                <th scope="col">Atraso</th>
                <th scope="col">Adelanto</th>
                <th scope="col">Pago total</th>
                <th scope="col">Fecha de vencimiento</th>
                <th scope="col">Hora</th>
                <th scope="col">Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php $pago_total = 0 ?>
            <?php $atraso_total = 0 ?>
            <?php $adelanto_total = 0 ?>
            <?php $actual_total = 0 ?>
            @foreach($infoTable as $contrata)
                <tr>
                    <td>
                        {{$contrata->nombres}}
                    </td>
                    <td>
                        {{$contrata->id}}
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
                        {{$contrata->fecha_termino}}
                    </td>
                    <td>
                        {{ $contrata->hora_cobro }}
                    </td>
                    <td>
                        <form action="{{ route('agregarPago' , $contrata->idPago) }}" method="post" id="form_{{ $contrata->idPago }}">
                            @csrf
                            <input type="number" name="cantidad_pagada" id="cantidad_pagada" class="form-control">
                            
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" onclick=" document.getElementById('form_{{ $contrata->idPago }}').submit() " >Agregar pago</button>
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{"$" . number_format(round(((float)$actual_total)),2,'.',',')}}</td>
                    <td>{{"$" . number_format(round(((float)$atraso_total)),2,'.',',')}}</td>
                    <td>{{"$" . number_format(round(((float)$adelanto_total)),2,'.',',')}}</td>
                    <td>{{"$" . number_format(round(((float)$pago_total)),2,'.',',')}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
</div>
