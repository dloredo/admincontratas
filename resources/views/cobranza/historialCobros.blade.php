@extends('layouts.layout')

@section('main')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Historial de cobros del dia</h3>
        <input class="form-control col-sm-3" type="date" name="date" id="date"/>
        <button class="btn btn-primary" id="btnPlicarFiltro" onclick="filtrar();">Aplicar</button>
    </div>
    <div class="block-content">
        <div class="block">
            <div class="table-responsive-sm">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Cobrador</th>
                            <th style="text-align: center;">Cliente</th>
                            <th style="text-align: center;">No. Contrada</th>
                            <th style="text-align: center;">Cantidad pagada</th>
                            <th style="text-align: center;">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($cobros as $cobro)
                        <tr>
                            <td>
                                {{$cobro->cobrador->name}}
                            </td>
                            <td>
                                {{$cobro->cliente->nombres}}
                            </td>
                            <td>
                                {{$cobro->contrata->id}}
                            </td>
                            <td>
                                {{$cobro->cantidad}}
                            </td>
                            <td>
                                {{$cobro->fecha}}
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                {{ $cobros->links() }}
            </div>
        </div>
    </div>
</div>


@endsection

@section('styles')

@endsection


@section('scripts')

<script>

    const filtrar = () =>
    {
        fecha = document.getElementById("date").value;
        if(fecha != "")
            location.href = "/cobranza/historial/" + fecha
    }
</script>

@endsection