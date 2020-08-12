@extends('layouts.layout')

@section('main')
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
                            @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->categoria }}">{{ $categoria->categoria }}</option>
                            @endforeach
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
@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
 
    </script>

@endsection