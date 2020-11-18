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
    
    body { margin: 5px 10px; }

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
</style>

    
    @for($x = 0; $x < sizeof($chunks_fechas); $x++)
        @php
            $fechas = $chunks_fechas[$x];
            $i = 1;
        @endphp
        <div class="main-page">
            <div class="half-page">
                <p class="outside-text">Forma de pago: <strong>{{$contrata->tipo_plan_contrata}}</strong></p>
                <p class="outside-text">Capital total: <strong>${{$contrata->cantidad_pagar}}</strong></p>
            </div>

            <div class="half-page">
                <p class="outside-text"><strong>{{$contrata->cliente->nombres}}</strong></p>
            </div>
        </div>

        <div class="{{ ($x < sizeof($chunks_fechas)-1)? 'main-page' : '' }}">
            <div class="quarter-page">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Cantidad</th>
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
                                <td style="width: 10%;">{{$i}}</td>
                                <td style="width: 30%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                <td style="width: 20%;">${{$contrata->pagos_contrata}}</td>
                                <td style="width: 40%;"></td>        
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="quarter-page">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Cantidad</th>
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
                            <td style="width: 10%;">{{$i}}</td>
                                <td style="width: 30%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                <td style="width: 20%;">${{$contrata->pagos_contrata}}</td>
                                <td style="width: 40%;"></td>      
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="quarter-page">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Cantidad</th>
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
                            <td style="width: 10%;">{{$i}}</td>
                                <td style="width: 30%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                <td style="width: 20%;">${{$contrata->pagos_contrata}}</td>
                                <td style="width: 40%;"></td>           
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="quarter-page">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Cantidad</th>
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
                            <td style="width: 10%;">{{$i}}</td>
                                <td style="width: 30%;">{{ date('d-m-Y', strtotime( $fechas[$i-1]["fecha_pago"])) }}</td>
                                <td style="width: 20%;">${{$contrata->pagos_contrata}}</td>
                                <td style="width: 40%;"></td>        
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            
        </div>
    @endfor
</body>
</html>