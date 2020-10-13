@extends('layouts.layout')

@section('main')
@foreach ( $categoria as $categoria )
<h2 class="content-heading">Sub-categorias de {{ $categoria->categoria }}</h2>
@endforeach
<div style="float:left;">
    <a href="{{ route('vista.categorias') }}" class="btn btn-danger">Atrás</a>
</div>
<div style="float:right;">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarSubCategoria">Agregar subcategoria</button>
</div>
<br> <br> <br>
<div class="block">
    <div class="block-content">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center;">#</th>
                    <th style="text-align: center;">Sub-Categoria</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sub_categorias as $sub)
                <tr>
                    <th style="text-align: center;">{{ $sub->id }}</th>
                    <td style="text-align: center;">{{ $sub->sub_categoria }}</td>
                    <td style="text-align: center;">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editarSubCategoria{{ $sub->id }}">Editar</button>
                        <a href="{{ route('eliminarSubcategoria' , $sub->id) }}" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="agregarSubCategoria" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Agregar Subcategoria</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="formAgregar" action="{{ route('agregarSubcategoria' , $categoria->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="sub_categoria">Nombre de la sub-categoria</label>
                            <input class="form-control" type="text" name="sub_categoria" id="sub_categoria" placeholder="Ingrese la subcategoria">
                        </div>   
                    </form> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formAgregar').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Agregar subcategoria
                </button>
            </div>
        </div>
    </div>
</div>

@foreach ($sub_categorias as $sub)
<div class="modal fade" id="editarSubCategoria{{ $sub->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-modal="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Editar Subcategoria {{ $sub->sub_categoria }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="formEditar" action="{{ route('editarSubcategoria' , $sub->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="sub_categoria">Nombre de la sub-categoria</label>
                            <input class="form-control" type="text" name="sub_categoria" id="sub_categoria" value="{{ $sub->sub_categoria }}" placeholder="Ingrese la subcategoria">
                        </div>   
                    </form> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" onclick="document.getElementById('formEditar').submit()" type="button" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Editar subcategoria
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