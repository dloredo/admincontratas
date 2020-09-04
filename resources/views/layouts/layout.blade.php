<!doctype html>
<html lang="es-MX" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Administración</title>

        <meta name="description" content="Panel de administración de contratas">
        <meta name="author" content="Alx">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Administracion">
        <meta property="og:site_name" content="Administracion">
        <meta property="og:description" content="Panel de administración de contratas">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        @yield('styles')
        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.min.css') }}">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
        
    </head>

    <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed page-header-inverse sidebar-inverse">

        @include('layouts.sidebar')

        @include('layouts.header')

        <main id="main-container">

            <!-- Page Content -->
            <div class="content">
                
                @yield('main')

            </div>

        </main>

        @include('layouts.footer')

    </div>

        <script src="{{ asset('assets/js/codebase.core.min.js') }}"></script>
        <script src="{{ asset('assets/js/codebase.app.min.js') }}"></script>

        @yield('scripts')
    </body>
</html>