<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TAIS @yield('title')</title>

    @yield('css_before')
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.css') }}">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/noty/noty.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/noty/animate.css') }}">
    <!-- endinject -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.svg') }}" />
    @yield('css_after')
  </head>
  <body class="header-fixed">
    <!-- partial:partials/_header.html -->
    <nav class="t-header" >
        @include('layouts.partials.navbar')
    </nav>
    <!-- partial -->
    <div class="page-body">
        <!-- partial:partials/_sidebar.html -->
        <div class="sidebar">
            @include('layouts.partials.sidebar')
        </div>
        <!-- partial -->
        <div class="page-content-wrapper">
            <div class="page-content-wrapper-inner">
                <div class="viewport-header">
                    <nav aria-label="breadcrumb">
                        @yield('nav_breadcrumd')
                        {{-- <ol class="breadcrumb has-arrow bg-light rounded">
                            <li class="breadcrumb-item"><a href="#">Principal</a></li>
                        </ol> --}}
                    </nav>
                </div>
                <div class="content-viewport">
                    @yield('contenido')
                </div>
            </div>
            <!-- content viewport ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                @include('layouts.partials.footer')
            </footer>
            <!-- partial -->
        </div>
      <!-- page content ends -->
    </div>
    <!--page body ends -->


    <!-- SCRIPT LOADING START FORM HERE /////////////-->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/core.js') }}"></script>
    <script src="{{ asset('assets/plugins/noty/noty.js') }}"></script>
    <!-- endinject -->
    <!-- Vendor Js For This Page Ends-->
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/charts/chartjs.addon.js') }}"></script>
    <!-- Vendor Js For This Page Ends-->
    <!-- build:js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- endbuild -->
    @yield('js_after')

  </body>
</html>