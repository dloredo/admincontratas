@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Agregar contrata nueva a {{ $cliente->nombres  }} {{ $cliente->apellidos }}</h2>

<div class="block" id="app">
    <div class="block-content">
        <form action="{{ route('agregarContrataNueva' , $cliente->id) }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Cantidad a prestar</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('cantidad_prestada') is-invalid @enderror" id="cantidad_prestada" name="cantidad_prestada" placeholder="Ingrese el prestamo que se hara" value="{{ old('cantidad_prestada') }}" v-model="prestamo" autocomplete="cantidad_prestada">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Tipo de contrata</label>
                    <select class="custom-select @error('tipo_plan_contrata') is-invalid @enderror" name="tipo_plan_contrata" id="tipo_plan_contrata" value="{{ old('tipo_plan_contrata') }}" v-model="tipoPagos" autocomplete="tipo_plan_contrata">
                        <option value="Pagos diarios">Pagos diarios</option>
                        <option value="Pagos por semana">Pagos por semana</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Cantidad de dias o semanas</label>
                    <div class="input-group-prepend">
                        <input type="number" class="form-control @error('dias_plan_contrata') is-invalid @enderror" id="dias_plan_contrata" name="dias_plan_contrata" v-on:keyup="diasPlanKeyUp"  placeholder="Ejem: 80 dias, 10 semanas" value="{{ old('dias_plan_contrata') }}" v-model="diasPlan">
                    </div>
                </div>
                
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Comisión del prestamo</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('comision') is-invalid @enderror" id="comision" name="comision" placeholder="Ingrese la comisión del prestamo" v-on:keyup="comisionPrestamoKeyUp" value="{{ old('comision') }}" v-model="comisionPrestamo" >
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label>Cantidad a pagar por dia o cada semama</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('pagos_contrata') is-invalid @enderror" id="pagos_contrata" name="pagos_contrata" placeholder="" value="{{ old('pagos_contrata') }}" v-on:keyup="pagosContrataKeyUp" v-model="cantidadPago">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Porcentaje de comisión</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">%</span>
                        <input type="number" class="form-control @error('porcentaje_comision') is-invalid @enderror" id="porcentaje_comision" name="porcentaje_comision" v-model="porcentajeComision" placeholder="0" readonly value="{{ old('porcentaje_comision') }}" autocomplete="pagos_contrata">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Fecha de inicio</label> <br>
                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" @change="getEndTime" autocomplete="fecha_inicio">
                    @error('fecha_inicio')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label>Fecha de termino</label> <br>
                    <input type="date" class="form-control @error('fecha_termino') is-invalid @enderror" id="fecha_termino" name="fecha_termino" value="{{ old('fecha_termino') }}" autocomplete="fecha_termino" readonly>
                    @error('fecha_termino')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Agregar contrata nueva a {{ $cliente->nombres  }} {{ $cliente->apellidos }}</button>
        </form>

        <br>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{asset('js/contratas/contratas.js')}}"></script>
@endsection