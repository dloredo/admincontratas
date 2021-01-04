@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Cobradores</h2>
<div class="block">
    <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link  {{ (Request::is('cobradores'))? 'active' : '' }}" href="{{ route('vista.cobradores') }}">Saldo de cobradores</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('historial-saldo-cobradores'))? 'active' : '' }}" href="{{ route('vista.historial_cobradores') }}">Historial de cobradores</a>
        </li>
    </ul>
    <div class="block-content block-content-full">
        <div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
            <!-- Row #3 -->
            <div class="block-content tab-content">
                <div class="block block-themed block-mode-loading-inverse block-transparen w-100">
                   
                        @if(Request::is('cobradores'))
                            <h2>Hola1</h2>
                        @elseif(Request::is('historial-saldo-cobradores'))
                            @include ('cobradores._historialCobradores')
                        @endif
                    
                </div>
            </div>
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