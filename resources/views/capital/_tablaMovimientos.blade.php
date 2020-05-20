<table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th class="d-none d-sm-table-cell">Cantidad</th>
                                    <th class="d-none d-sm-table-cell">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>

                                
                            @foreach($movimientos as $movimiento)
                                <tr>
                                    <td>{{$movimiento->tipo_movimiento}}</td>
                                    <td>{{$movimiento->total}}</td>
                                    <td>{{$movimiento->created_at}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
