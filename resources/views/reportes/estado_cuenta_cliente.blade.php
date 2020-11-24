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
      <td colspan="7">Fecha: {{ date('d-m-Y') }}</td>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="7" scope="col" style="font-size: 24px;" >ESTADO DE CUENTA</th>
    </tr>
    <tr style="text-align: center;"> 
      <th colspan="7" scope="col"> <br> </th>
    </tr>
    <tr style="text-align: center;">
      <th scope="col">NO.</th>
      <th scope="col">NOMBRE</th>}
      <th scope="col">DIRECCION</th>
      <th scope="col">COLONIA</th>
      <th scope="col">CIUDAD</th>
      <th scope="col">TELEFONO</th>
      <th scope="col">TELEFONO 2</th>
    </tr>
  </thead>
  <tbody>
  
    @foreach ($cliente as $cliente)
    <tr style="text-align: center; font-size: 15px;">
      <td>{{ $cliente->id }}</td>
      <td>{{ $cliente->nombres }}</td>
      <td>{{ $cliente->direccion }}</td>
      <td>{{ $cliente->colonia }}</td>
      <td>{{ $cliente->ciudad }}</td>
      <td>{{ $cliente->telefono }}</td>
      <td>{{ $cliente->telefono_2 }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<table style="width: 100%;">
  <thead>
    <tr style="text-align: center;"> 
      <th colspan="5" scope="col"> <br> </th>
    </tr>
    <tr style="text-align: center;">
      <th scope="col">PRESTAMO</th>
      <th scope="col">COMISION</th>}
      <th scope="col">TOTAL</th>
      <th scope="col">SALDO ACTUAL</th>
      <th scope="col">FORMA DE PAGO</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($contrata as $dato)
    <tr style="text-align: center;">
      <td>{{ "$" . number_format(round(((float)$dato->cantidad_prestada)),2,'.',',') }}</td>  
      <td>{{ "$" . number_format(round(((float)$dato->comision)),2,'.',',') }}</td> 
      <td>{{ "$" . number_format(round(((float)$dato->cantidad_pagar)),2,'.',',') }}</td> 
      <td>{{ "$" . number_format(round(((float)$dato->cantidad_pagar-$saldo_actual)),2,'.',',') }}</td> 
      <td>{{ "$" . number_format(round(((float)$dato->pagos_contrata)),2,'.',',') }} {{ $dato->tipo_plan_contrata }}</td>  
    </tr>
    <?php $cantidad_pagar = 0;  ?>
    <?php $cantidad_pagar = $dato->cantidad_pagar;  ?>
    <?php $cantidad_pagar_tem = 0;  ?>
    <?php $cantidad_pagar_tem = $dato->cantidad_pagar;  ?>
    @endforeach
  </tbody>
</table>
<br>
<table style="width: 100%;">
  <thead>
    <tr style="text-align: center;">
      <th scope="col">FECHA</th>
      <th scope="col">CARGO</th>}
      <th scope="col">ABONO</th>
      <th scope="col">ADELANTOS</th>
      <th scope="col">ATRASOS</th>
      <th scope="col">SALDO</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($pagos as $dato)
      <tr style="text-align: center;">
        <td>{{ date('d-m-Y', strtotime( $dato->fecha_pago )) }}</td>  
        <td>{{ "$" . number_format(round(((float)$cantidad_pagar_tem)),0,'.',',') }}</td> 
        <td>{{ "$" . number_format(round(((float)$dato->cantidad_pagada)),0,'.',',') }}</td> 
        <td>{{ "$" . number_format(round(((float)$dato->adelanto)),0,'.',',') }}</td> 
        <td>{{ "$" . number_format(round(((float)$dato->adeudo)),0,'.',',') }}</td>
        <td>{{ "$" . number_format(round(((float)$cantidad_pagar-=$dato->cantidad_pagada)),0,'.',',') }}</td> 
        <?php $cantidad_pagar_tem -=$dato->cantidad_pagada ?> 
      </tr>
    @endforeach
  </tbody>
</table>