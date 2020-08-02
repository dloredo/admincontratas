@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contrata de {{ $cliente->nombres }} {{ $cliente->apellidos }}</h2>

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Detalles de la contrata</h3>
    </div>
    <div class="block-content">

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Cantidad a prestar</label>
                <div class="input-group-prepend">
                    <input type="number" class="form-control" value="{{ $contrata->cantidad_prestada }}" disabled>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>Tipo de contrata</label>
                <div class="input-group-prepend">
                    <input type="text" class="form-control" value="{{ $contrata->tipo_plan_contrata }}" disabled>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>Cantidad de dias o semanas</label>
                <div class="input-group-prepend">
                    <input type="number" class="form-control" value="{{ $contrata->dias_plan_contrata }}" disabled>
                </div>
            </div>

        </div>
        <div class="form-row" style="margin-top: 15px;">
            <div class="form-group col-md-4">
                <label>Comisión del prestamo</label>
                <div class="input-group-prepend">
                    <input type="number" class="form-control" value="{{ $contrata->comision  }}" disabled>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label>Cantidad a pagar por dia o cada semama</label>
                <div class="input-group-prepend">
                    <input type="number" class="form-control" value="{{ $contrata->pagos_contrata }}" disabled>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>Porcentaje de comisión</label>
                <div class="input-group-prepend">
                    <input type="text" class="form-control" value="%{{ $contrata->comision_porcentaje }}" disabled>
                </div>
            </div>
        </div>
        <div class="form-row" style="margin-top: 15px;">
            <div class="form-group col-md-4">
                <label>Fecha de inicio</label> <br>
                <div class="input-group-prepend">
                    <input type="text" class="form-control" value="{{ $contrata->fecha_inicio }}" disabled>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label>Fecha de termino</label> <br>
                <div class="input-group-prepend">
                    <input type="text" class="form-control" value="{{ $contrata->fecha_termino }}" disabled>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Historial de pagos</h3>
    </div>
    <div class="block-content">
        <table id="table" class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
                <tr>
                    <th style="text-align: center">Fecha</th>
                    <th style="text-align: center">Cantidad pagada</th>
                    <th style="text-align: center">Adeudo</th>
                    <th style="text-align: center">Adelanto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagos as $pago)

                    <tr>
                        <td style="text-align: center">{{ $pago->fecha_pago }}</td>
                        <td style="text-align: center">{{ $pago->cantidad_pagada }}</td>
                        <td style="text-align: center">{{ $pago->adeudo }}</td>
                        <td style="text-align: center">{{ $pago->adelanto }}</td>
                    </tr>

                @endforeach

            </tbody>
        </table>

    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.css') }}">
@endsection


@section('scripts')
<script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js')  }}"></script>

<script src="{{ asset('assets/js/plugins/datatables/buttons/dataTables.buttons.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.js')  }}"></script>

<script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.colVis.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.html5.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.flash.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.print.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons/jszip.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons/pdfmake.min.js')  }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/buttons/vfs_fonts.js')  }}"></script>
<script>

    $('#table').DataTable({
                    dom: 'Blfrtip',
                    buttons: [
                        {
                        extend: 'pdf',
                        title: 'Historial de pagos de contrata de {!! $cliente->nombres !!} {!! $cliente->apellidos !!}',
                        customize: function(doc) {
                            doc.styles.title = {
                            fontSize: '20',
                            alignment: 'center'
                            }   

                            doc.content[0].text += `
                            Prestamo total:  \${!! $contrata->cantidad_prestada + $contrata->comision !!} `
                        }  
                        },
                        {
                        extend: 'print',
                        title: 'Historial de pagos de contrata de {!! $cliente->nombres !!} {!! $cliente->apellidos !!} \n Prestamo total:  \${!! $contrata->cantidad_prestada +$contrata->comision !!}',
                          
                        }
                    ]
                });
</script>

@endsection