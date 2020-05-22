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
            @foreach ($clientes as $cliente)
                @foreach ($contratas as $contrata)
                    @if($cliente->id == $contrata->id_cliente)
                        <tr>
                            <th scope="row">{{$cliente->id}}</th>
                            <td>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?> </td>
                            <td>
                                <a href="" class="btn btn-primary" type="button">Ver pagos</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
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