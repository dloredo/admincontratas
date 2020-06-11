@extends('layouts.layout')

@section('main')

@foreach ($contratas as $contrata)
<h2 class="content-heading">
    Cantidad a pagar: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?> <br>
    Cantidad a pagar total: <?php echo "$" . number_format(round(((float)$total_pagado)),2,'.',',');?>
</h2>
@endforeach

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Historial de pagos</h3>
    </div>
    <div class="block-content">
        <div class="block">
            <div class="table-responsive-sm">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Fecha</th>
                            <th style="text-align: center;">Cantidad pagada</th>
                            <th style="text-align: center;">Adeudo</th>
                            <th style="text-align: center;">Adelanto</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($contratas as $contrata)
                    @foreach ($pagos as $pago)
                        @if($contrata->tipo_plan_contrata == "Pagos diarios")                     
                            <tr>
                                <td style="text-align: center;">{{ $pago->fecha_pago }}</td>
                                <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->cantidad_pagada )),2,'.',',');?></td>
                                <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->adeudo )),2,'.',',');?></td>
                                <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->adelanto )),2,'.',',');?></td>
                            </tr>              
                        @endif
                        @if ($contrata->tipo_plan_contrata == "Pagos por semana")
                            <tr>
                                <td style="text-align: center;">{{ $pago->fecha_pago }}</td>
                                <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->cantidad_pagada )),2,'.',',');?></td>
                                <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->adeudo )),2,'.',',');?></td>
                                <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->adelanto )),2,'.',',');?></td>
                            </tr>  
                        @endif
                    @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Añadir pago</h3>
    </div>
    <div class="block-content">
        <form action="{{ route('agregarPago' , $id_contrata) }}" method="post">
        @csrf
            <div class="form-row">
            <div class="form-group col-md-4">
                    <label>Fecha de pago</label>
                    <div class="input-group-prepend">
                        <input type="date" class="form-control @error('fecha_pago') is-invalid @enderror" id="fecha_pago" name="fecha_pago" placeholder="Cantidad a pagar" value="{{ old('fecha_pago') }}" v-model="prestamo" autocomplete="fecha_pago">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Cantidad a pagar</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('cantidad_pagada') is-invalid @enderror" id="cantidad_pagada" name="cantidad_pagada" placeholder="Cantidad a pagar" value="{{ old('cantidad_pagada') }}" v-model="prestamo" autocomplete="cantidad_pagada">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Adeudo</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('adeudo') is-invalid @enderror" id="adeudo" name="adeudo" placeholder="Adeudo" value="{{ old('adeudo') }}" v-model="prestamo" autocomplete="adeudo">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Adelanto</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('adelanto') is-invalid @enderror" id="adelanto" name="adelanto" placeholder="Adelanto" value="{{ old('adelanto') }}" v-model="prestamo" autocomplete="adelanto">
                    </div>
                </div>
            </div>
            <input type="submit" value="Agregar pago" class="btn btn-success">
        </form> 
        <br> 
    </div>
</div>
@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection