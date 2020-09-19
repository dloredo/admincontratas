<h3>Contratas a 10 dias de vencer</h3>

<table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Prestamo</th>
                        <th scope="col">Fecha de inicio</th>
                        <th scope="col">Fecha de termino</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($infoTable as $contrata)
                        <tr>
                            <td>
                                {{$contrata->id}}
                            </td>
                            <td>
                                {{$contrata->nombres}}
                            </td>
                            <td>
                                {{$contrata->cantidad_prestada}}
                            </td>
                            <td>
                                {{$contrata->fecha_inicio}}
                            </td>
                            <td>
                                {{$contrata->fecha_termino}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>