<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
    @page { margin: 5px 10px; }
    @page { size: 21.5cm 14.2cm;}
    body { margin: 5px 10px; }

    .table{
        width: 100%;
        text-align: center;
        border-spacing: 0;
      
    }

    tr
    {
        line-height: 30px !important;
        margin: 0px;
    }

    td{
        border: 1px solid #545454;
        margin: 0px;
        font-size: 12px;
        
    }

    .main-page{
        page-break-after: always;
    }

    .quarter-page, .half-page{
        display: block;
        max-height: 100%;
        float: left;
    }

    .quarter-page{width: 25%;}
    .half-page{width: 50%; text-align: center;}

    .outside-text{
        font-size: 22px;
    }
    p{
        margin: 0px;
        font-size: 17px;
    }
</style>

    
    @for($x = 0; $x < sizeof($chunks_fechas); $x++)
        @php
            $fechas = $chunks_fechas[$x];
            $i = 1;
        @endphp
        <div class="{{ ($x < sizeof($chunks_fechas)-1)? 'main-page' : '' }}">
            <div class="half-page">
                <table class="table">
                    <tr>
                        <td colspan="4" style="text-align: left; padding:5px; padding-bottom:69px;">
                            <p><strong>{{$contrata->cliente->nombres}}</strong></p>
                            <p>Forma de pago: <strong>{{$contrata->tipo_plan_contrata}}</strong></p>
                            <p>Capital: <strong>{{$contrata->cantidad_pagar}}</strong></p>
                        </td>
                    </tr>
                    @for($i; $i<=4; $i++)
                        @php
                            if($i > sizeof($fechas))
                                break;
                        @endphp
                      
                       <tr>
                           <td style="width: 10%; padding:4.8px 0px"><p>{{$i}}</p></td>
                           <td style="width: 20%; padding:4.8px 0px"><p><strong>PAGO</strong></p></td>
                           <td style="width: 30%; padding:4.8px 0px"><p><strong>{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</strong></p></td>
                           <td style="width: 30%; padding:4.8px 0px"><p><strong>${{$contrata->pagos_contrata}}</strong></p></td>
                       </tr>
                       <tr>
                           <td colspan="2" style="width: 30%; padding:4.8px 0px"><p>RECIBÍ</p></td>
                           <td colspan="2" style="width: 70%; padding:4.8px 0px"></td>
                       </tr>
                       @endfor
                </table>
            </div>
            <div class="half-page">
                <table class="table">
                    <tbody>
                        @for($i; $i<=10; $i++)
                            @php
                                if($i > sizeof($fechas))
                                    break;
                            @endphp
                        
                        <tr>
                            <td style="width: 10%; padding:4.8px 0px"><p>{{$i}}</p></td>
                            <td style="width: 20%; padding:4.8px 0px"><p><strong>PAGO</strong></p></td>
                            <td style="width: 30%; padding:4.8px 0px"><p><strong>{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</strong></p></td>
                            <td style="width: 30%; padding:4.8px 0px"><p><strong>${{$contrata->pagos_contrata}}</strong></p></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width: 30%; padding:4.8px 0px"><p>RECIBÍ</p></td>
                            <td colspan="2" style="width: 70%; padding:4.8px 0px"></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            
        </div>
    @endfor
</body>
</html>