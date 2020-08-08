@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Contratas pagadas y no pagadas</h2>


<div class="block">
    <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link  {{ (Request::is('contratas-no-pagadas'))? 'active' : '' }}" href="{{ route('vista.noPagadas') }}">Contratas no pagadas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('contratas-pagadas'))? 'active' : '' }}" href="{{ route('vista.Pagadas') }}">Contratas pagadas</a>
        </li>
        <li class="nav-item ml-auto">
            <a class="nav-link" href="#btabs-static-settings">
                <i class="si si-settings"></i>
            </a>
        </li>
    </ul>
    <div class="block-content block-content-full">

        <div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
            <!-- Row #3 -->
            <div class="block-content tab-content">
                <div class="block block-themed block-mode-loading-inverse block-transparen w-100">
                   
                        @if(Request::is('contratas-no-pagadas'))
                            @include ('listados.contratas_no_pagadas')
                        @else
                            @include ('listados.contratas_pagadas')
                        @endif
                    
                </div>
            </div>

            <!-- END Row #3 -->
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