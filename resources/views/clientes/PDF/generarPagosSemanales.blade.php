@section('styles')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection



@foreach ($clientes as $cliente)
    @if( $contrata->id_cliente == $cliente->id )
        <h2 class="content-heading">Cliente: {{ $cliente->nombres }} {{ $cliente->apellidos }}</h2>
    @endif
@endforeach


<div class="block">
    <div class="block-content">
        
    </div>
</div>

