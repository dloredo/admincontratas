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
                <input type="text" class="form-control" id="nombres" name="nombres" placeholder="">
            </div>
            <div class="form-group col-md-6">
                <label for="apellidos">Apellido(s)*</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Usuario</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="">
            </div>
            <div class="form-group col-md-6">
                <label for="id_rol">Rol</label>
                <select id="id_rol" name="id_rol" class="form-control">
                    <option selected>Elija el rol</option>
                    <option value="1">Administrador<option>
                    <option value="2">Cobrador<option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="direccion">Direccion</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="">
            </div>
            <div class="form-group col-md-6">
                <label for="telefono">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="">
            </div>
        </div>
       
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <br>
    </div>
</div>
@endsection