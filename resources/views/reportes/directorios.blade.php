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
      <td colspan="6">Fecha: {{ date('d-m-Y') }}</td>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="6" scope="col" style="font-size: 24px;" >DIRECTORIO DE CLIENTES </th>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="6" scope="col"> <br> </th>
    </tr>
    <tr style="text-align: center;">
      <th scope="col">NOMBRE</th>}
      <th scope="col">DIRECCION</th>
      <th scope="col">COLONIA</th>
      <th scope="col">CIUDAD</th>
      <th scope="col">TELEFONO</th>
      <th scope="col">TELEFONO 2</th>
    </tr>
  </thead>
  <tbody>
  
    @foreach ($clientes as $cliente)
    <tr style="text-align: center; font-size: 15px;">
      <td>{{substr(ucwords(strtolower($cliente->nombres)), 0, 25)}}..</td>
      <td>{{substr(ucwords(strtolower($cliente->direccion)), 0, 18)}}</td>
      <td>{{substr(ucwords(strtolower($cliente->colonia)), 0, 18)}}</td>
      <td>{{substr(ucwords(strtolower($cliente->ciudad)), 0, 18)}}</td>
      <td>{{ $cliente->telefono }}</td>
      <td>{{ $cliente->telefono_2 }}</td>
    </tr>
    @endforeach
  </tbody>
</table>