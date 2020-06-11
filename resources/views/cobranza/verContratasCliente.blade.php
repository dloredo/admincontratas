@extends('layouts.layout')

@section('main')
@foreach ($nombre as $nombre)
<h2 class="content-heading">Contratas de {{ $nombre->nombres }} {{ $nombre->apellidos }}</h2>
@endforeach
<div class="block">
    <div class="block-content">
        <div class="block">
            
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Plan</th>
                            <th style="text-align: center;">Cantidad prestada</th>
                            <th style="text-align: center;">Fecha de Inicio</th>
                            <th class="d-none d-sm-table-cell" style="text-align: center;">Fecha de Termino</th>
                            <th class="d-none d-sm-table-cell" style="text-align: center;">Total pagado</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($contratas as $contrata)
                        <tr>
                            <td style="text-align: center;">{{ $contrata->tipo_plan_contrata }}</td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?></td>
                            <td style="text-align: center;">{{ $contrata->fecha_inicio }}</td>
                            <td style="text-align: center;">{{ $contrata->fecha_termino }}</td>
                            <td style="text-align: center;">{{ $contrata->control_pago }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('verPagosContrata' , $contrata->id) }}" type="button" class="btn btn-primary">Ver pagos</a>
                            </td>
                        </tr>
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