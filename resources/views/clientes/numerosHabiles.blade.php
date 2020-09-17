@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Numeros habiles</h2>

<div class="block">
    <div class="block-content">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center;">Nombre</th>
                    <th style="text-align: center;">Telefono</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clienteshabiles as $cliente)
                <tr>
                    <th style="text-align: center;">{{ $cliente->nombres }}</th>
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