<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <title>IntiSoft</title>
    <link rel="icon"
        type="image/x-icon"
        href="../src/assets/img/logo.png" />
    <link href="../layouts/semi-dark-menu/css/light/loader.css"
        rel="stylesheet"
        type="text/css" />
    <link href="../layouts/semi-dark-menu/css/dark/loader.css"
        rel="stylesheet"
        type="text/css" />
    <script src="../layouts/semi-dark-menu/loader.js"></script>
    @include('layouts.theme.styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />
    {{-- <link href="{{ asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css"> --}}

</head>

<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('layouts.theme.header')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container"
        id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('layouts.theme.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content"
            class="main-content">
            <div class="layout-px-spacing">@yield('content')</div>

            <!--  BEGIN FOOTER  -->
            @include('layouts.theme.footer')
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    @include('layouts.theme.scripts')
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    {{-- <script src="{{ asset('plugins/apex/apexcharts.min.js') }}"></script> --}}
    @stack('scripts')
</body>

</html>
