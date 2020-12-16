<h3>Atrasos</h3>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">No.</th>
                <th scope="col">Atraso</th>
                <th scope="col">Fecha de vencimiento</th>
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

                </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
