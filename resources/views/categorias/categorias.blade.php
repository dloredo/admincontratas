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
                    <th style="text-align: center;">#</th>
                    <th style="text-align: center;">Categoria</th>
                    <th style="text-align: center;">Sub-categoria</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $categoria)
                <tr>
                    <th style="text-align: center;">{{ $categoria->id }}</th>
                    <td style="text-align: center;">{{ $categoria->categoria }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('VerSubcategorias' , $categoria->id) }}" class="btn btn-success">Ver sub-categorias</a>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editarCategoria{{ $categoria->id }}">Editar</button>
                        <a href="{{ route('destroy' , $categoria->id) }}" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@foreach ($categorias as $categoria)
<div class="modal fade" id="editarCategoria{{ $categoria->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Editar categoria {{ $categoria->categoria }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="formEditar" action="{{ route('edit.categoria' , $categoria->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="tipo_gasto_nuevo">Nombre de la categoria</label>
                            <input class="form-control" type="text" name="tipo_gasto_nuevo" id="tipo_gasto_nuevo" value="{{ $categoria->categoria }}" placeholder="Ingrese la categoria">
                        </div>   
                    </form> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formEditar').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Editar categoria
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection