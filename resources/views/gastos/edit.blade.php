@extends('layouts.layout')

@section('main')

<h2 class="content-heading">Agregar gasto</h2>
<div class="block" id="app">
    <div class="block-content">
        <form action="{{ route('vista.gastos.update', $gasto) }}" method="post">
            @csrf
            @method("PUT")

            <div class="form-group">
                <label for="">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="Ingrese la cantidad del gasto" value="{{$gasto->cantidad}}">
                @error('cantidad')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-control" v-model="categoria_id" v-on:change="changeCategory">
                    <option value="">Seleccione</option>
                    <option v-for="categoria in categorias" v-bind:value="categoria.id">
                        @{{ categoria.categoria }}
                    </option>
                </select>
                @error('categoria_id')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="subcategoria_id">Subcategoría</label>
                <select name="subcategoria_id" id="subcategoria_id" class="form-control" v-model="subcategoria_id">
                    <option value="">Seleccione</option>
                    <option v-for="subcategoria in subcategorias" v-bind:value="subcategoria.id" v-if="subcategoria.id_categoria == categoria_id">
                        @{{ subcategoria.sub_categoria }}
                    </option>
                </select>
                @error('subcategoria_id')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="">Información</label>
                <textarea name="informacion" id="" cols="30" rows="10" class="form-control" placeholder="Ingrese información del gasto hecho">{{$gasto->informacion}}</textarea>
                @error('informacion')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

       
            <div class="form-group">
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> editar gasto
                </button>    
            </div>
            

        </form>     

    </div>
   
</div>


@endsection

@section('styles')
    <style>
    </style>
@endsection


@section('scripts')

    <script>
        c_id = "{!!$gasto->categoria_id!!}"
        s_id = "{!!$gasto->subcategoria_id!!}"
    </script>
    <script src="{{ asset('js/agregarGastos/agregarGastos.js') }}"></script>    

    

@endsection