@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Añadir nuevo usuario</h2>
<div class="block">
    <div class="block-content">
        <form action="{{ route('create.agregarUsuario') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombres">Nombre(s)*</label>
                    <input type="text" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres') }}" id="nombres" name="nombres" placeholder="">
                    @error('nombres')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="apellidos">Apellido(s)*</label>
                    <input type="text" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos') }}" id="apellidos" name="apellidos" placeholder="">
                    @error('apellidos')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Usuario</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" name="name" placeholder="">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="id_rol">Rol</label>
                    <select id="id_rol" name="id_rol" class="form-control @error('id_rol') is-invalid @enderror">
                        <option >Elija el rol</option>
                        <option @if(old('id_rol') == 1) selected @endif value="1">Administrador</option>
                        <option @if(old('id_rol') == 2) selected @endif value="2">Cobrador</option>
                    </select>
                    @error('id_rol')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="direccion">Direccion</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" id="direccion" name="direccion" placeholder="">
                    @error('direccion')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="telefono">Telefono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" id="telefono" name="telefono" placeholder="">
                    @error('telefono')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <br>
    </div>
</div>
@endsection