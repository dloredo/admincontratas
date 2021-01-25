@extends('layouts.layout')

@section('main')


<h2 class="content-heading">Agregar gasto</h2>
<div class="block">
    <div class="block-content">
        <a href="{{route('vista.gastos.create')}}" class="btn btn-success">Agregar gasto nuevo</a>
        <br><br>
        @if (auth()->user()->id_rol == 1)    
            <nav id="app">
                <form class="form-inline">

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
                        <label for="buscar_categoria">Usuarios </label>
                        <select class="custom-select mr-sm-2" name="usuario_id" id="usuario_id">
                            <option selected value="">Sin filtro...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"  @if($usuario_id && $user->id == $usuario_id)selected @endif>{{ $user->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    

                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                </form>
            </nav>
        @endif
    <br>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha de gasto</th>
                        <th scope="col">Cantidad gastada</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Subcategoria</th>
                        <th scope="col">Información</th>
                        @if (auth()->user()->id_rol == 1)   
                            <th scope="col">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gastos as $gasto)
                    <tr>
                        <td>{{ $gasto->nombres }}</td>
                        <th scope="row">{{ date('d-m-Y', strtotime($gasto->fecha_gasto)) }}</th>
                        <td><?php echo "$" . number_format(round(((float)$gasto->cantidad)),2,'.',',');?></td>
                        <td>{{ $gasto->categoria_r->categoria ?? "Sin categoría" }}</td>
                        <td>{{ $gasto->subcategoria->sub_categoria ?? "Sin subcategoría" }}</td>
                        <td>{{ $gasto->informacion }}</td>

                        @if (auth()->user()->id_rol == 1)   
                            <td> <a href="{{route('vista.gastos.edit', $gasto)}}" class="btn btn-primary">Editar</a> </td>
                        @endif
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>  
    </div>
    <br>
</div>


@endsection

@section('styles')
    <style>
        label{
            margin-right: 10px;
        }
    </style>
@endsection


@section('scripts')

    @if(auth()->user()->id_rol == 1)
    <script>
        c_id = "{!! $categoria ?? '' !!}"
        s_id = "{!! $subcategoria ?? '' !!}"
    </script>
    <script src="{{ asset('js/agregarGastos/agregarGastos.js') }}"></script>
    @endif

@endsection