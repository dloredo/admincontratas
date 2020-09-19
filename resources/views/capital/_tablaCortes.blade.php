<table class="table table-bordered table-striped table-vcenter js-dataTable-full">
    <thead>
        <tr>
            <th>capital acumulado</th>
            <th class="d-none d-sm-table-cell">saldo efectivo</th>
            <th class="d-none d-sm-table-cell">capital parcial</th>
            <th class="d-none d-sm-table-cell">Comisiones</th>
            <th class="d-none d-sm-table-cell">Fecha de corte</th>
        </tr>
    </thead>
    <tbody>

    @foreach($cortes as $corte)

        <tr>
            <td>{{$corte->capital_acumulado}}</td>
            <td>{{$corte->saldo_efectivo}}</td>
            <td>{{$corte->capital_parcial}}</td>
            <td>{{$corte->comisiones}}</td>
            <td>{{$corte->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>