<h3>Pagos del día</h3>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">No.</th>
                <th scope="col">Pago del dia</th>
                <th scope="col">Atraso</th>
                <th scope="col">Adelanto</th>
                <th scope="col">Pago total</th>
                <th scope="col">Fecha de vencimiento</th>
                <th scope="col">Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($infoTable as $contrata)
                <tr>
                    <td>
                        {{$contrata->nombres}}
                    </td>
                    <td>
                        {{$contrata->id}}
                    </td>
                    <td>
                        {{$contrata->pagos_contrata}}
                    </td>
                    <td>
                        {{$contrata->adeudo}}
                    </td>
                    <td>
                        {{$contrata->cantidad_pagada}}
                    </td>
                    <td>
                        {{ $contrata->pagos_contrata + $contrata->adeudo - $contrata->cantidad_pagada }}
                    </td>
                    <td>
                        {{$contrata->fecha_termino}}
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
        </tbody>
    </table>
</div>
