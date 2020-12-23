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
            @foreach ($infoTableDeudores as $contrata)
                <tr>
                    <td>
                        {{$contrata->nombres}}
                    </td>
                    <td>
                        {{$contrata->numero_contrata}}
                    </td>
                    <td>
                        {{$contrata->adeudo}}
                    </td>
                    <td>
                        {{$contrata->fecha_termino}}
                    </td>
                    <td width="30%">
                        <form action="{{ route('agregarPago' , $contrata->idPago) }}" method="post" id="form_{{ $contrata->idPago }}">
                            @csrf
                            <input type="number" name="cantidad_pagada" id="cantidad_pagada" class="form-control" style="min-width:150px !important; ">
                            
                        </form>
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
