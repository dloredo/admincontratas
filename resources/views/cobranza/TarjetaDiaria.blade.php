<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
    @page { margin: 0px 10px; }
    
    body { margin: 0px 10px; }

    .table{
        width: 100%;
        text-align: center;
        margin: inherit 5px;
    }

    tr
    {
        line-height: 30px !important;
        margin: 0px;
    }

    td{
        border: 1px solid #dddddd;
        margin: 0px;
        font-size: 12px;
        padding:0px;
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
        font-size: 30px;
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
            <div class="half-page">
                <p class="outside-text"><strong>{{$contrata->cliente->nombres}}</strong></p>
            </div>
        </div>

        <div class="{{ ($x < sizeof($chunks_fechas)-1)? 'main-page' : '' }}">
            <div class="header">
                <div class="half-page-header left">
                    <p style="margin-top: 10px; margin-bottom:0px;">Forma de pago: <strong>{{$contrata->dias_plan_contrata}} días x ${{$contrata->pagos_contrata}}</strong></p>
                </div>
                <div class="half-page-header right">
                    <p style="margin-top: 10px; margin-bottom:0px;">Capital total: <strong>${{$contrata->cantidad_pagar}}</strong></p>
                </div>
              
            </div>
            <div style="clear:both;">

                <div class="quarter-page">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Pago</th>
                            <th scope="col">Firma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i; $i<=20; $i++)
                                @php
                                    if($i > sizeof($fechas))
                                        break;
                                @endphp
                                
                                <tr>
                                    <td style="width: 10%;">{{$count}}</td>
                                    <td style="width: 25%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                    <td style="width: 15%;">${{$contrata->pagos_contrata}}</td>
                                    <td style="width: 50%;"></td>        
                                </tr>
                                @php
                                    $count++;  
                                @endphp
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="quarter-page">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Pago</th>
                            <th scope="col">Firma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i; $i<=40; $i++)
                                @php
                                    if($i > sizeof($fechas))
                                        break;
                                @endphp
                                <tr>
                                <td style="width: 10%;">{{$count}}</td>
                                    <td style="width: 25%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                    <td style="width: 15%;">${{$contrata->pagos_contrata}}</td>
                                    <td style="width: 50%;"></td>      
                                </tr>
                                @php
                                    $count++;  
                                @endphp
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="quarter-page">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Pago</th>
                            <th scope="col">Firma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i; $i<=60; $i++)
                                @php
                                    if($i > sizeof($fechas))
                                        break;
                                @endphp
                                <tr>
                                <td style="width: 10%;">{{$count}}</td>
                                    <td style="width: 25%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                    <td style="width: 15%;">${{$contrata->pagos_contrata}}</td>
                                    <td style="width: 50%;"></td>           
                                </tr>
                                @php
                                    $count++;  
                                @endphp
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="quarter-page">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Pago</th>
                            <th scope="col">Firma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i; $i<=80; $i++)
                                @php
                                    if($i > sizeof($fechas))
                                        break;
                                @endphp
                                <tr>
                                <td style="width: 10%;">{{$count}}</td>
                                    <td style="width: 25%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                    <td style="width: 15%;">${{$contrata->pagos_contrata}}</td>
                                    <td style="width: 50%;"></td>        
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