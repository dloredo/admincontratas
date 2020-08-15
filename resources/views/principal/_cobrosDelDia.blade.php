<h3>Pagos del día</h3>

<table class="table">
                <thead>
                    <tr>
                        <th scope="col">Cliente</th>
                        <th scope="col">Prestamo</th>
                        <th scope="col">Comision</th>
                        <th scope="col">Total pagado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($infoTable as $contrata)
                        <tr>
                            <td>
                                {{$contrata->nombres}}
                            </td>
                            <td>
                                {{$contrata->cantidad_prestada}}
                            </td>
                            <td>
                                {{$contrata->comision}}
                            </td>
                            <td>
                                {{$contrata->control_pago}}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm">Agregar pago</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>