@extends('layouts.layout')

@section('main')


@if(Session::get('saved'))
<div class="alert alert-success alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Correcto</h3>
    <p class="mb-0">{{Session::get('message')}}</p>
</div>
@endif


<h2 class="content-heading">Cambiar contraseña</h2>
<div class="block">
    <div class="block-content">
        <form action="{{ route('cambiar.contraseña.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="old_password">Ingresa la contraseña actual</label>
                    <input type="text" class="form-control @error('old_password') is-invalid @enderror" id="old_password" name="old_password" required>
                    @error('old_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="new_password">Ingresa la contraseña nueva</label>
                    <input type="text" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                    @error('new_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
              
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password_confirmation">Confirma la contraseña</label>
                    <input type="text" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Cambiar</button>
        </form>

        <br>
    </div>
</div>
@endsection