@extends('layouts.layout')

@section('main')

@foreach ($contratas as $contrata)
<h2 class="content-heading">
    Cantidad a pagar por dia: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?> <br>
    Adelanto: <?php echo "$" . number_format(round(((float)$contrata->adelanto)),2,'.',',');?> <br>
    Adeudo: <?php echo "$" . number_format(round(((float)$contrata->adeudo)),2,'.',',');?> <br>
    Total a pagar: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata + $contrata->adeudo - $contrata->adelanto )),2,'.',',');?>
</h2>
@endforeach

<div class="block">
    <div class="block-content">
        <div class="block">
            
            <div class="table-responsive-sm">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Fecha</th>
                            <th style="text-align: center;">Cantidad</th>
                            <th style="text-align: center;">Adeudo</th>
                            <th style="text-align: center;">Adelanto</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $fechaInicio = strtotime($contrata->fecha_inicio); ?>
                    <?php $fechaFin = strtotime($contrata->fecha_termino); ?>
                    @foreach ($contratas as $contrata)
                    @for( $i = $fechaInicio+86400; $i <= $fechaFin; $i+=86400  )
                        <tr>
                            <td style="text-align: center;"><?php echo date("d-m-Y" , $i)  ?></td>
                            <td style="text-align: center;">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" size="5" class="form-control" style="text-align: center;" value="{{ $contrata->pagos_contrata + $contrata->adeudo - $contrata->adelanto }}" placeholder="{{ $contrata->pagos_contrata + $contrata->adeudo - $contrata->adelanto }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" size="5" class="form-control" style="text-align: center;" value="{{ $contrata->adeudo }}" placeholder="{{ $contrata->adeudo }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" size="5" class="form-control" style="text-align: center;" value="{{ $contrata->adelanto }}" placeholder="{{ $contrata->adelanto }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="" type="button" class="btn btn-primary">Agregar pago</a>
                            </td>
                        </tr>
                    @endfor
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection