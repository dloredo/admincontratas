<table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Saldo</th>
                        <th scope="col">Liquidar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cobradores as $cobrador)
                    <tr>
                        <th scope="row">{{ $cobrador->id }}</th>
                        <td>{{ $cobrador->nombres }}</td>
                        <td>{{ $cobrador->telefono }}</td>
                        <td>{{ "$" . number_format(round(((float)$cobrador->saldo)),2,'.',',') }}</td>
                        <td>
                            <form class="form-inline" action="{{ route('liquidar_cobrador' , $cobrador->id) }}" method="post">
                                @csrf
                                <div class="form-group mx-sm-3 mb-2">
                                    <input type="number" class="form-control" id="saldo_nuevo" name="saldo_nuevo" value="0" placeholder="Liquidar">
                                </div>
                                <input type="submit" class="btn btn-primary mb-2" value="Liquidar">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>