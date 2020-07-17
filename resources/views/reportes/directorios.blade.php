<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nombre</th>
      <th scope="col">Telefono</th>
      <th scope="col">Dirección</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($clientes as $cliente)
    <tr>
      <th scope="row">{{ $cliente->id }}</th>
      <td>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
      <td>{{ $cliente->telefono }}</td>
      <td>{{ $cliente->direccion }}</td>
    </tr>
    @endforeach
  </tbody>
</table>