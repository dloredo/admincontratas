
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


<div class="container">
    @foreach ($clientes as $cliente)
        @if( $contrata->id_cliente == $cliente->id )
        <div>
            <p class="h5" style="float: left;">Cliente: {{$cliente->nombres}} <?php echo utf8_decode(substr($cliente->apellidos,0,5))?>.</p>
            <p class="h5" style="float: right;">Prestamo: <?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?></p>      
        </div>
        @endif
    @endforeach 
    <br> <br>
    <div class="block">
        <div class="block-content">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th style="text-align: center; width: 5%;">Pagos</th>
                        <th style="text-align: center; width: 30%;">Fecha de pago</th>
                        <th style="text-align: center; width: 30%;">Cantidad a pagar</th>
                        <th style="text-align: center; width: 40%;">Firma</th>
                    </tr>
                </thead>
                <tbody>
                <?php $fechaInicio = strtotime($contrata->fecha_inicio); ?>
                <?php $fechaFin = strtotime($contrata->fecha_termino); ?>
                <?php $contador = 1; ?>
                <?php for( $i = $fechaInicio; $i <= $fechaFin; $i+=604800  ) {?>
                    <tr>
                        <th style="text-align: center"><?php echo $contador++  ?></th>
                        <th style="text-align: center"><?php echo date("d-m-Y" , $i)  ?></th>
                        <td style="text-align: center"><?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?></td>
                        <td style="text-align: center"></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table> 
        </div>
    </div>
</div>





