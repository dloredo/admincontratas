<h3>Saldos de cobradores</h3>
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
        @foreach ($infoTable as $cobrador)
        <tr>
            <th scope="row">{{ $cobrador->id }}</th>
            <td>{{ $cobrador->nombres }}</td>
            <td>{{ $cobrador->telefono }}</td>
            <td>{{ "$" . number_format(round(((float)$cobrador->saldo)),2,'.',',') }}</td>
            <td>
                <button class="btn btn-primary" data-toggle="modal" data-target="#entregar{{ $cobrador->id }}">Entregar</button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#recibi{{ $cobrador->id }}">Recibi</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
