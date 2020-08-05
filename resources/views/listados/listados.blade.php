@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contratas pagadas y no pagadas</h2>
<div class="block">
    <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#ContratasPagadasNoPagadas">Contratas no pagadas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#btabs-static-profile">Contratas pagadas</a>
        </li>
    </ul>
    <div class="block-content tab-content">
        <div class="tab-pane active" id="ContratasPagadasNoPagadas" role="tabpanel">
            <h4 class="font-w400">Contratas no pagadas</h4>
            <div style="float: right;">
                
                <a href="" type="button" class="btn btn-primary">Filtrar</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="btabs-static-profile" role="tabpanel">
            <h4 class="font-w400">Contratas pagadas</h4>
            <p>...</p>
        </div>
    </div>
</div>


<h2 class="content-heading">Listado de comisiones</h2>
<div class="block">
    <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#listadoComisiones">Listado de comisiones por clientes</a>
        </li>
    </ul>
    <div class="block-content tab-content">
        <div class="tab-pane active" id="listadoComisiones" role="tabpanel">
            <h4 class="font-w400">Contratas no pagadas</h4>
            <p>...</p>
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