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
      <th colspan="5" scope="col" style="font-size: 24px;" >SALDO GLOBAL DE CLIENTES</th>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="5" scope="col"> <br> </th>
    </tr>
    <tr style="text-align: center;">
      <th scope="col">NO.</th>
      <th scope="col">NOMBRE</th>
      <th scope="col">PRESTAMO TOTAL</th>
      <th scope="col">ABONO</th>
      <th scope="col">C. PARCIAL</th>
    </tr>
  </thead>
  <tbody>
  @php
    $prestamo_total_suma = 0;
    $parcial_suma = 0;
    $abono_suma = 0;
  @endphp
  @foreach($clientes as $cliente)
    <tr style="text-align: center; font-size: 15px;">
      <td>{{ $cliente->numero_contrata }}</td>
      <td>{{substr(ucwords(strtolower($cliente->nombres)), 0, 18)}}</td>
      <td>{{"$" . number_format(round(((float)$cliente->cantidad_pagar)),0,'.',',')}}</td>
      <td>{{"$" . number_format(round(((float)$cliente->abono)),0,'.',',')}}</td>
      <td>{{"$" . number_format(round(((float)$cliente->parcial)),0,'.',',')}}</td>
      @php
        $prestamo_total_suma += $cliente->cantidad_pagar;
        $parcial_suma += $cliente->parcial;
        $abono_suma += $cliente->abono;
      @endphp
    </tr>
  @endforeach
    <tr style="text-align: center;"> 
      <th colspan="5" scope="col"> <br> </th>
    </tr>
    <tr style="text-align: center;">
        <td></td>
        <td>SUMA:</td>
        <td>{{"$" . number_format(round(((float)$prestamo_total_suma)),0,'.',',')}}</td>
        <td>{{"$" . number_format(round(((float)$abono_suma)),0,'.',',')}}</td>
        <td>{{"$" . number_format(round(((float)$parcial_suma)),0,'.',',')}}</td>
    </tr>
  </tbody>
</table>