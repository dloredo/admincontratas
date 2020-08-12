@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Categorias</h2>
<div class="block">
    <div class="block-content">
        <form action="{{ route('agregarCategoria') }}" method="post" class="form-inline">
            @csrf
            <div class="form-group mx-sm-3 mb-2">
                <input class="form-control" type="text" name="tipo_gasto" id="tipo_gasto" placeholder="Ingrese la categoria">  
            </div> 
            <input type="submit" value="Agregar" class="btn btn-success mb-2">
        </form> <br> 
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $categoria)
                <tr>
                    <th scope="row">{{ $categoria->id }}</th>
                    <td>{{ $categoria->categoria }}</td>
                    <td>
                        <a href="{{ route('pre_edit' , $categoria->id) }}" type="button" class="btn btn-info">Editar</a>
                        <form action="{{ route('destroy' , $categoria->id) }}" method="post">
                            @csrf
                            <input type="submit" value="Eliminar" class="btn btn-danger">
                        </form>
                        
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