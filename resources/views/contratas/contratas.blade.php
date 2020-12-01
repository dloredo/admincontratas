@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contratas de todos los usuarios</h2>

<div class="block">
    <div class="block-content">
        <table class="table table-hover">
            <thead>
                <tr style="text-align: center;">
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Fecha termino</th>
                    <th style="text-align: center;">Cantidad prestada</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contratas as $contrata)
                        <tr style="text-align: center;">
                            <th scope="row">{{$contrata->id}}</th>
                            <td>{{ $contrata->nombres }}</td>
                            <td>{{date('d-m-Y', strtotime($contrata->fecha_inicio))}}</td>
                            <td>{{date('d-m-Y', strtotime($contrata->fecha_termino))}}</td>
                            <td style="text-align: center;"><?php echo "$" . number_format(round(((float)$contrata->cantidad_prestada)),2,'.',',');?> </td>
                            <td>
                            <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-outline-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block">Opciones</span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Opciones</h5>
                                <a class="dropdown-item" target="_blank" href="{{ route('verPagosContrata' , $contrata->id) }}">
                                    <i class="fa fa-money mr-5"></i> Agregar pago
                                </a>
                                <a class="dropdown-item" target="_blank" href="{{ route('editarContrata' , $contrata->id) }}">
                                    <i class="fa fa-edit mr-5"></i> Editar contrata
                                </a>
                            </div>
                        </div>
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