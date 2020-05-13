@extends('layouts.layout')

@section('main')
<h2 class="content-heading">Fixed Header - Classic Inverse</h2>
<p>This is the classic style for the Header but dark themed.</p>

    <!-- Dummy content -->
    <h2 class="content-heading">Dummy Content <small>To preview Header behaviour on scroll</small></h2>
    <div class="block">
        <div class="block-content">
            <p class="text-center py-100">...</p>
        </div>
    </div>
    <div class="block">
        <div class="block-content">
            <p class="text-center py-100">...</p>
        </div>
    </div>
    <div class="block">
        <div class="block-content">
            <p class="text-center py-100">...</p>
        </div>
    </div>
    <div class="block">
        <div class="block-content">
            <p class="text-center py-100">...</p>
        </div>
    </div>
@endsection

@section('styles')
    
@endsection


@section('scripts')

    <script>
        alert("Seccion de scripts")
    </script>

@endsection