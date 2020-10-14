@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Añadir nuevo cliente</h2>
<div class="block">
    <div class="block-content">

    <form action="{{ route('agregarClienteNuevo') }}" method="post">
    @csrf
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Nombre(s)*</label>
                <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres') }}" autocomplete="nombres" autofocus placeholder="Nombre(s)">
                @error('nombres')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" autocomplete="direccion" placeholder="Av. Pablo Silva #555">
            @error('direccion')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Telefóno</label>
                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}" autocomplete="telefono" placeholder="+52 3124567891">
                @error('telefono')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>Hora de cobro</label>
                <input type="text" class="form-control @error('hora_cobro') is-invalid @enderror" id="hora_cobro" name="hora_cobro" value="{{ old('hora_cobro') }}" autocomplete="hora_cobro" placeholder="De 3 a 4 de la tarde">
                @error('hora_cobro')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Colonia</label>
                <input type="text" class="form-control @error('colonia') is-invalid @enderror" id="colonia" name="colonia" value="{{ old('colonia') }}" autocomplete="colonia" placeholder="Prados del sur">
                @error('colonia')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>Ciudad</label>
                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" autocomplete="ciudad" placeholder="Colima">
                @error('ciudad')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Agregar cliente nuevo</button>
         <a href="{{ route('vista.clientes') }}"><button type="button" class="btn btn-danger">Cancelar</button></a>
    </form>

    <br>
    </div>
</div>
@endsection
