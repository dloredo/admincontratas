<h3>Contratas a 10 dias de vencer</h3>

<table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Saldo</th>
                        <th scope="col">Tipo de contrata</th>
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
                                {{substr(ucwords(strtolower($contrata->nombres)), 0, 18)}}
                            </td>
                            <td>
                                {{"$" . number_format(round(((float)$contrata->cantidad_pagar - $contrata->control_pago)),0,'.',',')}}
                            </td>
                            <td>
                                {{$contrata->tipo_plan_contrata}}
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