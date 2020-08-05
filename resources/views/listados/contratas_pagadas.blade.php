<h4 class="font-w400">Contratas pagadas</h4>
<div style="float: right;">
<form action="{{ route('vista.Pagadas') }}" method="get">
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
        @foreach ($contratas_pagadas as $contrata_pagada)
        <tr>
            <th scope="row">{{ $contrata_pagada->id }}</th>
            <td>{{ $contrata_pagada->nombres }}</td>
            <td>{{ $contrata_pagada->telefono }}</td>
            <td>{{ $contrata_pagada->cantidad_prestada }}</td>
            <td>{{ $contrata_pagada->fecha_entrega }}</td>
        </tr>
        @endforeach
    </tbody>
</table>