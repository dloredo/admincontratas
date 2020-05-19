<table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th>Capital total</th>
                                    <th class="d-none d-sm-table-cell">Capital neto</th>
                                    <th class="d-none d-sm-table-cell">Capital prestado</th>
                                    <th class="d-none d-sm-table-cell">Comision global</th>
                                    <th class="d-none d-sm-table-cell">Fecha de corte</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($cortes as $corte)

                                <tr>
                                    <td>{{$corte->capital_total}}</td>
                                    <td>{{$corte->capital_neto}}</td>
                                    <td>{{$corte->capital_en_prestamo}}</td>
                                    <td>{{$corte->comisiones}}</td>
                                    <td>{{$corte->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>