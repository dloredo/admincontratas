<h3>Saldos de cobradores</h3>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Telefono</th>
            <th scope="col">Saldo</th>
            @if(auth()->user()->id_rol == 1)<th scope="col">Liquidar</th>@endif
        </tr>
    </thead>
    <tbody>
        @foreach ($infoTable as $cobrador)
        <tr>
            <th scope="row">{{ $cobrador->id }}</th>
            <td>{{ $cobrador->nombres }}</td>
            <td>{{ $cobrador->telefono }}</td>
            <td>{{ "$" . number_format(round(((float)$cobrador->saldo)),0,'.',',') }}</td>
            @if(auth()->user()->id_rol == 1)
            <td>
                <button class="btn btn-primary" data-toggle="modal" data-target="#entregar{{ $cobrador->id }}">Entregar</button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#recibi{{ $cobrador->id }}">Recibi</button>
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
