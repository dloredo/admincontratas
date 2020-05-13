@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Añadir nuevo cliente</h2>
<div class="block">
    <div class="block-content">

    <form action="{{ route('agregarClienteNuevo') }}" method="post">
    @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Nombre(s)*</label>
                <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres') }}" autocomplete="nombres" autofocus placeholder="Nombre(s)">
                @error('nombres')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>Apellido(s)*</label>
                <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" autocomplete="apellidos" placeholder="Apellido(s)">
                @error('apellidos')
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
            <div class="form-group col-lg-6">
                <label>Fecha de registro</label>
                <input type="date" class="form-control @error('fecha_registro') is-invalid @enderror" id="fecha_registro" name="fecha_registro" value="{{ old('fecha_registro') }}" autocomplete="fecha_registro">
                @error('fecha_registro')
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
