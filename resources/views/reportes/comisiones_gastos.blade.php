@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Reporte comisiones y gastos</h2>
<div class="block">
    <div class="block-content">
        <form class="form-inline" action="{{ route('comisiones_gastos') }}" method="get" id="filtrar_rango_fecha">
            <div class="form-group">
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{ $fecha_inicio ?? date('Y-m-d')}}">
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="{{ $fecha_fin ?? date('Y-m-d')}}">
            </div>
            <button type="button" class="btn btn-success" onclick="document.getElementById('filtrar_rango_fecha').submit()">Filtrar</button>
        </form>
        <br>
        <table id="tableComisionesGastos" class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
            <tr style="text-align: center;">
                <td colspan="4">Fecha: {{ date('d-m-Y') }}</td>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col" style="font-size: 24px;" >REPORTE DE CORTES GRAL </th>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col" style="font-size: 24px;" >COMISIONES Y GASTOS </th>
            </tr>
            <tr style="text-align: center;"> 
                <th colspan="4" scope="col"> <br> </th>
            </tr>
            <tr style="text-align: center;">
                <th scope="col">FECHA</th>
                <th scope="col">COMISIONES</th>
                <th scope="col">GASTOS</th>
                <th scope="col">SALDO</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $saldo = 0;
                @endphp
                @foreach ($comisiones_gastos as $comisiones_gastos)
                    <tr style="text-align: center; font-size: 15px;">
                        <td>{{date('d-m-Y', strtotime($comisiones_gastos->created_at))}}</td>
                        <td>{{ $comisiones_gastos->comisiones }}</td>
                        <td>{{ $comisiones_gastos->gastos }}</td>
                        @php
                            $saldo += $comisiones_gastos->comisiones - $comisiones_gastos->gastos
                        @endphp
                        <td>{{ $saldo }}</td>
                    </tr>    
                @endforeach
            </tbody>
          </table>
    </div>
</div>
@endsection

@section('styles')
    
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
    $('#tableComisionesGastos').DataTable({
    dom: 'Blfrtip',
    buttons: [    
        {
            extend: 'print',
            title: 'REPORTE COMISIONES Y GASTOS',
        }
    ]
});
</script>

@endsection