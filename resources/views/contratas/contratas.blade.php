@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contratas de todos los usuarios</h2>

<div class="block">
    <div class="block-content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Teléfono</th>
                    <th style="text-align: center;">Cantidad prestada</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contratas as $contrata)
                        <tr>
                            <th scope="row">{{$contrata->cliente->id}}</th>
                            <td>{{ $contrata->cliente->nombres }} {{ $contrata->cliente->apellidos }}</td>
                            <td>{{ $contrata->cliente->direccion }}</td>
                            <td>{{ $contrata->cliente->telefono }}</td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?> </td>
                            <td>
                                <a href="" class="btn btn-primary" type="button">Ver pagos</a>
                            </td>
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

    <script>
 
    </script>

@endsection