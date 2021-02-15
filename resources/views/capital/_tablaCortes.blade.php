<table class="table table-bordered table-striped table-vcenter js-dataTable-full">
    <thead>
        <tr style="text-align: center;">
            <th class="d-none d-sm-table-cell">Fecha de corte</th>
            <th>capital acum. anterior</th>
            <th class="d-none d-sm-table-cell">Comisiones</th>
            <th class="d-none d-sm-table-cell">Gastos</th>
            <th class="d-none d-sm-table-cell">Capital acum. nuevo</th>
        </tr>
    </thead>
    <tbody>

    @foreach($cortes as $corte)
        <tr style="text-align: center;">
            <td>{{date('d-m-Y', strtotime($corte->created_at))}}</td>
            <td><?php echo "$" . number_format(round(((float)$corte->capital_acumulado)),2,'.',',');?></td>
            <td><?php echo "$" . number_format(round(((float)$corte->comisiones)),2,'.',',');?></td>
            <td><?php echo "$" . number_format(round(((float)$corte->gastos)),2,'.',',');?></td>
            <td><?php echo "$" . number_format(round(((float)$corte->capital_acumulado + $corte->comisiones - $corte->gastos)),2,'.',',');?></td>
        </tr>
    @endforeach
    </tbody>
</table>