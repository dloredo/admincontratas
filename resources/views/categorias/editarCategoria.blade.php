@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Editar categoria</h2>
<div class="block">
    <div class="block-content">
        <form action="{{ route('edit.categoria' , $categoria->id) }}" method="post" class="form-inline">
            @csrf
            <div class="form-group mx-sm-3 mb-2">
                <input class="form-control" type="text" name="tipo_gasto_nuevo" id="tipo_gasto_nuevo" value="{{ $categoria->categoria }}" placeholder="Ingrese la categoria">
            </div>   
            <input type="submit" value="Editar" class="btn btn-success mb-2">
        </form> <br>
    </div>
</div>
@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection