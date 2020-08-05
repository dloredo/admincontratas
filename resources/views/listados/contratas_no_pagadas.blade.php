<h4 class="font-w400">Contratas no pagadas</h4>
<div style="float: right;">
<form action="{{ route('vista.noPagadas') }}" method="get">
    <input type="date" name="fecha_inicio" id="fecha_inicio">
    <input type="date" name="fecha_fin" id="fecha_fin">
    <input type="submit" value="Filtrar" class="btn btn-primary">
</form>
    
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Telefono</th>
            <th scope="col">Cantidad prestada</th>
            <th scope="col">Fecha de entrega</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($contratas_no_pagadas as $contrata)
        <tr>
            <th scope="row">{{ $contrata->id }}</th>
            <td>{{ $contrata->nombres }}</td>
            <td>{{ $contrata->telefono }}</td>
            <td>{{ $contrata->cantidad_prestada }}</td>
            <td>{{ $contrata->fecha_entrega }}</td>
        </tr>
        @endforeach
    </tbody>
</table>