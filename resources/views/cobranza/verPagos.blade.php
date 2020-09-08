@extends('layouts.layout')

@section('main')

@if($validar == 1)
    @foreach ($contrata as $contrata)
        @foreach ($pago_anterior as $pago_anterior)
            <h2 class="content-heading">Control de pagos: <br> 
                                        <span style="float: right;">
                                            Cantidad pagada: <?php echo "$" . number_format(round(((float)$total_pagado)),2,'.',',');?> 
                                        </span>
                                        Cantidad a pagar: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?> <br>
                                        Adeudo: <?php echo "$" . number_format(round(((float)$pago_anterior->adeudo)),2,'.',',');?> <br>
                                        Total a pagar: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata + $pago_anterior->adeudo)),2,'.',',');?> 
            </h2>

            <span style="float: right;">
                Cantidad pagada: <?php echo "$" . number_format(round(((float)$total_pagado)),2,'.',',');?> <br>
                Cantidad por pagar: <?php echo "$" . number_format(round(((float)$contrata->cantidad_pagar-$total_pagado)),2,'.',',');?>
            </span>
            <?php $cantidad_pagar_esperada = 0; ?>
            <?php $cantidad_pagar = 0; ?>
            <?php $cantidad_pagar_esperada =  $contrata->pagos_contrata + $pago_anterior->adeudo?> 
            <?php $cantidad_pagar = $contrata->pagos_contrata; ?>
        @endforeach
    @endforeach
@else
    @foreach ($contrata as $contrata)
        <h2 class="content-heading">Control de pagos: <br> 
                                    <span style="float: right;">
                                        Cantidad pagada: <?php echo "$" . number_format(round(((float)$total_pagado)),2,'.',',');?> 
                                    </span>
                                    Cantidad a pagar: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?> <br>
                                    Adeudo: Desconocido, checar en tabla <br>
                                    Total a pagar: <?php echo "$" . number_format(round(((float)$contrata->pagos_contrata)),2,'.',',');?> 
        </h2>

        <span style="float: right;">
            Cantidad pagada: <?php echo "$" . number_format(round(((float)$total_pagado)),2,'.',',');?> <br>
            Cantidad por pagar: <?php echo "$" . number_format(round(((float)$contrata->cantidad_pagar-$total_pagado)),2,'.',',');?>
        </span>
        <?php $cantidad_pagar_esperada = 0; ?>
        <?php $cantidad_pagar = 0; ?>
        <?php $cantidad_pagar_esperada =  $contrata->pagos_contrata?> 
        <?php $cantidad_pagar = $contrata->pagos_contrata; ?>
    @endforeach
@endif
<br><br><br>

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
                            <th style="text-align: center;">Estado</th>
                            <th style="text-align: center;">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @foreach ($pagos as $pago)                                 
                        <tr>
                            <td style="text-align: center;">{{ date('d-m-Y', strtotime($pago->fecha_pago)) }}</td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->cantidad_pagada )),2,'.',',');?></td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->adeudo )),2,'.',',');?></td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$pago->adelanto )),2,'.',',');?></td>
                            <td style="text-align: center; align-items: center; vertical-align: middle">
                                @if ($pago->estatus == 0)
                                    <div class="p-2 bg-danger text-white">
                                        No pagado
                                    </div>
                                @elseif ($pago->estatus == 1)
                                    <div class="p-2 bg-success text-white">
                                        Pagado
                                    </div>
                                @elseif ($pago->estatus == 3)
                                    <div class="p-2 bg-success text-white">
                                        Pagado con adeudo
                                    </div>
                                @else
                                <div class="p-2 bg-success text-white">
                                    Desconocido
                                </div>
                                @endif
                            </td>
                            <td style="text-align: center;">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#pagar{{ $pago->id }}">Pagar</button>
                            </td>
                        </tr>                
                    @endforeach
                    
                    </tbody>
                </table>
                {{ $pagos->links() }}
            </div>
        </div>
    </div>
</div>

<!--<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Agregar nuevo pago</h3>
    </div>
    <div class="block-content">
        <form action="{{ route('agregarPago' , $id_contrata) }}" method="post">
            @csrf
            <div class="form-row align-items-center">
                <div class="col-sm-3 my-1">
                    <label>Fecha de pago</label>
                    <input type="date" class="form-control @error('fecha_pago') is-invalid @enderror" id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}">
                    @error('fecha_pago')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-sm-3 my-1">
                    <label>Cantidad a pagar</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input type="number" class="form-control @error('cantidad_pagada') is-invalid @enderror" id="cantidad_pagada" name="cantidad_pagada" value="{{ isset($cantidad_pagar_esperada) ? $cantidad_pagar_esperada : '' }}" value="{{ old('cantidad_pagada') }}" autocomplete="cantidad_pagada" placeholder="Cantidad a pagar">
                        @error('cantidad_pagada')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-3 my-1">
                    <label>Adeudo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input type="number" class="form-control @error('adeudo') is-invalid @enderror" id="adeudo" name="adeudo" value="{{ old('adeudo') }}" autocomplete="adeudo" placeholder="Adeudo">
                        @error('adeudo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-3 my-1">
                    <label>Adelanto</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input type="number" class="form-control @error('adelanto') is-invalid @enderror" id="adelanto" name="adelanto" value="{{ old('adelanto') }}" autocomplete="adelanto" placeholder="Adelanto">
                        @error('adelanto')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-auto my-1">
                    <input type="hidden" id="cantidad_pagar_dia" name="cantidad_pagar_dia" value="{{ isset($cantidad_pagar) ? $cantidad_pagar : '' }}">
                    <button type="submit" class="btn btn-primary">Agregar pago</button>
                </div>
            </div>
        </form>
        <br>
    </div>
</div> -->
@foreach ($pagos as $pago) 
<div class="modal fade" id="pagar{{ $pago->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Agregar pago para fecha {{ date('d-m-Y', strtotime($pago->fecha_pago)) }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                <h3>Por pagar: {{"$" . number_format(round(((float)$cantidad_pagar_esperada )),2,'.',',') }}</h3>
                    <form action="{{ route('agregarPago' , $pago->id) }}" method="post">
                        @csrf
                        <div class="form-row align-items-center">
                        <label>Cantidad a pagar</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">$</div>
                            </div>
                            <input type="number" class="form-control @error('cantidad_pagada') is-invalid @enderror" id="cantidad_pagada" name="cantidad_pagada" value="{{ old('cantidad_pagada') }}" autocomplete="cantidad_pagada" placeholder="Cantidad a pagar">
                            @error('cantidad_pagada')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                
                        <label>Adeudo</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">$</div>
                            </div>
                            <input type="number" class="form-control @error('adeudo') is-invalid @enderror" id="adeudo" name="adeudo" value="{{ old('adeudo') }}" autocomplete="adeudo" placeholder="Adeudo">
                            @error('adeudo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                       
                            <label>Adelanto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">$</div>
                                </div>
                                <input type="number" class="form-control @error('adelanto') is-invalid @enderror" id="adelanto" name="adelanto" value="{{ old('adelanto') }}" autocomplete="adelanto" placeholder="Adelanto">
                                @error('adelanto')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        
                        <div class="col-auto my-1">
                            <input type="hidden" id="cantidad_pagar_dia" name="cantidad_pagar_dia" value="{{ isset($cantidad_pagar) ? $cantidad_pagar : '' }}">
                        </div>
                    </div>   
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Agregar pago
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection