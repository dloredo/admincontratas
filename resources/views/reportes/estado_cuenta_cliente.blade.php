<style>
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
  }
</style>

<table style="width: 100%;">
  <thead>
    <tr style="text-align: center;">
      <td colspan="5">Fecha: {{ date('d-m-Y') }}</td>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="5" scope="col" style="font-size: 24px;" >DIRECTORIO DE CLIENTES </th>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="5" scope="col"> <br> </th>
    </tr>
    <tr style="text-align: center;">
      <th scope="col">NOMBRE</th>}
      <th scope="col">DIRECCION</th>
      <th scope="col">COLONIA</th>
      <th scope="col">CIUDAD</th>
      <th scope="col">TELEFONO</th>
    </tr>
  </thead>
  <tbody>
  
    @foreach ($clientes as $cliente)
    <tr style="text-align: center; font-size: 15px;">
      <td>{{ $cliente->nombres }}</td>
      <td>{{ $cliente->direccion }}</td>
      <td>{{ $cliente->colonia }}</td>
      <td>{{ $cliente->ciudad }}</td>
      <td>{{ $cliente->telefono }}</td>
    </tr>
    @endforeach
  </tbody>
</table>