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
    @page { size: 21.5cm 13.6cm;}
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
        font-size: 25px;
    }
    p{
        margin: 0px;
        font-size: 17px;
    }

    .header{
        width: 100%;
        padding:0px 10px;
        margin-bottom: 0px;
        display: inline-block;
        height: 50px;
    }

    .half-page-header{
        display: inline;  
        text-align: center;
    }
    
    .left{
        float: left;
        width: 50%;
    }
   
   .right{
       float: right;
       width: 50%;
   }
</style>
    @php
         $count = 1;
    @endphp
    
    @for($x = 0; $x < sizeof($chunks_fechas); $x++)
        @php
            $fechas = $chunks_fechas[$x];
            $i = 1;
        @endphp

        <div class="main-page">
            <div class="half-page">
            </div>

            <div class="half-page" style="padding-top: 10%;">
                <p class="outside-text"><strong>{{$contrata->cliente->nombres}}</strong></p>
            </div>
        </div>

        <div class="{{ ($x < sizeof($chunks_fechas)-1)? 'main-page' : '' }}">
            <div class="header">
                <div class="half-page-header left">
                    <p style="margin-top: 10px; margin-bottom:0px;">Forma de pago: <strong>{{$contrata->dias_plan_contrata}} semanas x ${{$contrata->pagos_contrata}}</strong></p>
                </div>
                <div class="half-page-header right">
                    <p style="margin-top: 10px; margin-bottom:0px;">Capital total: <strong>${{$contrata->cantidad_pagar}}</strong></p>
                </div>
              
            </div>

            <div style="clear: both;">
            <div class="half-page">
                <table class="table">
                    @for($i; $i<=5; $i++)
                        @php
                            if($i > sizeof($fechas))
                                break;
                        @endphp
                      
                       <tr>
                           <td style="width: 10%; padding:5px 0px"><p>{{$count}}</p></td>
                           <td style="width: 20%; padding:5px 0px"><p><strong>PAGO</strong></p></td>
                           <td style="width: 30%; padding:5px 0px"><p><strong>{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</strong></p></td>
                           <td style="width: 30%; padding:5px 0px"><p><strong>${{$contrata->pagos_contrata}}</strong></p></td>
                       </tr>
                       <tr>
                           <td colspan="2" style="width: 30%; padding:5px 0px"><p>RECIBÍ</p></td>
                           <td colspan="2" style="width: 70%; padding:5px 0px"></td>
                       </tr>

                       @php
                         $count++;  
                       @endphp
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
                            <td style="width: 10%; padding:5px 0px"><p>{{$count}}</p></td>
                            <td style="width: 20%; padding:5px 0px"><p><strong>PAGO</strong></p></td>
                            <td style="width: 30%; padding:5px 0px"><p><strong>{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</strong></p></td>
                            <td style="width: 30%; padding:5px 0px"><p><strong>${{$contrata->pagos_contrata}}</strong></p></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width: 30%; padding:5px 0px"><p>RECIBÍ</p></td>
                            <td colspan="2" style="width: 70%; padding:5px 0px"></td>
                        </tr>

                        @php
                            $count++;  
                        @endphp
                        @endfor
                    </tbody>
                </table>
            </div>
            </div>
            
            
        </div>
    @endfor
</body>
</html>