@extends('layouts.layout')

@section('main')
@foreach ($contrata as $dato)
<h2 class="content-heading">Editar o renovar contrata a {{ $dato->nombres }}</h2>
<div class="block" id="app">
    <div class="block-content">
        <form action="{{ route('updateContrata' , $dato->id) }}" method="post">
            @csrf

            <div class="form-row">
                <div class="form-group col-sm-4 col-md-2">
                    <label>Número de contrata</label>
                    <div class="input-group-prepend">
                        <input type="number" class="form-control @error('numero_contrata') is-invalid @enderror" id="numero_contrata" name="numero_contrata" value="{{ old('numero_contrata') }}" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Cantidad a prestar</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number"  class="form-control @error('cantidad_prestada') is-invalid @enderror" id="cantidad_prestada" name="cantidad_prestada" placeholder="Ingrese el prestamo que se hara" value="{{ old('cantidad_prestada') }}" v-model="prestamo" autocomplete="cantidad_prestada" >
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Fecha de entrega</label> <br>
                    <input type="date" class="form-control @error('fecha_entrega') is-invalid @enderror" id="fecha_entrega" name="fecha_entrega" value="{{ date('Y-m-d') }}" value="{{ old('fecha_entrega') }}" autocomplete="fecha_entrega">
                    @error('fecha_entrega')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Tipo de contrata</label>
                    <select class="custom-select @error('tipo_plan_contrata') is-invalid @enderror" v-on:change="elegirDiasKeyUP" name="tipo_plan_contrata" id="tipo_plan_contrata" value="{{ old('tipo_plan_contrata') }}" v-model="tipoPagos" v-on:change="resetEndDate" autocomplete="tipo_plan_contrata">
                        <option value="Pagos diarios">Pagos diarios</option>
                        <option value="Pagos por semana">Pagos por semana</option>
                    </select>
                </div>

                <div class="form-group col-md-4" v-if="(tipoPagos == 'Pagos diarios')">
                    <div class="form-group row">
                        <label class="col-12">Opciones de pago</label>
                        <div class="col-12">
                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                <input class="custom-control-input" type="radio" v-on:change="elegirDiasKeyUP" name="opcionesPago" v-model="opcionesPago" id="example-inline-radio1" value="1" checked="">
                                <label class="custom-control-label" for="example-inline-radio1">Todos los dias</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline mb-5">
                                <input class="custom-control-input" type="radio" v-on:change="elegirDiasKeyUP" name="opcionesPago" v-model="opcionesPago" id="example-inline-radio2" value="2">
                                <label class="custom-control-label" for="example-inline-radio2">Elegir dias</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12" v-if="(tipoPagos == 'Pagos diarios' && opcionesPago== 2)">
                    <label class="col-12">Dias para cobrar</label>
                    
                    <div class="col-12">
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek" v-on:change="elegirDiasKeyUP" value="1" name="daysOfWeek[]" id="lunes" value="true" checked>
                            <label class="custom-control-label" for="lunes">Lunes</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek" v-on:change="elegirDiasKeyUP" value="2" name="daysOfWeek[]" id="martes" value="true" checked>
                            <label class="custom-control-label" for="martes">Martes</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek" v-on:change="elegirDiasKeyUP" value="3" name="daysOfWeek[]" id="miercoles" value="true" checked>
                            <label class="custom-control-label" for="miercoles">Miercoles</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek" v-on:change="elegirDiasKeyUP" value="4" name="daysOfWeek[]" id="jueves" value="true" checked>
                            <label class="custom-control-label" for="jueves">Jueves</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek"  v-on:change="elegirDiasKeyUP" value="5" name="daysOfWeek[]" id="viernes" value="true" checked>
                            <label class="custom-control-label" for="viernes">Viernes</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek" v-on:change="elegirDiasKeyUP" value="6" name="daysOfWeek[]" id="sabado" value="true">
                            <label class="custom-control-label" for="sabado">Sábado</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                            <input class="custom-control-input" type="checkbox" v-model="daysOfWeek" v-on:change="elegirDiasKeyUP" value="0" name="daysOfWeek[]" id="domingo" value="true">
                            <label class="custom-control-label" for="domingo">Domingo</label>
                        </div>
                    </div>
                 </div> 

            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Cantidad de dias o semanas</label>
                    <div class="input-group-prepend">
                        <input type="number" class="form-control @error('dias_plan_contrata') is-invalid @enderror" id="dias_plan_contrata" name="dias_plan_contrata" v-on:keyup="diasPlanKeyUp" placeholder="Ejem: 80 dias, 10 semanas" value="{{ old('dias_plan_contrata') }}" v-model="diasPlan">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Comisión del prestamo</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('comision') is-invalid @enderror" id="comision" name="comision" placeholder="Ingrese la comisión del prestamo" v-on:keyup="comisionPrestamoKeyUp" value="{{ old('comision') }}" v-model="comisionPrestamo">
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label>Fecha de inicio de pago</label> <br>
                    <input type="date" onkeypress="return false" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" @change="getEndTime" autocomplete="fecha_inicio">
                    @error('fecha_inicio')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Cantidad a pagar por dia o cada semama</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                        <input type="number" class="form-control @error('pagos_contrata') is-invalid @enderror" id="pagos_contrata" name="pagos_contrata" placeholder="" value="{{ old('pagos_contrata') }}" v-on:keyup="pagosContrataKeyUp" v-model="cantidadPago">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Comisión mensual</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">%</span>
                        <input type="number" class="form-control @error('porcentaje_comision') is-invalid @enderror" id="porcentaje_comision" name="porcentaje_comision" v-model="porcentajeComision" placeholder="0" readonly value="{{ old('porcentaje_comision') }}" autocomplete="pagos_contrata">
                    </div>
                </div>
                
                

                <div class="form-group col-md-4">
                    <label>Fecha de termino</label> <br>
                    <input type="date" class="form-control @error('fecha_termino') is-invalid @enderror" id="fecha_termino" name="fecha_termino" value="{{ old('fecha_termino') }}" autocomplete="fecha_termino" readonly>
                    @error('fecha_termino')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Hora de cobro</label>
                    <input type="text" class="form-control @error('hora_cobro') is-invalid @enderror" id="hora_cobro" name="hora_cobro" value="{{ old('hora_cobro') }}" autocomplete="hora_cobro" placeholder="De 3 a 4 de la tarde">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Editar o renovar contrata a {{ $dato->nombres }}</button>
        </form>

        <br>
    </div>
</div>
@endforeach
@endsection


@section('scripts')
<script src="{{ asset('js/contratas/contratas.js') }}"></script>
@endsection