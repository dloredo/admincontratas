@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Numeros habiles</h2>

<div class="block">
    <div class="block-content">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center;">#</th>
                    <th style="text-align: center;">Nombre</th>
                    <th style="text-align: center;">Prestamo</th>
                    <th style="text-align: center;">Comisión</th>
                    <th style="text-align: center;">Fecha de termino</th>
                    <th style="text-align: center;">Telefono</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clienteshabiles as $cliente)
                <tr>
                    <th style="text-align: center;">{{ $cliente->id }}</th>
                    <th style="text-align: center;">{{ $cliente->nombres }}</th>
                    <th style="text-align: center;">{{ $cliente->cantidad_prestada }}</th>
                    <th style="text-align: center;">{{ $cliente->comision }}</th>
                    <th style="text-align: center;">{{ $cliente->fecha_termino }}</th>
                    <td style="text-align: center;">{{ $cliente->telefono }}</td>
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