@extends('layouts.layout')

@section('main')

@if(Auth::user()->id_rol == 1)
<h2 class="content-heading">Agregar gasto</h2>
<div class="block">
    <div class="block-content">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-popin">Agregar gasto nuevo</button>
        <br><br>

        <nav >
            <form class="form-inline">
                <select class="custom-select mr-sm-2" name="buscar_categoria" id="buscar_categoria">
                    <option selected value="">Sin filtro...</option>
                    <option value="Sin categoria">Sin categoria</option>
                    @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->categoria }}">{{ $categoria->categoria }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </nav>
    <br>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Fecha de gasto</th>
                        <th scope="col">Cantidad gastada</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Información</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gastos_admin as $gasto)
                    <tr>
                        <th scope="row">{{ date('d-m-Y', strtotime($gasto->fecha_gasto)) }}</th>
                        <td><?php echo "$" . number_format(round(((float)$gasto->cantidad)),2,'.',',');?></td>
                        <td>{{ $gasto->categoria }}</td>
                        <td>{{ $gasto->informacion }}</td>
                        <td> <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editar{{ $gasto->id }}">Editar</a> </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>  
    </div>
    <br>
</div>
@else
<h2 class="content-heading">Agregar gasto</h2>
<div class="block">
    <div class="block-content">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-popin">Agregar gasto nuevo</button>
        <br><br>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Fecha de gasto</th>
                        <th scope="col">Cantidad gastada</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Información</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gastos as $gasto)
                    <tr>
                        <th scope="row">{{ date('d-m-Y', strtotime($gasto->fecha_gasto)) }}</th>
                        <td><?php echo "$" . number_format(round(((float)$gasto->cantidad)),2,'.',',');?></td>
                        <td>{{ $gasto->categoria }}</td>
                        <td>{{ $gasto->informacion }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>  
    </div>
    <br>
</div>
@endif

<div class="modal fade" id="modal-popin" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Agregar gasto</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="{{ route('agregarGasto') }}" method="post">
                        @csrf
                        <label for="">Ingrese la cantidad del gasto</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="Ingrese la cantidad del gasto">
                        <label for="">Ingrese la categoria</label>
                        <select name="categoria" id="" class="form-control">
                        <option value="Otros pagos">Otros pagos</option>
                            <option value="Contratas">Contratas</option>
                        </select>
                        <label for="">Ingrese caracteristicas</label>
                        <textarea name="informacion" id="" cols="30" rows="10" class="form-control" placeholder="Ingrese una caracteristica del gasto hecho"></textarea>
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Agregar pago
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AGREGAR CATEGORIA AL GASTO  -->
@foreach ($gastos_admin as $gasto)
<div class="modal fade" id="editar{{ $gasto->id }}" tabindex="-1" role="dialog" aria-labelledby="editar{{ $gasto->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Agregar gasto</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="{{ route('editarGasto' , $gasto->id) }}" method="post">
                        @csrf
                        <label>Gasto realizado</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="Ingrese la cantidad del gasto" disabled value="{{ $gasto->cantidad }}">
                        <label>Ingrese la categoria</label>
                        <select name="categoria" id="categoria" class="form-control">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->categoria }}">{{ $categoria->categoria }}</option>
                            @endforeach
                        </select>
                        <label for="">Ingrese caracteristicas</label>
                        <textarea name="informacion" id="" cols="30" rows="10" class="form-control" disabled placeholder="Ingrese una caracteristica del gasto hecho">{{ $gasto->informacion }}</textarea>
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-alt-success">
                    <i class="fa fa-check"></i> Editar categoria
                </button>
                </form>
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