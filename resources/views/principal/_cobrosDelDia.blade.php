<h3>Pagos del día</h3>

<table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">No.</th>
                        <th scope="col">Pago del dia</th>
                        <th scope="col">Atraso</th>
                        <th scope="col">Pago total</th>
                        <th scope="col">Fecha de vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($infoTable as $contrata)
                        <tr>
                            <td>
                                {{$contrata->nombres}} {{$contrata->idPago}}
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
                                {{$contrata->pagos_contrata + $contrata->adeudo}}
                            </td>
                            <td>
                                {{$contrata->fecha_termino}}
                            </td>
                            <td>
                                <a href="{{ route('agregarPago' , $contrata->idPago) }}" class="btn btn-primary btn-sm">Agregar pago</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>