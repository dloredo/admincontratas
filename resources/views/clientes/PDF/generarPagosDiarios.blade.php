<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Document</title>
</head>
<style>
    html, body{
        height: 100%;
    }
    .contenedor{
        width: 49%;
        margin: 1% 1%;
    }
</style>
<body>

    @foreach ($clientes as $cliente)
        @if( $contrata->id_cliente == $cliente->id )
            <div>
                <p class="h5" style="float: left;">Cliente: {{$cliente->nombres}} <?php echo utf8_decode(substr($cliente->apellidos,0,2))?>.</p>
                <p class="h5" style="float: right;">Prestamo: <?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?> 
                y Pagos de: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?></p>
            </div>
        @endif
    @endforeach 
    <br> <br>

    <div> 
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th style="text-align: center; width: 5%;">Pagos</th>
                    <th style="text-align: center; width: 30%;">Fecha de pago</th>
                    <th style="text-align: center; width: 40%;">Firma</th>
                </tr>
            </thead>
            <tbody>
            <?php $fechaInicio = strtotime($contrata->fecha_inicio); ?>
            <?php $fechaFin = strtotime($contrata->fecha_termino); ?>
            <?php $contador = 0 ?>
            <?php $dia = 86400; ?>
            @for ( $i = $fechaInicio; $i <= $fechaFin; $i+=$dia )
                <tr>
                    <th style="text-align: center"><?php echo $contador += 1  ?></th>
                    <th style="text-align: center"><?php echo date("d-m-Y" , $i)  ?></th>
                    <td style="text-align: center"></td>
                </tr>
            @endfor
            </tbody>
        </table> 
    </div>  
</body>
</html>