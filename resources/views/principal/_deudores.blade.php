<h3>Atrasos</h3>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">No.</th>
                <th scope="col">Atraso</th>
                <th scope="col">Fecha de vencimiento</th>
                <th scope="col">Pago</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_adeudo = 0;
            @endphp
            @foreach ($infoTableDeudores as $contrata)
                <tr>
                    <td>
                        {{substr(ucwords(strtolower($contrata->nombres)), 0, 18)}}
                    </td>
                    <td>
                        {{$contrata->numero_contrata}}
                    </td>
                    <td>
                        {{"$" . number_format(round(((float)$contrata->adeudo)),0,'.',',')}}
                        @php
                            $total_adeudo += $contrata->adeudo;
                        @endphp
                    </td>
                    <td width="30%">
                        {{$contrata->fecha_termino}}
                    </td>
                    
                    <td width="30%">
                        <form action="{{ route('agregarPagoPrototipo' , $contrata) }}" method="post" id="form_deudores{{ $contrata->idPago }}">
                            @csrf
                            <input type="number" name="cantidad_pagada" id="cantidad_pagada" class="form-control" style="min-width:150px !important; ">
                            
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" onclick=" document.getElementById('form_deudores{{ $contrata->idPago }}').submit() " >Agregar pago</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td style="text-align:right;">TOTAL:</td>
                <td>{{"$" . number_format(round(((float)$total_adeudo)),0,'.',',')}}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
